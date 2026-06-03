<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Entities\Position;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Position::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $limit = $request->get('limit', 15);
        $positions = $query->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $positions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:225|unique:positions,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $position = Position::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo chức vụ thành công',
            'data' => $position
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $position = Position::with('users')->find($id);

        if (!$position) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy chức vụ'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $position
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy chức vụ'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:225|unique:positions,name,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $position->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật chức vụ thành công',
            'data' => $position
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy chức vụ'
            ], 404);
        }

        $position->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa chức vụ thành công'
        ]);
    }
}
