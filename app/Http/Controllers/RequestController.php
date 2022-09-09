<?php

namespace App\Http\Controllers;

use App\Models\RequestModel;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function create(Request $request) {
        return response()->json(RequestModel::createNew($request->all(), $request->user()));
    }

    public function remove(Request $request) {
        return response()->json(RequestModel::remove($request->all(), $request->user()));
    }

    public function updateStatus(Request $request) {
        return response()->json(RequestModel::updateStatus($request->all(), $request->user()));
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
