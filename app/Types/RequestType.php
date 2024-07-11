<?php

namespace App\Types;

class RequestType
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public const Pending='Pending';
    public const Accepted='Accepted';
    public const Rejected='Rejected';
    public const Shown='Shown';
}
