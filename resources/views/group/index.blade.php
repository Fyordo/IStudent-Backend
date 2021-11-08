<?php
use App\Models\Classes\StudentClass;
use App\Models\Classes\GroupClass;

/* @var $student StudentClass */
/* @var $headman StudentClass */
/* @var $group GroupClass */
?>

@extends('layouts.main')

@section('title', 'Группа '. $group->printGroup())

@section('content')

<div class="text-center">
    <br>
    <br>
    <h1 class="display-4">Группа {{ $group->printGroup() }}</h1>
    <br>
    <br>
    <br>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm">
            <div class="text-center"><h2>Список группы</h2></div>
            <div class="GreyBox-3">
                <div class="text-left">
                    <ol>
                        @foreach ($group->getStudents() as $st)
                            @if ($st->email == $student->email)
                                <div class="col-xs-4"><a href=" {{ route("page", ["id" => $st->id]) }} " class="floating-button" style="font-size: 15px; width: 400px;">{{ $st->name }}</a></div>
                            @else
                                <div class="col-xs-4"><a href=" {{ route("page", ["id" => $st->id]) }} " class="floating-button" style="font-size: 15px; width: 400px;">{{ $st->name }}</a></div>
                            @endif
                        @endforeach

                    </ol>
                </div>
            </div>
        </div>

        <div class="col-sm">
            <div class="text-center"><h2>О группе</h2></div>
                    <div class="GreyBox-3">
                        <div class="text-left">
                            <p><b>Курс:</b> {{$group->groupCourse}} </p>
                            <p><b>Номер:</b> {{$group->groupNumber}}</p>
                            <p><b>Направление:</b> {{$group->direction->title}}</p>
                            <p>
                                <b>Староста:</b>
                                @if ($headman != null)
                                    <a href=" {{ route("page", ["id" => $group->headmanId]) }}" class="floating-button" style="font-size: 15px; width: 400px;">{{ $headman->name }}</a>
                                @else
                                    <a>Староста не назначен</a>
                                @endif
                            </p>
                            <p><b>Кол-во студентов:</b> {{ $group->countStudents() }}</p>
                            <a href="{{route("fullSchedule", ['groupId' => $group->id])}}" class="floating-button">
                                Расписание группы
                            </a>
                        </div>
                    </div>
        </div>

    </div>
</div>
<br>
<br>
@stop
