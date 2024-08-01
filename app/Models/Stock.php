<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';

    protected $primaryKey = 'stock_id';

    protected $fillable = [
        'user_id',
        'username',
        'shelf_id',
        'product_id',
        'product_amount',
        'installation_date',
        'type',
    ];

    /**
     * Get the user that owns the stock.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the shelf that owns the stock.
     */
    public function shelf()
    {
        return $this->belongsTo(Shelf::class, 'shelf_id', 'shelf_id');
    }

    /**
     * Get the product that owns the stock.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
