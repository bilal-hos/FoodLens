<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable; 

class User extends Model implements Authenticatable{

    use HasApiTokens, Notifiable;
    use HasFactory;

    
    protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'age',
    'phone_number',
    'gender',
    'weight',
    'height',
    'address',
    'password',
    'needed_calories'
    ];
    protected $hidden = ['created_at', 'updated_at'];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
public function getAuthIdentifierName() { return 'id'; } 
public function getAuthIdentifier() { return $this->id; } 
public function getAuthPassword() { return $this->password; } 
public function getRememberToken() {} 
public function setRememberToken($value) {} 
public function getRememberTokenName() {}
    
    public function Plates()
    {
        return $this->hasMany(Plate::class);
    }
    public function subtotals()
    {
        return $this->hasMany(SubTotal::class);
    }
}
