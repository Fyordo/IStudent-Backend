<?php

use App\Models\Classes\LessonClass;
use App\Models\Classes\StudentClass;

/* @var $student StudentClass */
/* @var $lesson LessonClass */

?>

@extends('layouts.main')

@section('title', 'Расписание')

@section('content')

<br>

<div class="container">
    <div class="text-center">
        <div class="row">
            <div class="col-sm">
                <a href="{{ route("lessonList", ['groupId' => $groupId, 'day' => date('d', $yesterday), 'month' => date('m', $yesterday), 'year' => date('Y', $yesterday)]) }}" class="floating-button">
                    Предыдущий день
                </a>
            </div>

            <div class="col-sm">

            </div>

            <div class="col-sm" style="text-align: right">
                <a href="{{ route("lessonList", ['groupId' => $groupId, 'day' => date('d', $tomorrow), 'month' => date('m', $tomorrow), 'year' => date('Y', $tomorrow)]) }}" class="floating-button">
                    Следующий день
                </a>
            </div>
        </div>
        <br>
        <h1 class="display-5" style="font-size: 40px">{{ "Расписание " . $groupNum }}</h1>
    </div>
</div>

<div class="text-center">
    <p>{{ $weekDay . ' - ' . ($upWeek ? "Верхняя неделя" : "Нижняя неделя") .  " - " . date('d', $today) . "." . date('m', $today) . "." . date('Y', $today)}}</p>
    <h1 class="display-5"></h1>
</div>

@if (count($lessons) == 0)
    <br>
    <br>
    <br>
    <div class="text-center">
        <h1 class="display-4">Пар нет</h1><br>
    </div>
@else
    <div class="text-left">
        <ol>
            @foreach ($lessons as $lesson)
                <li>
                    <b>{{ $lesson->title }}</b><br>
                    @if ($student->is_headman)
                        <a href="#">
                            Добавить дополнение
                        </a><br>
                    @endif
                    <em>Ведёт: {{ $lesson->teacher === null ? "" : $lesson->teacher->name }}</em><br>
                    <em>Место проведения: {{ $lesson->location }}</em><br>
                    <em>Время: {{ $lesson->getTime() }}</em>
                    @if (count($lesson->addictions) > 0)
                        <br>
                        @foreach ($lesson->addictions as $addiction)
                            <b style="color: red">Дополнение: {{ $addiction->description }}</b>
                            <br>
                        @endforeach
                    @endif
                    <br>
                    <br>
                </li>
            @endforeach
        </ol>
    </div>
@endif

@stop
