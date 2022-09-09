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

    public function request() {
        return $this->hasOne(RequestModel::class, 'id', 'request_id');
    }

    public static function getByUser($user) {
        return self::query()->where('user_id', '=', $user)->with('request')->get()->all();
    }

    public static function getByUserStatusFilter($user, $status) {
        $data = self::getByUser($user);
        $res = [];
        foreach ($data as $item) {
            if ($item->request->status == $status) {
                $res[] = $item;
            }
        }
        return $res;
    }
}
