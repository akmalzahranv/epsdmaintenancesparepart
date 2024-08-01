<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;

    protected $table = 'shelf';

    protected $primaryKey = 'shelf_id';

    protected $fillable = [
        'shelf_name',
    ];

    /**
     * Get the stocks for the shelf.
     */
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'shelf_id', 'shelf_id');
    }
}
