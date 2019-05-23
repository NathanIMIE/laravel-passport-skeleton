<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\DeleteUserRequest;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a user
     *
     * @return Response
     */
    public function createUser(CreateUserRequest $request)
    {
        $input = $request->validated();

        $user = new User;

        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = Hash::make($input['password']);
        $user->email_verified_at = now();
        $user->remember_token = Str::random(10);

        $user->save();

        return response($user, 201);
    }

    /**
     * Delete my user
     *
     * @return Response
     */
    public function deleteUser(DeleteUserRequest $request)
    {
        $user = $request->user();
        
        foreach($user->assignedTickets as $ticket) {
            $ticket->state="PENDING";
            $ticket->assignation()->associate(null);
            $ticket->save();
        }
        
        $user->delete();
        
        return response("User deleted", 204);
    }
}
