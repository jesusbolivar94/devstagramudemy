<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        // Check if the logged user have permission to edit the route user profile.
        $this->authorizeForUser( auth()->user(), 'view', [$user] );

        return view('profile.index', $user);
    }

    public function store(Request $request, User $user)
    {
        $request->request->add(['username' => Str::slug($request->username)]);

        $request->validate([
            'username' => [
                'required',
                'unique:users,username,' . Auth::user()->id,
                'min:3',
                'max:20',
                'not_in:twitter,editar-perfil'
            ],
        ]);

        if ( $request->image ) {
            $img = $request->file('image');

            $imageName = Str::uuid() . '.' . $img->extension();

            $imageServer = Image::make($img);
            $imageServer->fit(1000, 1000);

            $imagePath = public_path('profiles') . '/' . $imageName;
            $imageServer->save( $imagePath );
        }

        $user->username = $request->username;
        $user->image = $imageName ?? Auth::user()->image ?? null;
        $user->save();


        return redirect()->route('posts.index', $user->refresh());
    }
}
