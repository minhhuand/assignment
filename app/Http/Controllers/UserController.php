<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{
    // Phương thức index để lấy danh sách người dùng
    public function index()
    {

        $users = User::orderBy('username', 'ASC')->paginate(10);
        return response()->json([
            'data' => $users,
            'success' => true,
        ]);
    }

    public function show(string $id)
    {

        $user = User::findOrFail($id);
        return response()->json([
            'data' => $user,
            'success' => true,
            'message' => 'Get user successfully',
        ]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            $this->authorize('update', $user);
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

            $request->validate($rules, $messages);

            $user->fill($request->all());
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thành công',
                'data' => $user,
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Bạn không có quyền cập nhật người dùng này.',
                'success' => false,
            ], 403);
        }
    }

    public function destroy(string $id, Request $request)
    {
        try {
            $user = User::findOrFail($id);
            $this->authorize('delete', $user);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa người dùng thành công',
                'data' => $request->user(),
                'id' => $id,
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Bạn không có quyền xóa người dùng này.',
                'success' => false,
            ], 403);
        }
    }
}
