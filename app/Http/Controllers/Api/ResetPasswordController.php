<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UserResetPasswordMail;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    //Password Request codes start from here
    public function sendEmail(Request $request) 
    {

        if (!$this->validateEmail($request->email)) {
            return $this->failedResponse();
        }

        $this->send($request->email);
        return $this->successResponse();
    }

    public function send($email) 
    {
        $user = User::where('email', $email)->first();

        $token = $this->createToken($email);
        $user->notify(new UserResetPasswordMail($token));
    }

    public function createToken($email) 
    {

        $oldToken = DB::table('password_resets')->where('email', $email)->first();
        if ($oldToken) {
            return $oldToken->token;
        }

        $token = Str::random(60);
        $this->saveToken($token, $email);
        return $token;
    }

    public function saveToken($token, $email) 
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    public function validateEmail($email) 
    {
        return !!User::where('email', $email)->first();
    }

    public function failedResponse() 
    {
        return response()->json([
            'error' => 'Email isn\'t found on our database'
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse() 
    {
        return response()->json([
            'data' => 'Reset Email is sent successfully, please check your inbox.'
        ], Response::HTTP_OK);
    }
}
