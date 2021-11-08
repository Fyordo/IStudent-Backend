@extends('layouts.main')

@section('title', 'Авторизация')

@section('content')
    <div class="text-center">
        <h1 class="display-4">Вход на сайт</h1>
        <h4 style="color: red">{{ ($message) }}</h4>
    </div>
    <br>
    <br>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="email">Введите Email</label>
            <input type="email" name="email" placeholder="Введите Email" id="email" />
        </div>
        <br>
        <div class="form-group">
            <label for="password">Введите пароль</label>
            <input type="password" name="password" placeholder="Введите пароль" id="password" />
        </div>
        <br>
        <button type="submit" class="btn btn-success">Войти</button>
    </form>
    <br>
    <br>
    <a href=" {{ route('signin') }}" class="floating-button" style="width: 400px">Регистрация через Microsoft</a>
@stop
