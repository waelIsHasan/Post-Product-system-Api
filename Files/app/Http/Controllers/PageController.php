<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Arr;

class PageController extends Controller
{
    public function showPages(){
        try{
            $user = Auth::user();
            $pagesUsers = $user->pages;
            if($pagesUsers == null){
                return response()->json(["success" => true,"msg" => 'there are no pages']);
            }
            return response()->json(["success" => true, "msg" => 'there are your pages' , "data" => $pagesUsers ]);
        }catch(Exception $e){
            return response()->json(["success" => false,"msg"=> $e]);
        }
    }

    public function createPage(Request $request){
        try{
            $vaildator = Validator::make($request->all(),[
                'title'=>'required',
                'description'=>'required',
                'category_id'=>'required',
            ]);
            
            if($vaildator ->fails()){
                return response()->json(["success" => false ,"msg" => "vaildator Error !" , "data" => $vaildator->errors()]);
            }
            $page = Page::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id
            ]);
            return response()->json(["success" => true , "msg" => "You have created a new page" ,'data' => $page]);
            
        }catch(Exception $e){
            return response()->json(["success" => false,"msg"=> $e]);
        }
    }
    
    

    public function deletePage($id){
        $page = Page::find($id);

        if($page == null){
            return response()->json(["success" => false,"msg"=> 'you do not have this id page ']);
        } 
        $curUserId = Auth::id();
        $exactUserId = $page->user_id;
        if($curUserId != $exactUserId){
            return response()->json(["success"=> false,"msg"=>"You do not have permisson for deleting this page"]);
         }

        $page->delete();
        return response()->json(["msg"=>"It has deleted successfully"]);
    }
    
    public function createAdmin($pageId , $userId){
        $page = Page::find($pageId);
        if($page == null){
            return response()->json(["success" => false,"msg"=> 'you do not have this id page ']);
        } 
        //chech if the same user that create page the same who needs to create admin 
        $curUserId = Auth::id();
        $exactUserId = $page->user_id;
        if($curUserId != $exactUserId){
            return response()->json(["success"=> false,"msg"=>"You do not have permisson for creating admin for this page"]);
         }

         $admin = User::find($userId);
         if($admin == null){
             return response()->json(["success" => false ,'msg' => 'there is no user with this id in this system']);
         }

         $page->admins()->attach([$userId]);
         return response()->json(["success" => true ,'msg' => 'he become an admin']);
    }

    public function countProducts($pageId){
        $page = Page::find($pageId);
        if($page == null){
            return response()->json(["success" => false,"msg"=> 'you do not have this id page ']);
        } 
        //chech if the same user that create page the same who needs to create admin 
        $curUserId = Auth::id();
        $exactUserId = $page->user_id;
        if($curUserId != $exactUserId){
            return response()->json(["success"=> false,"msg"=>"You do not have permisson for creating admin for this page"]);
         }

         $products = $page->products;

         return response()->json(["success"=> true,"msg"=>"you have {$products->count()} products"]);

    }


    public function countSoldProducts($pageId){
        $page = Page::find($pageId);
        if($page == null){
            return response()->json(["success" => false,"msg"=> 'you do not have this id page ']);
        } 
        //chech if the same user that create page the same who needs to create admin 
        $curUserId = Auth::id();
        $exactUserId = $page->user_id;
        if($curUserId != $exactUserId){
            return response()->json(["success"=> false,"msg"=>"You do not have permisson for creating admin for this page"]);
         }

         $products = $page->products;
         $filter= array();
         foreach($products as $product ){
            if($product->sold == 1){
                array_push($filter , $product->id);
            }


         }
         $count = count($filter);
         
         return response()->json(["success"=> true,"msg"=>"you have $count sold products" ,'data' => $filter]);

    }

    
    public function banUser($pageId , $userId){
        $page = Page::find($pageId);
        if($page == null){
            return response()->json(["success" => false,"msg"=> 'you do not have this id page ']);
        } 
        //chech if the same user that create page the same who needs to create admin 
        $curUserId = Auth::id();
        $exactUserId = $page->user_id;
        if($curUserId != $exactUserId){
            return response()->json(["success"=> false,"msg"=>"You do not have permisson for creating admin for this page"]);
         }

         $forbiddenUser = User::find($userId);
         if($forbiddenUser == null){
             return response()->json(["success" => false ,'msg' => 'there is no user with this id in this system']);
         }

         $page->forbidden()->attach([$userId]);
         return response()->json(["success" => true ,'msg' => 'he banned form this page']);
    }

    public function unBanUser($pageId , $userId){
        $page = Page::find($pageId);
        if($page == null){
            return response()->json(["success" => false,"msg"=> 'you do not have this id page ']);
        } 
        //chech if the same user that create page the same who needs to create admin 
        $curUserId = Auth::id();
        $exactUserId = $page->user_id;
        if($curUserId != $exactUserId){
            return response()->json(["success"=> false,"msg"=>"You do not have permisson for creating admin for this page"]);
         }

         $forbiddenUser = User::find($userId);
         if($forbiddenUser == null){
             return response()->json(["success" => false ,'msg' => 'there is no user with this id in this system']);
         }

         $page->forbidden()->detach([$userId]);
         return response()->json(["success" => true ,'msg' => 'he unbanned form this page']);
    }

}
