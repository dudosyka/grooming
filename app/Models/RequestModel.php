<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class RequestModel extends Model
{
    use HasFactory;

    public $table = "requests";

    protected $fillable = [
        'pet_name',
        'description',
        'category_id',
        'photo_before',
        'photo_after',
        'status',
        'comment'
    ];

    public static $rules = [
        'pet_name' => 'required',
        'description' => 'required',
        'category_id' => 'required',
        'photo_before' => 'required',
    ];

    public static function createNew($data, $user) {
        try {
            $validator = Validator::validate($data, self::$rules);
            if (!is_array($validator)) {
                $validator->validate();
            }

            $data['status'] = 'new';
            $data['comment'] = "";
            $data['photo_after'] = "";
            $request = self::create($data);
            UserRequest::create([
                "user_id" => $user->id,
                "request_id" => $request->id
            ]);
            return [
                'request_create' => [
                    'status' => 'success',
                    'data' => ''
                ]
            ];
        } catch (\Exception $err) {
            if (method_exists($err, 'errors')) {
                return [
                    'request_create' => [
                        'status' => 'failed',
                        'errors' => $err->errors()
                    ]
                ];
            } else {
                return [
                    'request_create' => [
                        'status' => 'failed',
                        'errors' => [ $err->getMessage() ]
                    ]
                ];
            }
        }
    }

    public static function remove($data, $user) {
        $request = self::query()->where('id', '=', $data['id'])->get()->all();
        if (count($request) <= 0) {
            return [
                'request_remove' => [
                    'status' => 'failed',
                    'errors' => ['not_found']
                ]
            ];
        }
        $request = $request[0];
        if (!UserRequest::checkOwner($user->id, $request->id)) {
            return [
                'request_remove' => [
                    'status' => 'failed',
                    'errors' => ['forbidden']
                ]
            ];
        }
        if ($request->status != "new") {
            return [
                'request_remove' => [
                    'status' => 'failed',
                    'errors' => ['status_failed']
                ]
            ];
        }
        $request->delete();
        return [
            'request_remove' => [
                'status' => 'success',
                'data' => ''
            ]
        ];
    }


    public static function updateStatus(array $data, $user) {
        if ($user->role != "admin") {
            return [
                'request_update' => [
                    'status' => 'failed',
                    'errors' => ['forbidden']
                ]
            ];
        }
        $request = self::query()->where('id', '=', $data['id'])->get()->all();
        if (count($request) <= 0) {
            return [
                'request_update' => [
                    'status' => 'failed',
                    'errors' => ['not_found']
                ]
            ];
        }
        $request = $request[0];
        if ($request->status != 'new') {
            return [
                'request_update' => [
                    'status' => 'failed',
                    'errors' => ['status_failed']
                ]
            ];
        }
        if ($data['status'] == 'proccessing') {
            if (array_key_exists('comment', $data)) {
                $request->update($data);
                return [
                    'request_update' => [
                        'status' => 'success',
                        'data' => ''
                    ]
                ];
            } else {
                return [
                    'request_update' => [
                        'status' => 'failed',
                        'errors' => ['comment' => 'required']
                    ]
                ];
            }
        }
        else if ($data['status'] == 'done') {
            if (array_key_exists('photo_after', $data)) {
                $request->update($data);
                return [
                    'request_update' => [
                        'status' => 'success',
                        'data' => ''
                    ]
                ];
            } else {
                return [
                    'request_update' => [
                        'status' => 'failed',
                        'errors' => ['photo_after' => 'required']
                    ]
                ];
            }
        }
        else {
            return [
                'request_update' => [
                    'status' => 'failed',
                    'errors' => ['bad_request']
                ]
            ];
        }
    }

    public static function getAll() {
        return self::query()->orderBy('updated_at', 'desc')->get()->all();
    }

    public static function getByCategory($data) {
        return self::query()->where('category_id', '=', $data['cid'])->orderBy('updated_at', 'desc')->get()->all();
    }

    public static function getOne($data) {
        return self::query()->where('id', '=', $data['id'])->get()->all();
    }

    public static function getLastDone() {
        return self::query()->limit(4)->where('status', '=', "done")->orderBy('updated_at', 'desc')->get()->all();
    }

    public static function getDoneCount() {
        return count(self::query()->where('status', '=', "done")->get()->all());
    }
}
