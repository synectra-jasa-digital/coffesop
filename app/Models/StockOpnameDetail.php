<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_opname_id', 'ingredient_id', 'system_stock', 
        'actual_stock', 'difference', 'notes'
    ];

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
