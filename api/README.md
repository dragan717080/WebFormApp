# <a name="no-link"></a>Laravel Calendar API

API for the Calendar App. Database agnostic but I've used PostgreSQL. All IDs are UUIDv4.

## <a name="no-link"></a>Technologies Used

- **Laravel**

Laravel is a PHP web application framework known for its elegant syntax and developer-friendly features. It follows the MVC pattern and provides powerful tools for building robust web applications.

- **PostgreSQL**

PostgreSQL is a RDBMS known for its reliability, robust feature set, and extensibility. It is highly regarded for its ability to handle complex queries, manage high concurrency, and provide advanced data types and indexing capabilities. 

<img src='https://github.com/dragan717080/Calendar/assets/135660124/56603c60-cbf2-4b42-a536-4177abd0fdde' alt='Database Schema' width='670' height='310' />

## Example endpoint

**Create**

```POST /events```

```
{
    "title": "John and Kelsey's anniversary",
    "description": "John and Kelsey are celebrating their 5th anniversary!",
    "userEmail": "tkihn@example.net",
    "startTime": "2024-03-26 17:30:00",
    "endTime": "2024-03-26 22:00:00"
}
```

Returns

```
201 SUCCESS

{
    "id": "9b9fcd66-0df0-4efd-ac0f-1bd209b7c4d4",
    "user_id": "9b9f9a7e-0d93-44dd-8a4c-fcf3f1ec814e",
    "title": "John and Kelsey's anniversary",
    "description": "John and Kelsey are celebrating their 5th anniversary!",
    "start_time": "2024-03-26 17:30:00",
    "end_time": "2024-03-26 22:00:00",
}
```

**Read**

```GET /events```

Returns

```
200 SUCCESS

[
    {
        "id": "9b9f9a7f-a4d3-4a76-bae5-1c5d2ae80aad",
        "title": "Lisa's Party",
        "description": "Lisa is having a party and her friends and family will be there.",
        "start_time": "2024-03-25 18:00:00",
        "end_time": "2024-03-25 22:00:00",
        "user_id": "9b9f993f-5bfc-4c59-aa9f-e4951ba5ed51"
    },
    {
        "id": "9b9f9a7f-c052-4458-84e9-9ab606b7bff9",
        "title": "Meeting with Peter",
        "description": "Discuss upcoming projects and deadlines with Peter.",
        "start_time": "2024-03-29 09:00:00",
        "end_time": "2024-03-29 12:00:00",
        "user_id": "9b9f9a7d-d87f-4c59-a61c-c3cd895b1a79"
    }
]
```

```GET /events/{id}```
**id: unique identifier in UUIDv4 format**

Returns

```
200 SUCCESS

{
    "id": "9b9f9a7f-a4d3-4a76-bae5-1c5d2ae80aad",
    "title": "Lisa's Party",
    "description": "Lisa is having a party and her friends and family will be there.",
    "start_time": "2024-03-25 18:00:00",
    "end_time": "2024-03-25 22:00:00",
    "user_id": "9b9f993f-5bfc-4c59-aa9f-e4951ba5ed51"
}
```

**Update**

```PATCH /events/{id}```

```
{
    "title": "Get a car from repair",
    "description": "Get car back from repair",
    "startTime": "2024-03-26 16:00:00",
    "endTime": "2024-03-26 16:00:00"
}
```

Returns
```
200 SUCCESS

{
    "id": "9b9f9a7f-a4d3-4a76-bae5-1c5d2ae80aad",
    "title": "Get a car from repair",
    "description": "Get car back from repair",
    "start_time": {
        "date": "2024-03-26 16:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "end_time": {
        "date": "2024-03-26 16:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "user_id": "9b9f993f-5bfc-4c59-aa9f-e4951ba5ed51"
}
```

**Delete**

```DELETE /events/{id}```

Returns

```
200 SUCCESS

{
    "message": "Event with id 9b9f9a7f-a4d3-4a76-bae5-1c5d2ae80aad successfully deleted."
}
```
<img src='https://github.com/dragan717080/Calendar/assets/135660124/1cff3bf6-bccf-40a8-bd85-213bb2d17ac4' alt='Passed Unit Tests' width='670' height='310' />
