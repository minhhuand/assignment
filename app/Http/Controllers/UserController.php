<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('username', 'ASC')->paginate(10);
        return response()->json([
            'data' => $users,
            'success' => true,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json([
            'data' => $user,
            'success' => true,
            'message' => 'Get user successfully',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = $request->user();
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        $messages = [
            'first_name.required' => 'Họ tên là bắt buộc.',
            'last_name.required' => 'Tên là bắt buộc.',
            'username.required' => 'Tên đăng nhập là bắt buộc.',
            'username.unique' => 'Tên đăng nhập này đã được sử dụng.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
        ];

        // Thực hiện xác thực với các thông điệp lỗi tùy chỉnh
        $request->validate($rules, $messages);

        // Kiểm tra quyền truy cập
        if ($admin->is_admin != 1 && $admin->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền',
            ], 403);
        }

        $user = User::find($id);
        $user->fill($request->all());
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành công',
            'data' => $user,
        ]);
    }





    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $admin = $request->user();
        if ($admin->is_admin != 1) {
            //Chỉ được phép xóa user chính mình
            if ($admin->id != $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden',
                ], 403);
            }
        }
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Delete user successfully',
            'data' => $request->user(),
            'id' => $id
        ]);
    }

    public function orders(Request $request)
    {
        $user = $request->user();
        $user->orders = $user->orders()->with('details')->select('id', 'total')->orderBy('total', 'DESC')->get();
        return response()->json([
            'data' => $user,
            'success' => true,
            'message' => 'Get order successfully',
        ]);
    }
}
