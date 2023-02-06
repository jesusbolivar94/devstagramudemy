<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Validation
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => Str::slug( $request->username ),
            'email' => $request->email,
            'password' => Hash::make( $request->password )
        ]);

        // Authentication
        /*auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);*/

        // Other way to Authenticate
        auth()->attempt( $request->only('email', 'password') );

        // Redirect
        return redirect()->route('posts.index', auth()->user()->username );
    }
}
