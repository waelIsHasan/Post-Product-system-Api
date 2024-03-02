<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

protected  $fillable= [
        'title',
        'description',
        'category_id',
        'user_id'
    ];
    public function products(){
        return $this->hasMany(Product::class);
    }
    
    public function category(){
        return $this->belongsTo(Page::class);
    }

    public function user(){
       return $this->belongsTo(User::class );
    }

    public function admins(){
        return $this->belongsToMany(User::class,'page_user','page_id','user_id');
    }

    public function forbidden(){
        return $this->belongsToMany(User::class,'page_forbidden','page_id','user_id');
    }
}
