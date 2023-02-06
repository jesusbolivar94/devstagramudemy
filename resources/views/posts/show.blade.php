@extends('layouts.app')

@section('title')
    {{ $post->title }}
@endsection

@section('body')
    <div class="container mx-auto md:flex">
        <div class="md:w-1/2">
            <img src="{{ asset('uploads') . '/' . $post->image }}" alt="Imagen del post {{ $post->title }}">

            <div class="p-3 flex items-center gap-3">
                @auth

                    @if($post->checkLike(auth()->user()))
                        <form action="{{ route('posts.likes.destroy', $post) }}" method="POST">
                            @method('DELETE')
                            @csrf

                            <div class="my-4">
                                <button type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('posts.likes.store', $post) }}" method="POST">
                            @csrf

                            <div class="my-4">
                                <button type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5"
                                         stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    @endif

                @endauth

                <p class="font-bold">
                    {{ $post->likes()->count() }}
                    <span class="font-normal">Likes</span>
                </p>
            </div>

            <div>
                <p class="font-bold">{{ $post->user->username }}</p>
                <p class="text-sm text-gray-500">
                    {{ $post->created_at->diffForHumans() }}
                </p>
                <p class="mt-5">
                    {{ $post->description }}
                </p>
            </div>

            @auth
                @if($post->user_id === auth()->user()->id)
                    <form method="POST" action="{{ route('posts.destroy', $post) }}">
                        @method('DELETE')
                        @csrf

                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 p-2 rounded text-white font-bold mt-4 cursor-pointer"
                        >Eliminar publicación</button>
                    </form>
                @endif
            @endauth
        </div>
        <div class="md:w-1/2 p-5">
            <div class="shadow bg-white p-5 mb-5 rounded">
                @auth
                    <p class="text-xl font-bold text-center mb-4">
                        Agrega un nuevo comentario
                    </p>

                    @if(session('mensaje'))
                        <div class="bg-green-500 p-2 rounded-lg mb-6 text-white text-center uppercase font-bold"
                        >{{ session('mensaje') }}</div>
                    @endif

                    <form action="{{ route('comments.store', [ $post->user, $post ]) }}" method="POST">
                        @csrf

                        <div class="mb-5">
                            <label for="comment" class="mb-2 block uppercase text-gray-500 font-bold">Añade un comentario</label>
                            <textarea
                                type="text"
                                id="comment"
                                name="comment"
                                placeholder="Agrega un comentario"
                                class="border p-3 w-full rounded-lg resize-none @error('comment') border-red-500 @enderror"
                            ></textarea>
                            @error('comment')
                                <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg">
                            Comentar
                        </button>
                    </form>
                @endauth

                @if($post->comments->count())
                    <p class="text-xl font-bold text-center my-4">
                        Comentarios
                    </p>
                @else
                    <p class="mt-5 text-center">No hay comentarios aún.</p>
                @endif

                <div class="bg-white mb-5 max-h-96 overflow-y-scroll">
                    @if($post->comments->count())
                        @foreach($post->comments as $comment)
                            <div class="p-5 border-gray-200 border-b border-dotted relative">
                                <a href="{{ route('posts.index', $comment->user) }}"
                                   class="font-bold text-gray-600 text-sm">{{ $comment->user->username }}</a>
                                <p>{{ $comment->comment }}</p>
                                <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                @auth
                                    @if(
                                        $post->user_id === auth()->user()->id
                                        || $comment->user_id === auth()->user()->id
                                    )
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                            @method('DELETE')
                                            @csrf

                                            <button type="submit"
                                                    class="absolute top-0 right-2 text-sm bg-red-500 hover:bg-red-600 p-2 rounded text-white font-bold mt-4 cursor-pointer"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection