<?php

namespace App\Http\Controllers;

use App\HttpResponse\HttpResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    use HttpResponse;
    public function SearchOnBranch($branch_id)
    {
        try
        {

        }
        catch (\Throwable $th)
        {
            return $this->error($th->getMessage(),500);
        }
    }
}
