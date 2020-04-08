<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\User;

class ChangePasswordController extends Controller
{

    public function process(ChangePasswordRequest $request) {
        // dd($request->all());
        return $this->getPasswordResetTableRow($request)->count() > 0 ? $this->changePassword($request) :
        $this->tokenNotFoundResponse();
    }

    private function getPasswordResetTableRow(Request  $request) {
        return DB::table('password_resets')->where([
            'email' => $request->input('email'),
            'token' => $request->input('token')
        ]);
    }

    private function tokenNotFoundResponse() {
        return response()->json([
            'error' => 'Email is incorrect'
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function changePassword(Request $request) {
        $user = User::whereEmail($request->email)->first();
        $user->update([
            'password'=> Hash::make($request['password'])
        ]);

        $this->getPasswordResetTableRow($request)->delete();
        return response()->json(
            [
                'status' => 'Password changed successfully!'
            ]
        );
    }
}
