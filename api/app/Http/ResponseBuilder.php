<?php

declare(strict_types=1);

namespace App\Http;

use Illuminate\Http\{ Request, Response, JsonResponse };
use Illuminate\Http\Exceptions\HttpResponseException;

class ResponseBuilder
{
    private $modelName;

    public function __construct(private $repository) {
        $this->modelName = $this->getModelName();
    }

    private function getModelName()
    {
        // End method requires storing result in a variable prior to using it
        $parts = explode('\\', $this->repository::class);
        // e.g. for class BrandRepository model name will be Brand, this will be used to format messages
        return explode('Repository', end($parts))[0];
    }
    /**
     * Checks whether the request body has all the required keys.
     * 
     * @param array $requestKeys Array of keys in request payload.
     * @param array $requiredKeys Array of required keys.
     * @param string $this->modelName Name of current model, used for error formatting.
     * 
     * @return void
     **/
    private function checkIfAllRequiredKeys(
        array $requestKeys,
        array $requiredKeys,
    ): void
    {
        foreach ($requiredKeys as $requiredKey) {
            if (!in_array($requiredKey, $requestKeys)) {
                throw new HttpResponseException(
                    response()->json(
                        'Error creating ' . strtolower($this->modelName) . ": Missing key $requiredKey",
                        Response::HTTP_BAD_REQUEST
                    )
                );
            }
        }
    }

    private function informWhichKeysWereDuplicate(
        string $errorMessage
    )
    {
        $pattern = "/Key \((\w+)\)=\(([^)]+)\)/";
        preg_match($pattern, $errorMessage, $matches);

        /** Due to the way Laravel error messages for code 23505 
         * (duplicate entries) are formatted, it's guaranteed that
         * count($matches) will be 3, but still extra 'else' block
         * was added to be sure
         */
        if (count($matches) >= 3) {
            $key = $matches[1];
            $value = $matches[2];
            return "$key with value $value already exists";
        } else {
            return $errorMessage;
        }
    }

    public function getAllResponse(): JsonResponse
    {
        return response()->json($this->repository->getAll());
    }

    public function getByIdResponse(string $id): JsonResponse
    {
        $data = $this->repository->getById($id);
        return $data 
            ? response()->json($data)
            : self::notFound();
    }

    public function postResponse(array $params, array $requiredKeys): JsonResponse
    {
        self::checkIfAllRequiredKeys(array_keys($params), $requiredKeys, $this->modelName);

        try {
            $arr = array_map(fn($key) => $params[$key], $requiredKeys);

            $data = $this->repository->create(...$arr);

            /**
             * If return type from repository is string, it means one of the 
             * associated models (either given model or one of its parents with foreign keys)
             * was not found, in that case, string will give information about which model's key
             * was not found
             */
            if (gettype($data) === 'string') {
                return response()->json(
                    ['message' => $data], 
                    Response::HTTP_NOT_FOUND);
            }

            /** 
             * Use response()->json for most of responses and
             * new JsonResponse when need to set custom headers,
             * can use $status = 201 for status code
             **/
            return response()->json($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            /**
             * If error came due to duplicate entry in database (code 23505), 
             * inform which key is duplicate
             */
            $errorDetails = $e->getCode() === '23505'
                ? self::informWhichKeysWereDuplicate($e->getMessage())
                : $e->getMessage();

            throw new HttpResponseException(
                response()->json(
                    ['message' => 'Error creating ' . strtolower($this->modelName) . ": " . $errorDetails],
                    Response::HTTP_BAD_REQUEST
                )
            );
        }
    }

    /**
     * Similar to postResponse but this time validation for keys
     * is made in repository instead in order to save performance
     */
    public function createManyResponse(array $params): JsonResponse
    {
        try {
            $data = $this->repository->createMany($params);

            if (gettype($data) === 'string') {
                return response()->json(
                    ['message' => $data], 
                    Response::HTTP_NOT_FOUND);
        }

            return response()->json($data, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            throw new HttpResponseException(
                response()->json(
                    'Error creating ' . strtolower($this->modelName) . ": " . $e->getMessage(),
                    Response::HTTP_BAD_REQUEST
                )
            );
        }
    }

    public function signIn(array $params, array $requiredKeys, bool $withCredentials=true): JsonResponse
    {
        self::checkIfAllRequiredKeys(array_keys($params), $requiredKeys, $this->modelName);

        try {
            $arr = array_map(fn($key) => $params[$key], $requiredKeys);

            $data = $withCredentials
                ? $this->repository->signInWithCredentials(...$arr)
                : $this->repository->signInWithSocials(...$arr);

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            $errorDetails = $e->getCode() === '23505'
                ? self::informWhichKeysWereDuplicate($e->getMessage())
                : $e->getMessage();

            throw new HttpResponseException(
                response()->json(
                    ['message' => 'Error signing in ' . strtolower($this->modelName) . ": " . $errorDetails],
                    Response::HTTP_BAD_REQUEST
                )
            );
        }
    }

    public function signOut(array $params, array $requiredKeys)
    {
        self::checkIfAllRequiredKeys(array_keys($params), $requiredKeys, $this->modelName);

        try {
            $arr = array_map(fn($key) => $params[$key], $requiredKeys);

            $data = $this->repository->signOut(...$arr);

            if (gettype($data) === 'string') {
                return response()->json(
                    ['message' => $data], 
                    Response::HTTP_NOT_FOUND);
            }

            return response()->json('Successfully signed out.', Response::HTTP_OK);
        } catch (\Exception $e) {
            throw new HttpResponseException(
                response()->json(
                    'Error signing out ' . strtolower($this->modelName) . ": " . $e->getMessage(),
                    Response::HTTP_BAD_REQUEST
                )
            );
        } 
    }

    public function updateResponse(string $id, array $params, $possibleKeys): JsonResponse
    {
        try {
            $arr = [$id, ...array_map(
                fn($possibleKey) => $params[$possibleKey] ?? null,
                $possibleKeys
            )];

            $data = $this->repository->update(...$arr);

            return $data 
                ? response()->json($data)
                : self::notFound();
        } catch (\Exception $e) {
            throw new HttpResponseException(
                response()->json(
                    'Error updating ' . strtolower($this->modelName) . ': ' . $e->getMessage(),
                    Response::HTTP_BAD_REQUEST
                )
            );
        }

        return response()->json('Updated');
    }

    public function deleteResponse(string $id)
    {
        $data = $this->repository->delete($id);
        return $data 
            ? response()->json(['message' => "$this->modelName with id $id successfully deleted."])
            : self::notFound('DELETE');
    }

    public function notFound(string $method = 'GET')
    {
        $s = $method === 'GET' ? 'getting' : 'deleting';
        $message = 'Error ' . "$s " . strtolower($this->modelName) . ': Not Found';
        return response()->json($message, Response::HTTP_NOT_FOUND);
    }
}
