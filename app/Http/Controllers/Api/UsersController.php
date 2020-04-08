<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Notifications\NewUserResetPasswordMail;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

use App\User;

class UsersController extends Controller
{
    public function index($id, Request $request) 
    {
        $response = Gate::inspect('viewAny', [User::class]);

        if ($response->allowed()) {
            $dec = \base64_decode($id);

            $users = User::where('company_id', $dec)
                ->orderBy('id', 'DESC')
                ->get();

            $data = Users::collection($users);

            $items = $data->toArray($request);

            $currentPage = Paginator::resolveCurrentPage();
            $perPage = 10;
            $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
            $total = count($items);

            $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

            $paginator->withPath(config('app.url').'/api/v2/users');

            if($items !== []) {
                return response()->json($paginator);
            }
            return response()->json([
                'error' => 'No Records Available!'
            ], 401);
        } else {
            return $response->message();
        }
    }

    public function create(NewUserRequest $request) 
    {
        $response = Gate::inspect('create', [User::class]);

        if ($response->allowed()) {
            $pw = User::generatePassword();

            $user = new User;
            $user->station_id = $request->input('station_id');
            $user->employee_id = $request->input('employee_id');
            $user->email = $request->input('email');
            $user->password = $pw;
            $user->permission = $request->input('permission');

            $user->save();
        
            $email = $user->email;
            $this->sendUserEmail($user, $email);
            if ($user) {
                return $this->successResponseUser();
            } else {
                return $this->failedResponse();
            }
        } else {
            return $response->message();
        }
    }

    public function getUserToEdit($id)
    {
        $user = User::findOrFail($id);

        $flatten = new UserResource($user);

        return response()->json([
            'user' => $flatten
        ]);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update($id, Request $request) 
    {
        $user = User::find($id);

        $response = Gate::inspect('update', $user);

        if ($response->allowed()) {
            $user->permission = $request->get('permission');
    
            $user->save();  
       
            return response()->json([
                'success' => 'User updated successfully'
            ]);
        } else {
            return $response->message();
        }
    }

    public function sendUserEmail($user, $email) 
    {
        //New user password reset code
        $token = $this->createToken($email);
        $user->notify(new NewUserResetPasswordMail($token));
    }

    public function failedResponse() 
    {
        return response()->json([
            'error' => 'Token not generated.'
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponseUser() 
    {
        return response()->json([
            'data' => 'Password Reset Email has been sent to Users Inbox.'
        ], Response::HTTP_OK);
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

    public function delete($ids, User $user) 
    {
        $response = Gate::inspect('delete', $user);

        if ($response->allowed()) {
           
            $id = explode(",", $ids);
            $user_to_delete = User::find($id);

            $user = User::whereIn('id', $id)->delete();

            return response()->json([
                'data' => 'User deleted'
            ]);
        } else {
            return $response->message();
        }
    }
}
