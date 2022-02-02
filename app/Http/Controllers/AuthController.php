<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
      public function __construct()
      {
            $this->middleware('auth:api', ['except' => ['register', 'login',]]);
      }

      /**
       * First register a new user
       */
      public function register(Request $request)
      {
            $this->validate($request, [
                  'username' => 'required|string|max:255|unique:users',
                  'password' => 'required|string',
                  'password_confirmation' => 'required|string|same:password',
            ]);

            $user = User::create([
                  'username' => $request->username,
                  'password' => Hash::make($request->password),
            ]);

            if ($user) {
                  return response()->json([
                        'status' => 'success',
                        'message' => 'User created successfully',
                  ], 200);
            } else {
                  return response()->json([
                        'status' => 'error',
                        'message' => 'User not created',
                  ], 400);
            }
      }

      /**
       * Get a JWT via given credentials.
       */
      public function login(Request $request)
      {
            $this->validate($request, [
                  'username' => 'required|string|max:255|exists:users',
                  'password' => 'required|string',
            ]);

            $credentials = $request->only(['username', 'password']);

            if (!$token = Auth::attempt($credentials)) {
                  return response()->json([
                        'message' => 'Invalid credentials'
                  ]);
            }

            return $this->respondWithToken($token);
      }

      /**
       * Get the authenticated User.
       */
      public function profile()
      {
            if(auth()->user()) { 
                  return response()->json([
                        'status' => 'success',
                        'data' => auth()->user(),
                  ], 200);
             }else{
                  return response()->json([
                        'message' => 'Unauthorized',
                  ], 400);
             }
      }

      /**
       * Log the user out (Invalidate the token).
       */
      public function logout()
      {
            auth()->logout();
            return response()->json(['message' => 'Successfully logged out']);
      }

      /**
       * Refresh a token.
       */
      public function refresh()
      {
            return $this->respondWithToken(auth()->refresh());
      }

      /**
       * Get the token array structure.
       */
      protected function respondWithToken($token)
      {
            return response()->json([
                  'access_token' => $token,
                  'token_type' => 'bearer',
                  'user' => auth()->user(),
                  'expires_in' => auth()->factory()->getTTL() * 60 * 24
            ]);
      }
}
