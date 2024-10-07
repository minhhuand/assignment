<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $username = $request->username;
        $password = $request->password;

        $status = Auth::attempt(['username' => $username, 'password' => $password]);
        if ($status) {
            $token = $request->user()->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'token' => $token,
                'role_id' => $request->user()->role_id,
                'user' => $request->user()->username,
                'id_user' => $request->user()->id,

            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tên đăng nhập hoặc mật khẩu không đúng',
        ], 401);
    }

    public function register(Request $request)
    {
        try {
            $this->authorize('create', User::class);
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'username' => 'required|unique:users',
                'password' => 'required',
                'email' => 'required|email|unique:users',
            ], [
                'first_name.required' => 'Tên là bắt buộc.',
                'last_name.required' => 'Họ là bắt buộc.',
                'username.required' => 'Tên đăng nhập là bắt buộc.',
                'username.unique' => 'Tên đăng nhập đã được sử dụng.',
                'password.required' => 'Mật khẩu là bắt buộc.',
                'email.required' => 'Email là bắt buộc.',
                'email.email' => 'Email không hợp lệ.',
                'email.unique' => 'Email đã được sử dụng.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Tạo người dùng mới
            $user = new User();
            $user->fill($request->all());
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Thêm người dùng thành công',
                'user' => $user,
            ]);
        } catch (AuthorizationException $e) {
            // Trả về lỗi 403 nếu không có quyền
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền tạo người dùng mới.',
            ], 403);
        } catch (\Exception $e) {
            // Xử lý các lỗi khác, nếu có
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi trong quá trình thêm người dùng.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
