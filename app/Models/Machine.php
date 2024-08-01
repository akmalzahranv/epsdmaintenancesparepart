<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $table = 'machine';

    protected $primaryKey = 'machine_id';

    protected $fillable = [
        'line_id',
        'machine_name',
    ];
    
    public function line()
    {
        return $this->belongsTo(Line::class, 'line_id', 'line_id');
    }
}
