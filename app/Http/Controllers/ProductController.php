<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductRequest;
use App\HttpResponse\HttpResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HttpResponse;

    public function addProduct(AddProductRequest $request)
    {

    }
}
