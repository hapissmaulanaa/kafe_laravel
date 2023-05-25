<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class user extends Authenticatable implements JWTSubject
// {
//     use HasFactory;
//     protected $table = 'user';
//     protected $primaryKey = 'id_user';
//     public $timestamps = false;
//     protected $fillable = ['nama_user', 'role', 'username', 'password'];
// }
{
    use Notifiable;
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
    'nama_user', 'username', 'role', 'password',
    ];
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
    'password', 'remember_token',
    ];
    public function getJWTIdentifier()
    {
    return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
    return [];
    }
    
   }
