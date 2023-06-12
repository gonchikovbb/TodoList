@extends('layouts.master')
@section("content")
    @auth
        <p>Добро пожаловать <b>{{ Auth::user()->name }}</b></p>
        <a class="btn btn-success" href="{{ route('tasks.showTasks') }}">Мои задачи</a>
        <form id="logout-form" action="{{ route('signOut') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Выйти</button>
        </form>

    @endauth
    @guest
        <a class="btn btn-primary" href="{{ route('signIn') }}">Войти</a>
        <a class="btn btn-info" href="{{ route('signUp') }}">Регистрация</a>
    @endguest
@endsection
