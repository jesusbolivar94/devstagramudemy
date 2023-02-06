<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{

    public function index(User $user)
    {
        return view('dashboard', [
            'user' => $user,
            'posts' => $user->posts()->latest()->paginate(8)
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'required',
        ]);

        Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
            'user_id' => auth()->user()->id
        ]);

        // Otra forma de ingresar, pero por relaciones
        /*$request->user()->posts()->create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
            'user_id' => auth()->user()->id
        ]);*/

        return redirect()->route('posts.index', auth()->user()->username );
    }

    public function show(User $user, Post $post)
    {
        return view('posts.show', ['post' => $post]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        // Eliminar la imagen
        $image_path = public_path('uploads/' . $post->image);

        if ( File::exists( $image_path ) ) {
            //unlink($image_path);
            File::delete($image_path);
        }

        return redirect()->route('posts.index', auth()->user()->username);
    }

}
