<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    public function __invoke(Request $request) {
            $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'approved' => false,
        ]);

        if ($user) {
            return response()->json([
                'berhasil' => true,
                'user' => $user,
                'message' => 'Pendaftaran berhasil. Menunggu persetujuan admin.',
            ], 200);
        }

        return response()->json([
            'berhasil' => false,
            'message' => 'Pendaftaran Gagal.'
        ], 409);
    }
}
