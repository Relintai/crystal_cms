<?php

namespace App\Models;

use DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function generateSessionId() {
        $users = DB::table('users')->get();

        $sid;
        while(true) {
            $sid = md5(time() + rand(1, 50000));

            $found = false;

            foreach ($users as $u) {
                if ($u->sessionid == $sid) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                break;
            }
        }

        $this->sessionid = $sid;
        return $sid;
    }

    public static function obfuscatePassword($pw) {
        return $pw;
    }
}
