<?php
use App\Models\Classes\StudentClass;

/* @var $student StudentClass */
?>

@extends('layouts.main')

@section('title', 'iStudent')

@section('content')
<div class="text-center">
    <br>
    <img src="favicon.ico" width="200" height="200">
    <h1 class="display-4">iStudent</h1>
    <h3>Интерфейс студента</h3>
    <br>
    <br>
    <br>
    <br>
</div>
@if ($student != null)
    <div class="text-center">
        <h2 class="display-6">Намеченные события:</h2>
    </div>
    @if (sizeof($student->notifications) > 0)
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    @for ($i = 0; $i < count($student->notifications); $i += 2)
                        <div class="GreyBox-2">
                            <div class="text-center">
                                <h1>{{ $student->notifications[$i]->topic }}</h1>
                                <b>{{ $student->notifications[$i]->date }}</b>
                                <p>{{ $student->notifications[$i]->text }}</p>
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="col-sm">
                    @for ($i = 1; $i < count($student->notifications); $i += 2)
                        <div class="GreyBox-2">
                            <div class="text-center">
                                <h1>{{ $student->notifications[$i]->topic }}</h1>
                                <b>{{ $student->notifications[$i]->date }}</b>
                                <p>{{ $student->notifications[$i]->text }}</p>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    @else
        <div class="text-center">
            <h1 class="display-6">Событий нет. Их можно добавить <a href=" {{route("page", ["id" => $student->id])}} ">здесь</a></h1>
        </div>
    @endif
@else
    <div class="text-center">
        <h2 class="display-6">Авторизуйтесь или зарегистрируйтесь, чтобы продолжить работу с сайтом</h2>
    </div>
@endif
@stop
