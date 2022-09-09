<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_id'
    ];

    public static function checkOwner($user, $request) {
        return self::query()->where('user_id', '=', $user)->where('request_id', '=', $request)->get()->all() > 0;
    }
}
