<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTotal extends Model
{
    use HasFactory;

    public function user ()
    {
        return $this->belongsTo(User::class);
    }
    
    protected $fillable=
    [
    'sub_protein',
    'sub_fat',
    'sub_calories',
    'sub_carb',
    'user_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

}
