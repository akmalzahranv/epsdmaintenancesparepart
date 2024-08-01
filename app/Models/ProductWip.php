<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWip extends Model
{
    use HasFactory;

    protected $table = 'products_wip';

    protected $primaryKey = 'product_wip_id';

    protected $fillable = [
        'user_id',
        'product_code',
        'product_name',
        'problem_details',
        'purchase_price',
        'sale_price',
        'quantity',
        'shelf_id',
        'category_id',
        'line_id',
        'machine_id',
        'request_date',
        'requester',
        'order_date',
        'supplier',
        'estimate_time',
        'arrival_time',
        'installation_planning_schedule',
        'installation_date',
        'status'
    ];

    // Define relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
