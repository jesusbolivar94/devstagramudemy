@extends('layouts.app')

@section('title')
    Perfil: {{ $user->username }}
@endsection

@section('body')

    <div class="flex justify-center">
        <div class="w-full md:w-8/12 lg:w-6/12 flex flex-col items-center md:flex-row">
            <div class="w-8/12 lg:6/12 px-5">
                <img
                    src="{{
                        $user->image
                            ? asset('profiles/' . $user->image)
                            : asset('img/usuario.svg')
                    }}"
                    @if($user->image) class="rounded-full" @endif
                    alt="Imagen usuario">
            </div>
            <div class="md:w-8/12 lg:6/12 px-5 flex flex-col items-center md:justify-center py-10 md:py-10 md:items-start">
                <div class="flex items-center gap-2">
                    <p class="text-gray-700 text-2xl mb-3">{{ $user->username }}</p>
                    @auth
                        @if( $user->id === auth()->user()->id )
                            <a
                                href="{{ route('profile.index', $user) }}"
                                class="text-gray-500 hover:text-gray-500 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="w-5 h-5">
                                    <path
                                        d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z"/>
                                </svg>
                            </a>
                        @endif
                    @endauth
                </div>


                <p class="text-gray-800 text-sm mb-3 font-bold">
                    {{ $user->followers()->count() }}
                    <span class="font-normal">
                        @choice('Seguidor|Seguidores', $user->followers()->count())
                    </span>
                </p>

                <p class="text-gray-800 text-sm mb-3 font-bold">
                    {{ $user->following()->count() }}
                    <span class="font-normal"> Siguiendo</span>
                </p>

                <p class="text-gray-800 text-sm mb-3 font-bold">
                    {{ $user->posts()->count() }}
                    <span class="font-normal">
                        @choice('Post|Posts', $user->posts()->count())
                    </span>
                </p>

                @auth
                    @if($user->id !== auth()->user()->id)
                        @if($user->isFollowing( auth()->user() ))
                            <form action="{{ route('users.unfollow', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="bg-red-500 text-white uppercase rounded-lg px-3 py-1 text-xs font-bold cursor-pointer">
                                    Dejar de Seguir
                                </button>
                            </form>
                        @else
                            <form action="{{ route('users.follow', $user) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="bg-blue-500 text-white uppercase rounded-lg px-3 py-1 text-xs font-bold cursor-pointer">
                                    Seguir
                                </button>
                            </form>
                        @endif
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <section class="container mx-auto mt-10">
        <h2 class="text-4xl text-center font-black my-10">Publicaciones</h2>

        @if($posts->count() > 0)

            <div class="grid md:gird-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($posts as $post)

                    <div>
                        <a href="{{ route('posts.show', [$user, $post]) }}">
                            <img
                                src="{{ asset('uploads') . '/' . $post->image }}"
                                alt="Imagen del post {{ $post->title }}">
                        </a>
                    </div>

                @endforeach
            </div>

            <div class="my-10">
                {{ $posts->links() }}
            </div>

        @else

            <p class="text-gray-600 uppercase text-sm text-center font-bold">No hay publicaciones</p>

        @endif
    </section>

@endsection
