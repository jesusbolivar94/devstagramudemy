@extends('layouts.app')

@section('title')
    Pagina Principal
@endsection

@section('body')
    <x-list-posts :posts="$posts" />
@endsection
