<?php

namespace App\HttpResponse;

trait HttpResponse
{
    public function success($data,$message,$code=200)
    {
        return response(['data'=>$data,'message'=>$message],$code);
    }
    public function error($message,$code)
    {
        return response(['message'=>$message],$code);
    }
    public function serverError()
    {
        return response(['message'=>"server error"],500);
    }
}
