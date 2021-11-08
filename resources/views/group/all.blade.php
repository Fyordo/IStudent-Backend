<?php
use App\Models\Classes\StudentClass;
use App\Models\Classes\GroupClass;

/* @var $student StudentClass */
/* @var $groups array */
$count = 0;
?>

@extends('layouts.main')

@section('title', 'Группы ИММиКН им. Воровича')

@section('content')

<div class="text-center">
    <br>
    <h1 class="display-4">Группы ИММиКН им. Воровича</h1>
    <br>
    <br>
    <br>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm">

            @for ($i = 0; $i < count($groups); $i += 2)

                <div class="GreyBox-2">
                    <div class="text-center">
                        <a href="{{ route("group", ['id' => $groups[$i]->id]) }}" class="floating-button" style="font-size: 15px;">
                            <b>Группа {{$groups[$i]->printGroup()}}</b>
                        </a>
                        <p>Кол-во студентов: {{$groups[$i]->countStudents()}}</p>
                        <p>{{$groups[$i]->direction->title}}</p>
                    </div>
                </div>
                @if ($count++ == 6)
                    <br>
                    <br>
                    <br>
                    <?php $count = 0?>
                @endif
            @endfor
        </div>
        <div class="col-sm">
            @for ($i = 1; $i < count($groups); $i += 2)
                <div class="GreyBox-2">
                    <div class="text-center">
                        <a href="{{ route("group", ['id' => $groups[$i]->id]) }}" class="floating-button" style="font-size: 15px;">
                            <b>Группа {{$groups[$i]->printGroup()}}</b>
                        </a>
                        <p>Кол-во студентов: {{$groups[$i]->countStudents()}}</p>
                        <p>{{$groups[$i]->direction->title}}</p>
                    </div>
                </div>
                @if ($count++ == 6)
                    <br>
                    <br>
                    <br>
                    <?php $count = 0?>
                @endif
            @endfor
        </div>
    </div>
</div>

@stop
