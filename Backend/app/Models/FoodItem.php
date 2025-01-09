<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_name',
        'cal_per_g',
        'fat_per_g',
        'protein_per_g',
        'carb_per_g',
        'components',
        'plate_id',
    ];

    public function FoodItem_plate()
    {
        return $this->belongsTo(FoodItem_plate::class);
    }

    
}
