<?php
use App\Models\Classes\StudentClass;

/* @var $student StudentClass */
/* @var $ownerStudent StudentClass */

?>

@extends('layouts.main')

@section('title', isset($ownerStudent) ? $ownerStudent->name : "Ошибка")

@section('content')

@if (!isset($ownerStudent))
    <div class="text-center">
        <br>
        <br>
        <h1 class="display-4">Такого студента нет</h1>
        <br>
        <br>
        <br>
    </div>
@else
<div class="text-center">
    <br>
    <br>
    <h1 class="display-4">{{ $ownerStudent->name }}</h1>
    <br>
    <br>
    <br>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm">
            <div class="GreyBox">
                <div class="text-left">
                    @if (isset($ownerStudent->photo))
                        <div class="text-center">
                            <img src="data:image/jpeg;base64,{{$ownerStudent->photo}}" class="rounded-circle shadow"/>
                        </div>
                    @else
                        <div class="text-center">
                            <h1 style="color: red;">Фото не установлено</h1>
                        </div>
                    @endif
                    <br>
                    <a href="{{ route("group", ['id'=>$ownerStudent->groupId]) }}" class="floating-button"><b>Группа:</b> {{$ownerStudent->printGroup()}}</a><br><br>
                    <b>Email: </b><a href="mailto:{{ $ownerStudent->email }}" class="floating-button" style="font-size: 20px;">{{ $ownerStudent->email }}</a><br>
                    @if ($ownerStudent->isHeadman)
                        <br>
                        <br>
                        <br>
                        <b>Староста</b>
                    @endif
                </div>
            </div>
        </div>


        <div class="col-sm">
            <div class="text-center"><h2>Уведомления</h2>
            @if ($ownerStudent->id == $student->id || $student->isHeadman && $ownerStudent->groupId == $student->groupId)
                <a href="#" class="floating-button">Добавить уведомление</a><br>
            @endif

            @if ($student->isHeadman && $ownerStudent->groupId == $student->groupId)
                <a href="#" class="floating-button">Добавить уведомление группе</a><br>
            @endif
            </div>
            <br>
            @if ($student->id == $ownerStudent->id)
                <div class="row">
                    <div class="col-sm">
                        @if (empty($student->notifications))
                            <div class="text-center">
                                <h1>Уведомлений нет</h1>
                            </div>
                        @endif
                        @foreach ($student->notifications as $note)
                            <div class="GreyBox-2">
                                <div class="text-center">
                                    <h3>{{$note->topic}}</h3>
                                    <b>{{$note->date}}</b><br><br>
                                    {{$note->text}}<br>
                                    <a href="#">Удалить</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-sm">
                        <div class="text-center">
                            <h1 style="color: red;">У вас нет доступа к просмотру чужих уведомлений</h1>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endif
<br>
<br>
@stop
