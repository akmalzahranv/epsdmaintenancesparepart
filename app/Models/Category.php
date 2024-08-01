<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
    ];

    // If you have any relationships, define them here
    // For example, if a category has many products:
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
