<?php

use App\Models\Classes\LessonClass;
use App\Models\Classes\StudentClass;

/* @var $student StudentClass */
/* @var $lesson LessonClass */
/* @var $groupString string */
/* @var $weekDays array */

?>

@extends('layouts.main')

@section('title', 'Полное расписание ' . $groupString)

@section('content')

<br>

<div class="text-center">
    <h1 class="display-5">{{'Полное расписание ' . $groupString}}</h1>
</div>
<br>

<div class="container">
    <div class="row">
        <div class="col-sm">
            <div class="text-left">
                @for ($i = 1; $i <= 6; $i++)

                    <div class="group">
                        <br>
                        <div class="text-center">
                            <b>
                                <p align="center">{{ $weekDays[$i] . " - " . "Верхняя неделя" }}</p></b>
                        </div>
                            <ol>
                                <?php
                                    $count = 0;
                                ?>
                                @foreach ($lessons as $lesson)
                                    @if ($lesson->week_day == $i && $lesson->up_week)
                                        <?php
                                            $count++;
                                        ?>
                                        <li>
                                            <b>{{$lesson->title}}</b><br>
                                            <em><b>Ведёт:</b> {{$lesson->lecturer}}</em><br>
                                            <em><b>Место проведения:</b> {{$lesson->location}}</em><br>
                                            <em><b>Время:</b> {{$lesson->getTime()}}</em>
                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                            <?php
                                if ($count == 0) echo '<h1 align="center">Пар нет</h1>';
                                $count = 0;
                            ?>
                            <br>
                    </div>
                    <br>
                @endfor
            </div>
        </div>

        <div class="col-sm">
            <div class="text-left">
                @for ($i = 1; $i <= 6; $i++)

                    <div class="group">
                        <br>
                        <div class="text-center">
                            <b>
                                <p align="center">{{ $weekDays[$i] . " - " . "Нижняя неделя" }}</p></b>
                        </div>
                        <ol>
                            <?php
                            $count = 0;
                            ?>
                            @foreach ($lessons as $lesson)
                                @if ($lesson->week_day == $i && !$lesson->up_week)
                                    <?php
                                    $count++;
                                    ?>
                                    <li>
                                        <b>{{$lesson->title}}</b><br>
                                        <em><b>Ведёт:</b> {{$lesson->lecturer}}</em><br>
                                        <em><b>Место проведения:</b> {{$lesson->location}}</em><br>
                                        <em><b>Время:</b> {{$lesson->getTime()}}</em>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                        <?php
                            if ($count == 0) echo '<h1 align="center">Пар нет</h1>';
                            $count = 0;
                        ?>
                        <br>
                    </div>
                    <br>
                @endfor
            </div>
        </div>

    </div>
</div>

@stop
