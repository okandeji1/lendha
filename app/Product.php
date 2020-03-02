<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'uuid', 'category_id', 'price', 'image', 'discription'
    ];
    
    // Category model
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
