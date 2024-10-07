<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
class UserController extends Controller
{
   
    public function index()
    {
        try {
            $this->authorize('viewAny', User::class);
            $users = User::orderBy('username', 'ASC')->paginate(10);
            return response()->json([
                'data' => $users,
                'success' => true,
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'message' => 'Bạn không có quyền truy cập vào danh sách người dùng này.',
                'success' => false,
            ], 403);
        }
    }

    
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json([
            'data' => $user,
            'success' => true,
            'message' => 'Get user successfully',
        ]);
    }
    
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

       
        $request->validate($rules, $messages);


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



    public function destroy(string $id, Request $request)
    {
        $admin = $request->user();
        if ($admin->is_admin != 1) {
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
}
