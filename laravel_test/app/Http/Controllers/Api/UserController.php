<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\User;

class UserController extends Controller
{
    public function getAllUser()
    {
        // All Users
        $users = User::all();

        // Return Json Response
        return response()->json([
            'users' => $users
        ],200);
    }

    public function createUser(Request $request)
    {
        $this->validate($request,[
            'firstname' => 'required|string|max:60',
            'lastname' => 'required|string|max:60',
            'email' => 'required|required|unique:users',
            'password' => 'required|string|min:6',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'mobile_number' => 'required|numeric',
            'status' => 'required|string'
        ]);

        $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
        $request->file('image')->move(
            base_path() .'/public/uploads/',$imageName
        );
        $user = new User([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $imageName,
            'mobile_number' => $request->mobile_number,
            'status' => $request->status
        ]);

        $user->save();
        return response()->json(['message' => 'User created successfully'],200);
    }

    public function getUser($id)
    {
        // User Detail 
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message'=>'User Not Found.'
            ],404);
        }

        // Return Json Response
        return response()->json([
            'user' => $user
        ],200);
    }

    public function updateUser(Request $request, $id)
    {
        try {
            // Find User
            $user = User::find($id);
            if(!$user)
            {
                return response()->json([
                    'message'=>'User Not Found.'
                ],404);
            }
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->mobile_number = $request->mobile_number;
            $user->status = $request->status;

            if($request->image) 
            {
                //Image name
                $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();
                $user->image = $imageName;
    
                $request->file('image')->move(
                    base_path() .'/public/uploads/',$imageName
                );
            }
            
            $user->save();
            // Return Json Response
            return response()->json([
                'message' => "User successfully updated."
            ],200);
        } 
        catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);
        }
    }

    public function deleteUser($id)
    {
        // User Detail 
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message'=>'User Not Found.'
            ],404);
        }

        // Delete User
        $user->delete();

        // Return Json Response
        return response()->json([
            'message' => "User successfully deleted."
        ],200);
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $credentials = request(['email','password']);

        if (!auth()->attempt($credentials)) {
            return response(['message' => 'Unauthorized'],401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        return response()->json(['data' => [
            'user' => Auth::user(),
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString() 
        ]]);
    }
}