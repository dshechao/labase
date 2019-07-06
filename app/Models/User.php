<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 * @method static object role($role)
 * @method $this orWhere(string $string,$username)
 * @method first($colum = '')
 * @method value(string $string)
 * @method string exists()
 * @method  static $this where(string $string,$phone)
 * @method static create(array $array)
 *
 * @property mixed password
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable,HasApiTokens,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'name',
            'email',
            'phone',
            'password',
        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden
        = [
            'password',
            'remember_token',
            'email_verified_at',
            'created_at',
            'updated_at',
        ];

    /**
     * @param $password
     *
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return $password == $this -> password || Hash ::check($password,$this -> password);
    }

    /**
     * @param $username
     *
     * @return mixed
     */
    public function findForPassport($username)
    {
        return $this -> orWhere('email',$username) -> orWhere('phone',$username) -> first();
    }
}
