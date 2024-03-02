<?php
use App\Http\Controllers\Autho\AuthController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register' , [AuthController::class ,'register']);
Route::post('/login' , [AuthController::class ,'login']);
Route::post('/logout' ,[AuthController::class , 'logout'])->middleware('auth:api');


Route::middleware('auth:api')->controller(PageController::class)->group(function(){
    Route::get('/showPages' ,'showPages');
    Route::post('/createPage','createPage');
    Route::delete('/delete/{page_id}' , 'deletePage');  
    Route::get('/page/{page_id}/admin/{user_id}' ,'createAdmin');
    Route::get('/countProducts/{page_id}' ,'countProducts');
    Route::get('/countSoldProducts/{page_id}','countSoldProducts');
    Route::get('page/{page_id}/banUser/{banUser}','banUser');
    Route::get('page/{page_id}/unBanUser/{banUser}','unBanUser');

});

Route::middleware('auth:api')->controller(ProductController::class)->group(function(){
    Route::post('/createProduct','createProduct');
    Route::get('/getProducts/{page_id}','getProductsOfPage');
    Route::delete('/product/delete/{product_id}' , 'deleteProduct');  
    Route::get('/page/{page_id}/getproduct/{product_id}','getOneProductOfPage');
    Route::get('buyProduct/{product_id}' ,'buyProduct');
});

Route::middleware('auth:api')->controller(FriendshipController::class)->group(function(){
    Route::get('friendship/getFriends','getFriends');
    Route::post('friendship/add/{friend_id}','addFriend');
    Route::delete('friendship/remove/{friend_id}' , 'removeFriend');      
});


Route::middleware('auth:api')->controller(NotificationController::class)->group(function(){
    Route::get('/notifications/invitaions/{product_id}','Invitaions');
    Route::get('/unReadNotifications/invitaions','getUnreadInvitations');
    Route::get('/markInvitationsAsread','markInvitationsAsread');
    Route::get('/markOneIvAsRead/{product_id}','markOneIvAsRead');
    
});

    
