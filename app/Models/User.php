<?php

namespace App\Models;

use App\Partials\File\HasFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use League\OAuth2\Server\Exception\OAuthServerException;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasFile;

    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function findAndValidateForPassport($username, $password)
    {
        if ($user = $this->where('username', $username)->first()) {
            if (!Hash::check($password, $user->password)) {
                throw new OAuthServerException(message: 'password_incorrect', code: 400, errorType: 'oauth', httpStatusCode: 400);
            }
            return $user;
        } else throw new OAuthServerException(message: 'user_not_found', code: 404, errorType: 'oauth', httpStatusCode: 404);
    }
}
