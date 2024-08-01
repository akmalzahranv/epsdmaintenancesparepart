<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    protected $table = 'line';

    protected $primaryKey = 'line_id';

    protected $fillable = [
        'line_name',
    ];
    
    public function products()
    {
        return $this->hasMany(Product::class, 'line_id', 'line_id');
    }
}
