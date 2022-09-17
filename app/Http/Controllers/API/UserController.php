<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Rules\Password;
use PDO;

class  UserController extends Controller
{
    use PasswordValidationRules;
    public function login(Request $request)
    {
        try{ 
            $request->validate([
                'email' => 'email|required',
                'password' => 'required',
            ]);
            // mengecek credential(login)
            if (!Auth::attempt($request->only('email', 'password'))) {
                return $this->senderror([
                    'message' => 'Unauthorized'
                ],'Autentication failed', 500);
            }
            //jika berhasil maka loginkan

            $user = User::where('email', $request->email)->first();
            if(!Hash::check($request->password, $user->password,[])){
                throw new \Exception('Invalid Credentials');
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return $this->sendsuccess([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'Authenticated');
        } catch (Exception $error){
            return $this->senderror([
                'message' => 'Something went wrong',
                'error' => $error
            ],'Authentication failed', 500);
        }
    }


    public function register(Request $request){

        try{
            $request->validate([
                'name' => ['required','string', 'max:255'],
                'username' => ['required','string','unique:users','max:255'],
                'email' =>[ 'required','email','unique:users','max:255'],
                'password' => ['required','string', new Password],
             
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username'=> $request->username,
                'password' => Hash::make($request->password),
            ]);
            
            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return $this->sendsuccess([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'Authenticated');
        } catch (Exception $error){
            return $this->senderror([
                'message' => 'Something went wrong',
                'error' => $error
            ],'Authentication failed', 500);
        }
    }

    public function logout(Request $request)  {
        $token = $request->user()->currentAccessToken()->delete();
        return $this->sendsuccess($token, 'Token Revoked');
    }
 
    public function fetch(Request $request){
        return $this->sendsuccess($request->user(), 'Data profile user berhasil diambil');
    }

    public function updateProfile(Request $request){
        $data = $request->all();

        $user = Auth::user();
        $user->update($data);

        return $this->sendsuccess($user, 'Profile Updated');

    }

    public function updatePhoto(Request $request){

        $validator = Validator::make($request->all(),[
            'file' => 'required|image|max:2048',

        ]);

        if($validator->fails()){
            return $this->sendError([
                'error' => $validator->errors()],
                'Update Photo Failed', 401);
        }

        if($request->file('file')){
            $file = $request->file->store('assets/user', 'public');
            // simpan foto ke database (url only)

            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();
            return $this->sendsuccess([$file], 'File successfully uploaded');

        }
    }
}
