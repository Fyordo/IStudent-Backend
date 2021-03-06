<?php
use App\Models\Classes\StudentClass;

/* @var $student StudentClass */
?>

@extends('layouts.main')

@section('title', 'Об iStudent')

@section('content')
    <div class="text-center">
        <h1 class="display-4">Что такое iStudent?</h1><br>
    </div>
    <div class="text-left">
        <p>
            <b>iStudent - удобный сервис, объединяющий расписание, новости мехмата, просмотр лекций</b><br>
        </p>
        <h2>
            Что тут можно делать?<br>
        </h2>
        <ol>
            <li>Переходить в личный кабинет студента</li>
            <li>Переходить в сервис БРС</li>
            <li>Смотреть расписание в любой день или полное расписание</li>
            <li>Смотреть список каждой группы</li>
            <li>Смотреть некоторую информацию о студентах</li>
            <li>Добавлять уведомления на определённые даты</li>
            <li>Смотреть записи прошлых лет</li>
            <li>Смотреть новости мехмата</li>
        </ol>
        <h2>
            Что могут старосты?<br>
        </h2>
        <ol>
            <li>Добавлять уведомления к определённым парам</li>
            <li>Добавлять уведомления студентам своей группы или всей группе</li>
        </ol>
        <h2>
            Как работать с iStudent?<br>
        </h2>
        <ol>
            <li>Чтобы создать аккаунт на iStudent, нужно удостовериться, что ты - студент ЮФУ. А потому регистрация в iStudent происходит через Microsoft.</li>
            <li>Когда авторизация в Microsoft будет завершена, мы автоматически получим твоё ФИО, почту и фото. Изначальный пароль будет совпадать с почтой, группа и статус старосты не будет установлен.</li>
            <li>В конце тебе будет предложено выставить новый пароль (для авторизации в iStudent), указать свою группу (ну тут на честность) и выбрать, староста ли ты (если в выбранной группе есть староста, то будет ошибка регистрации).</li>
            <li>После заполнения всех дополнительных данных можно полноценно пользоваться iStudent.</li>
        </ol>
    </div>
@stop
