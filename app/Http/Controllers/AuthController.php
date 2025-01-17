<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    
    public function login(LoginRequest $request): JsonResponse {
        try {
            
            $validated = $request->validated();
            
            $user = User::where('email', $validated['email'])->first();


            //Check if user exists and password matches
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                    'data' => [],
                ], 401);
            }

            $token = $user->createToken($user->name.'-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successfully',
                'data' => [
                    'email' => $user->email,
                    'token' => $token
                ],
            ], 200);


        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function currentUser() {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'message' => 'Get info user successfully',
            'data' => $user
        ],200);
    }

}
