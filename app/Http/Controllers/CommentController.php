<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, User $user, Post $post)
    {
        // Validate
        $this->validate($request, [
            'comment' => 'required|max:255'
        ]);

        // store comment
        Comment::create([
            'user_id' => auth()->user()->id,
            'post_id' => $post->id,
            'comment' => $request->comment
        ]);

        // print view
        return back()->with('mensaje', 'Comentario Realizado Correctamente');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()
            ->route('posts.show', [$comment->user, $comment->post])
            ->with('mensaje', 'Comentario Eliminado Correctamente');
    }
}
