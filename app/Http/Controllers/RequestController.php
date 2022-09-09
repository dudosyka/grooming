<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestController extends Controller
{
    public function create(Request $request) {
        if ($request->hasFile('photo_before')) {
            $file = $request->file('photo_before');
            $ext = explode(".", $file->getClientOriginalName())[count(explode(".", $file->getClientOriginalName())) - 1];
            $new_name = Str::random(32).".".$ext;
            $file->move(public_path() . '/images', $new_name);
            $data = $request->all();
            $data['photo_before'] = $new_name;
            return response()->json(RequestModel::createNew($data, $request->user()));
        } else {
            return response()->json([
                'request_create' => [
                    'status' => 'failed',
                    'errors' => ['photo_before' => 'required']
                ]
            ]);
        }
    }

    public function remove(Request $request) {
        return response()->json(RequestModel::remove($request->all(), $request->user()));
    }

    public function updateStatus(Request $request) {
        $data = $request->all();
        if ($data['status'] == 'done') {
            if ($request->hasFile('photo_after')) {
                $file = $request->file('photo_after');
                $ext = explode(".", $file->getClientOriginalName())[count(explode(".", $file->getClientOriginalName())) - 1];
                $new_name = Str::random(32).".".$ext;
                $file->move(public_path() . '/images', $new_name);
                $data['photo_after'] = $new_name;
                return response()->json(RequestModel::updateStatus($data, $request->user()));
            }
            else {
                return response()->json([
                    'request_update' => [
                        'status' => 'failed',
                        'errors' => ['photo_after' => 'required']
                    ]
                ]);
            }
        } else {
            return response()->json(RequestModel::updateStatus($data, $request->user()));
        }
    }

    public function getAll() {
        return response()->json([
            'requests' => [
                'status' => 'success',
                'data' => RequestModel::getAll()
            ]
        ]);
    }

    public function getOne(Request $request) {
        return response()->json([
            'request' => [
                'status' => 'success',
                'data' => RequestModel::getOne($request->all())
            ]
        ]);
    }


    public function getByCategory(Request $request) {
        return response()->json([
            'request_by_category' => [
                'status' => 'success',
                'data' => RequestModel::getByCategory($request->all())
            ]
        ]);
    }

    public function getLastDone() {
        return response()->json([
            'request_last' => [
                'status' => 'success',
                'data' => RequestModel::getLastDone()
            ]
        ]);
    }

    public function getCounter() {
        return response()->json([
            'request_counter' => [
                'status' => 'success',
                'data' => RequestModel::getDoneCount()
            ]
        ]);
    }
}
