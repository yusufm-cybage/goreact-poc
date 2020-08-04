<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Media Upload Validation 
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */
    
    'MAXSIZE_2MB'   => '2048', 
    'MAXSIZE_5MB'   => '5242', 
    'MAXSIZE_10MB'  => '10240',
    'PATH'          => public_path('/mediafiles'),
];
