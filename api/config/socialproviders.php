<?php

/**
 * Social providers keys from '.env' (Github and Google) to be checked in controller.
 * 
 * Later they can be accessed like '\config('socialproviders.githubId')'.
 */
return [
  'githubId' => env('GITHUB_ID'),
  'githubSecret' => env('GITHUB_SECRET'),
  'googleClientId' => env('GOOGLE_CLIENT_ID'),
  'googleClientSecret' => env('GOOGLE_CLIENT_SECRET'),
];
