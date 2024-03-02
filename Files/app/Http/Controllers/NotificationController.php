<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Page;
use App\Models\User;
use App\Notifications\invitation;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function Invitaions($product_id){
        $id=Auth::id();
        $user =User::find($id);
        $username = $user->name;
        $friends = $user->friends;
        $product = Product::find($product_id);
    
        if($product == null){
            return response()->json(['success' => false ,'msg' => 'there is no post with this id']);
        }
            //notify all my friends with this product
        Notification::send($friends,new Invitation($product, $username,$id));
        return response()->json(['success' => true , 'msg' => 'you send the Invitations successfully']);
    
    }

    public function getUnreadInvitations(){
        $id=Auth::id();
        $user = User::find($id);
        $unreadInvitations = $user->unReadNotifications;
        return response()->json(["success" => true , 'msg' => 'You have recieved them successfully' , 'data' => $unreadInvitations ]); 

    }

    public function markInvitationsAsread(){
        $id=Auth::id();
        $user = User::find($id);
        $user->unReadNotifications->markAsRead();
        return response()->json(["success" => true , 'msg' => 'You have read them successfully']); 

    }


    public function markOneIvAsRead($product_id){
       
        $product = Product::find($product_id);
        
        if($product == null){
            return response()->json(['success' => false,'msg' => 'there is no product with this id']);
        }
       
        $id=Auth::id();
        // search in DB to mark all the notifications this user recieved from his friend
       
        //get all notification's id for this user
        $notificationIds = DB::table('notifications')->where('data->product->id' ,$product_id)->where('notifiable_id' ,$id)->pluck('id');
      
        if(count($notificationIds) == 0){
            return response()->json(['success' => true,'msg' => 'you do not have any notifications']);
            
        }

        //mark them as read
        for($i = 0 ; $i < $notificationIds->count() ;$i++ )
             DB::table('notifications')->where('id', $notificationIds[$i])->update(['read_at' => now()]);        

        return response()->json(['success' => true ,"msg" =>'you have shown the product ']);
    }





}
