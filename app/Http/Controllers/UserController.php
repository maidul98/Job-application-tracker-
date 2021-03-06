<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use JWTAuthException;
class UserController extends Controller
{
    private function getToken($email, $password)
    {
        $token = null;
        //$credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt( ['email'=>$email, 'password'=>$password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Password or email is invalid',
                    'token'=>$token
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'Token creation failed',
            ]);
        }
        return $token;
    }

    /**
     * Logout user 
     */
     public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
         return response()->json([
            'response' => 'success',
            'message' => 'Token has been invalidated',
        ]);
     }


    /**
     * Log the user in and get auth token 
     */
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|bail',
            'password' => 'required',
        ]);

        $user = \App\User::where('email', $request->email)->get()->first();
        if ($user && \Hash::check($request->password, $user->password)) // The passwords match...
        {
            $token = self::getToken($request->email, $request->password);
            $user->auth_token = $token;
            $user->save();
            $response = ['success'=>true, 'data'=>['id'=>$user->id,'auth_token'=>$user->auth_token,'first_name'=>$user->first_name,'last_name'=>$user->last_name,  'email'=>$user->email]];           
        }
        else 
          $response = ['success'=>false, 'data'=>'Record doesnt exists'];
      
        return response()->json($response, 201);
    }



    /**
     *Rgeisters the user into app 
     */
    public function register(Request $request)
    { 
        $validatedData = $request->validate([
            'email' => 'required|unique:users|email|bail',
            'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required'
        ]);

        $payload = [
            'password'=>\Hash::make($request->password),
            'email'=>$request->email,
            'name'=>$request->name,
            'auth_token'=> '',
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name
            
        ];
                  
        $user = new \App\User($payload);
        if ($user->save())
        {
            
            $token = self::getToken($request->email, $request->password); // generate user token
            
            if (!is_string($token))  return response()->json(['success'=>false,'data'=>'Token generation failed'], 201);
            
            $user = \App\User::where('email', $request->email)->get()->first();
            
            $user->auth_token = $token; // update user token
            
            $user->save();
            
            $response = ['success'=>true, 'data'=>['first_name'=>$user->first_name,'last_name'=>$user->last_name,'id'=>$user->id,'email'=>$request->email,'auth_token'=>$token]];        
        }
        else
            $response = ['success'=>false, 'data'=>'Couldnt register user'];
        
        
        return response()->json($response, 201);
    }
}
