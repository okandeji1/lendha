<?php

namespace App\Http\Controllers;

use App\User;
use Ramsey\Uuid\Uuid;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Login method
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->createToken('catalog')->accessToken;
            
            $response = array(
                "status" => "success",
                "message" => "You are successfully logged in",
            );
            return response()->json($response);
        } else {
            $response = array(
                "status" => "error",
                "message" => "Invalid email or password",
            );
            return response()->json($response);
        }
    }

    /**
     * Register method
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'cpassword' => 'required|same:password',
        ]);

        $data = $request->only(['firstname', 'lastname', 'email', 'password']);
        $data['password'] = bcrypt($data['password']);
        
        // Check if user already exit
        if(User::where('email', '=', $data['email'])->exists()){
            $response = array(
                "status" => "error",
                "message" => "Email already exist",
            );
            return response()->json($response);
        }else {
            $user = new User();
            $user->uuid = Uuid::uuid4();
            $user->firstname = $data['firstname'];
            $user->lastname = $data['lastname'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->save();
            // Create access token
            $user->createToken('catalog')->accessToken;
            // Redirect user
            $response = array(
                "status" => "success",
                "message" => "User successfully created",
            );
            return response()->json($response);
        }
    }

    public function logout(Request $request)
    {
        $value = $request->bearerToken();
        if ($value) {
            $id = (new Parser())->parse($value)->getHeader('jti');
            User::table('oauth_access_tokens')->where('id', '=', $id)->update(['revoked' => 1]);
            $this->guard()->logout();
        }
        Auth::logout();
        $response = array(
            "status" => "success",
            "message" => "User successfully created",
        );
        return response()->json($response);
    }
}
