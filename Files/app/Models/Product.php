<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'body',
        'price',
        'page_id',
        'discount',
        'discount_start',
        'discount_end',
        'has_discount',
        'sold'
    ];

    public function page(){
        return $this->belongsTo(Page::class);
    }

    public function hasDiscount()
  {
    
    if($this->has_discount){
          $check = Carbon::now()->between($this->discount_start,$this->discount_end);
        if($check != null) {
          return ($this->price - ($this->price * $this->discount/100));
        }
        return $this->price;

    }
}



}
