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

use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function createProduct(Request $request){

 try{
            $vaildator = Validator::make($request->all(),[
                'name'=>'required',
                'body'=>'required',
                'price'=>'required',
                'page_id'=>'required',
                
                
            ]);
            
            if($vaildator ->fails()){
                return response()->json(["success" => false ,"msg" => "vaildator Error !" , "errors" => $vaildator->errors()]);
            }
            //chech if the user is admin to create a product
            $page = Page::find($request->page_id);
            $curUserId = Auth::id();
            $exactUserId = $page->user_id;
            $pivotData = $page->admins()->where('user_id', $curUserId)->first();
            if($curUserId != $exactUserId && $pivotData == null){
                return response()->json(["success"=> false,"msg"=>"You do not have permisson for creating admin for this page"]);
             }

            $product = Product::create([
                'page_id' => $request->page_id,
                'name' => $request->name,
                'body' => $request->body,
                'price' => $request->price,
                'discount'=>$request->discount,
                'discount_start'=>$request->discount_start,
                'discount_end' => $request->discount_end,
                'has_discount' =>$request->has_discount,
                'sold' => $request->sold
            ]);
            return response()->json(["success" => true , "msg" => "You have created a new prodcut" ,'prodcut' => $product ,'pivat' => $pivotData]);
            
        }catch(Exception $e){
            return response()->json(["success" => false,"msg"=> $e->__toString()]);
        }
    }

    public function getProductsOfPage($pageId){
       
        $page = Page::find($pageId);
        if($page == null){
            return response()->json(["success" => false , "msg" => "You have not had Products yet !"]);
        }
        
        $curUserId = Auth::id();
      //if this user can access to this page 
        $pivotData = $page->forbidden()->where('user_id', $curUserId)->first();
        if($pivotData != null){
            
            return response()->json(["success" => false , "msg" => "You can not access to this page "]);

        }

       
        $productsOfPage = $page->products;
      
        

        return response()->json(["success" => true , "msg" => "It has done successfully" , "data" => $productsOfPage]);
        
    }

    
    public function getOneProductOfPage($pageId , $productId){
       
        $page = Page::find($pageId);
        //chech if the page is existed
        if($page == null){
            return response()->json(["success" => false , "msg" => "You do not have page with this id !"]);
        }
        
        $curUserId = Auth::id();
         //if this user can access to this page 
         $pivotData = $page->forbidden()->where('user_id', $curUserId)->first();
         if($pivotData != null){
             
             return response()->json(["success" => false , "msg" => "You can not access to this page "]);
 
         }


        
        $product = Product::find($productId);
        //chech if the product is existed
        if($product == null){
            return response()->json(["success" => false , "msg" => "You do not have product with this id  !"]);
        }
        $curpageId = $product->page_id;
        // chech if the product in the same page
        if($curpageId != $pageId){
            return response()->json(["success" => false , "msg" => "You do not have this product in this page  !"]);
        }
        $product['price_with_discount'] = $product->hasDiscount();
        return response()->json(["success" => true , "msg" => "You recieve the product" , 'data' => $product  ,]);        
    }


    
    public function deleteProduct($id){
        $product = Product::find($id);
        if($product == null){
            return response()->json(["success" => false,"msg"=> 'you do not have this id page ']);
        }
        
        $page_id= $product->page_id;
        $page = Page::find($page_id);
        $curUserId = Auth::id();
        $exactUserId = $page->user_id;

        // to know if the same user create the product who is the same need to delete
        if($curUserId != $exactUserId){
            return response()->json(["success" => false , "msg" => "You do not have permisson to delete this product"]);
        }

        $product->delete();
        return response()->json(["success" => true,"msg"=>"It has deleted successfully"]);
    }
    public function buyProduct($id){
        $product = Product::find($id);
        if($product == null){
            return response()->json(["success" => false,"msg"=> 'you do not have this id page ']);
        }
        $product->update([
            'sold'=>true,
        ]);
        return response()->json(["success" => true,"msg"=> 'you have bought the product successfully']);
 
    }

    
}
