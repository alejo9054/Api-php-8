<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const USUARIO_VERIFICADO = '1';
    const USUSARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    protected $table = 'users';//esta linea agrega a los modelos extendidos la tabla users para ellos.
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * Mutators to make sure that database is filled ok
     */
    //set all string names as  lower case  
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);// we acces to the atributes list from this model and modify it
    }
    //get this atribute and show it better without modify it in data base
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }
    //set mutator to email
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }





    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function esVerificado()
    {
        return $this->admin == User::USUARIO_VERIFICADO;
    }

    public static function generarVerificationToken()
    {
        return Str::random(40);
    }

    public function esAdministrador()
    {
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }
}
