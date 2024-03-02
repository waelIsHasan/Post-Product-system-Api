<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{

    public function addFriend($firendId){
        $friendUser = User::find($firendId);
        if($friendUser == null){
            return response()->json(["success" => false ,'msg' => 'there is no firend with this id']);
        } 
        $id=Auth::id();
        $user =User::find($id);
        //add friend
        $user->friends()->attach([$firendId]);
        return response()->json(['success' => true , 'msg' => 'You are Friends']);
    }



    public function removeFriend($firendId){
        $friendUser = User::find($firendId);
        if($friendUser == null){
            return response()->json(["success" => false ,'msg' => 'there is no firend with this id']);
        } 
        $id=Auth::id();
        $user =User::find($id);
        //remove friend
        $user->friends()->detach([$firendId]);
        return response()->json(['success' => true , 'msg' => 'You are not Friends']);
    }


    public function getFriends(){
        $id=Auth::id();
        $user =User::find($id);
        $friends = $user->friends;
        return response()->json(["success"=> true ,'msg' =>"Here is your Frinds" , 'data' => $friends]);
    }

    




}
