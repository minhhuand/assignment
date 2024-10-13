<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $email = $request->email;
        $password = $request->password;

        $status = Auth::attempt(['email' => $email, 'password' => $password]);
        if ($status) {
            $token = $request->user()->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'token' => $token,
                'role_id' => $request->user()->role_id,
                'user' => $request->user()->email,
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
        $user = new User();
        $user->fill($request->all());
        $user->role_id = 4;
        $user->save();
        $userId = $user->id;

        return response()->json([
            'success' => true,
            'message' => 'Thêm người dùng thành công',
            'user_id' => $userId,
            'user' => $user,
        ]);
    }


    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();


        Auth::guard('web')->logout();

        return response()->json(['message' => 'Đăng xuất thành công.']);
    }

    public function getUser(Request $request)
    {
        return $request->user();
    }
}
