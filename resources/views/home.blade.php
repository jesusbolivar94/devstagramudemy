@extends('layouts.app')

@section('title')
    Pagina Principal
@endsection

@section('body')
    @if($posts->count())
        <div class="grid md:gird-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($posts as $post)
                <div>
                    <a href="{{ route('posts.show', [$post->user, $post]) }}">
                        <img
                            src="{{ asset('uploads') . '/' . $post->image }}"
                            alt="Imagen del post {{ $post->title }}">
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center">No hay posts, sigue a alguien para poder mostrar sus posts</p>
    @endif
@endsection
