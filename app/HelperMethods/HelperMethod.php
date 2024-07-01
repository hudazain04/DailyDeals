<?php

namespace App\HelperMethods;

use App\HttpResponse\HTTPResponse;

class HelperMethod
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

    use HTTPResponse;
    public function getErrorResponse($error){
        return $this->error($error->getMessage() , 500);
    }
}
