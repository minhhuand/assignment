<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    public function index()
    {

        $users = User::orderBy('username', 'DESC')->paginate(10);
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

        $user = User::findOrFail($id);


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
