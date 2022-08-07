<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $name = $user->name;
        return view('users.show', [
            'title' => "User Card - {$name}",
            'id' => $user->id,
            'name' => $user->name,
            'comments' => $user->comments
        ]);
    }
}
