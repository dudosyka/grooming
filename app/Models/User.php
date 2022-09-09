<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
        'login',
        'role',
        'api_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static $rules = [
        'login' => 'required|unique:users',
        'email' => 'required|email:rfc|unique:users',
        'fullname' => 'required',
        'password' => 'required',
    ];

    public static function auth($data) {
        $user = self::query()->where('login', '=', $data['login'])->where('password', '=', $data['password'])->get()->all();
        if (count($user)) {
            $user = $user[0];
            $token = hash('sha256', Str::random(32));
            $user->api_token = $token;
            $user->save();
            return [
                'auth' => [
                    'status' => 'success',
                    'data' => $token
                ]
            ];
        } else {
            return [
                'auth' => [
                    'status' => 'failed',
                    'errors' => [ 'incorrect credentials' ]
                ]
            ];
        }
    }

    public static function reg($data) {
        $data['role'] = 'user';
        $data['api_token'] = '';
        try {
            $validator = Validator::validate($data, self::$rules);
            if (!is_array($validator)) {
                $validator->validate();
            }
            self::create($data);
            return [
                'reg' => [
                    'status' => 'success',
                    'data' => ''
                ]
            ];
        } catch (\Exception $err) {
            if (method_exists($err, 'errors')) {
                return [
                    'reg' => [
                        'status' => 'failed',
                        'errors' => $err->errors()
                    ]
                ];
            } else {
                return [
                    'reg' => [
                        'status' => 'failed',
                        'errors' => [ $err->getMessage() ]
                    ]
                ];
            }
        }

    }
}
