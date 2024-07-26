<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use App\HttpResponse\HttpResponse;
use App\Models\Favorite;
use Illuminate\Http\Request;


class FavoriteController extends Controller
{
    use HttpResponse;
    
    public function list_favorite()
    {
        $favorites = Favorite::where('customer_id',auth()->user()->id)->get();
        return $this->success(FavoriteResource::collection($favorites) ,'list favorites');

    }
    
    public function add_favorite(Request $request)
    {
        $user = auth()->user();
        
        $favorite = Favorite::where('customer_id', $user->id)->where('branch_id', $request->branch_id)->first();
        if ($favorite) {
        return $this->error('Branch already in favorites',400);
        } else {
        $favorite = Favorite::create([
            'customer_id' => $user->id,
            'branch_id' => $request->branch_id,
        ]);

        return $this->success(new FavoriteResource($favorite) ,'Branch added to favorites');
        }

    }

}