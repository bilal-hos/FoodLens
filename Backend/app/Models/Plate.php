<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Plate extends Model
{
    use HasFactory;


    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    protected $fillable = [
        'image',
        'total_calories',
        'total_carb',
        'total_fat',
        'total_protine',
        'total_mass',
        'user_id',
        'name',
        ];
        protected $hidden = [ 'updated_at'];

        public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s'); // Adjust format as needed
    }
}

