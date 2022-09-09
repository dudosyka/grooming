<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public static function createNew($user, $data) {
        if ($user->role != 'admin') {
            abort(403);
        }
        self::create($data);
        return [
            'category_create' => [
                'status' => 'success',
                'data' => ''
            ]
        ];
    }

    public static function getAll() {
        return self::query()->get()->all();
    }

    public static function remove($user, $data) {
        if ($user->role != 'admin') {
            abort(403);
        }
        try {
            self::query()->where('id', '=', $data['id'])->delete();
            return [
                'category_remove' => [
                    'status' => 'success',
                    'data' => ''
                ]
            ];
        } catch (\Exception $err) {
            return [
                'category_remove' => [
                    'status' => 'failed',
                    'errors' => [$err->getMessage()]
                ]
            ];
        }
    }
}
