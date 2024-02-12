<?php

namespace App\Http\Controllers\Api;

use Rules\Password;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (empty($credentials['email']) || empty($credentials['password'])) {
            return response()->json([
                'message' => 'email and password are required',
            ], 400);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'credentials do not match our records.',
            ], 401);
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'success login',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $user,
            'role' => \Auth::User()->roles()->first()->name
        ], 200);
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'nip' => ['required', 'min:8','max:8'],
                'email' => 'required',
                'password' => 'required',
            ]);
            $sales = User::create([
                'name' => $request->name,
                'nip' => $request->nip,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($sales));
            $sales->assignRole('sales');


            return response()->json([
                'message' => 'success register',
                'data' => $sales
            ], 200);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }

    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'success logout'
        ]);
    }
}

