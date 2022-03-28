<?php
    use App\Models\Classes\StudentClass;
    // asset("public/css/app.css")
    // asset("public/js/app.js")
    /* @var $student StudentClass */

    $day = date("d", strtotime('+3 hours'));
    $month = date("m", strtotime('+3 hours'));
    $year = date("Y", strtotime('+3 hours'));
?>

<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield("title")</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"/>
    <link rel="shortcut icon" href="{{ asset(env("META_DIR") . 'favicon.ico') }}"/>
    <link rel="stylesheet" href=" {{ asset("css/app.css") }} "/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,80" rel="stylesheet">
</head>
<body>
<header class="header">
    <nav class="navbar">
        <div class="link-list">
            @if(!isset($student))
                <div class=link-list-block  style="padding-left: 570px">
                    <ul class="link-sublist text-right">
                        <li>
                            <a  class="header-button" href="{{ route("home") }}">Главная</a>
                        </li>
                    </ul>
                </div>
                <div class="nav-logo-wrapper">
                    <a class="navbar-brand" href=" {{ route("home") }} ">
                        <img class="nav-logo" src="{{ asset(env("META_DIR") . 'favicon.ico') }}" alt="Logotip IStudent">
                    </a>
                </div>
                <div class=link-list-block>
                    <ul class="link-sublist">
                        <li>
                            <a class="header-button" href="{{ route("login") }}">Войти</a>
                        </li>
                    </ul>
                </div>
            @else
                <div class=link-list-block style="padding-right: 40px;">
                    <ul class="link-sublist text-left">
                        <li class="dropdown-wrapper">
                            <a class="dropdown" id="dropdown-groups">Группы</a>
                            <div class="dropdown-content-groups display-none" id="dropdown-groups-content">
                                @if ($student->group)<a class="dropdown-link" href="{{route("group", ['id' => $student->group->id])}}">Моя группа</a>@endif
                                <a class="dropdown-link" href="{{route("all")}}">Все группы</a>
                            </div>
                        </li>
                        <li class="dropdown-wrapper">
                            <a class="dropdown" id="dropdown-lessons">Расписание</a>
                            <div class="dropdown-content-lessons display-none" id="dropdown-lessons-content">
                                @if ($student->group)
                                    <a class="dropdown-link" href="{{ route("lessonList", ['groupId' => $student->group->id, 'day' => $day, 'month' => $month, 'year' => $year]) }}">
                                    Сегодня
                                </a>
                                <a class="dropdown-link" href="{{route("fullSchedule", ['groupId' => $student->group->id])}}">
                                    На неделю
                                </a>
                                @endif
                            </div>
                        </li>
                        <li class="dropdown-wrapper">
                            <a class="dropdown" id="dropdown-teachers">Преподаватели</a>
                            <div class="dropdown-content-teachers display-none" id="dropdown-teachers-content">
                                <a class="dropdown-link" href="#">
                                    Мои преподаватели
                                </a>
                                <a class="dropdown-link" href="#">
                                    Все преподаватели
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="nav-logo-wrapper">
                    <a class="navbar-brand" href=" {{ route("home") }} ">
                        <img class="nav-logo" src="{{ asset(env("META_DIR") . 'favicon.ico') }}" alt="Logo IStudent">
                    </a>
                </div>
                <div class=link-list-block>
                    <ul class="link-sublist text-right">
                        <li>
                            <a class="header-button" href="{{ route("page", ['id' => $student->id]) }}">Мой профиль</a>
                        </li>
                        <li>
                            <a class="header-button" href="https://grade.sfedu.ru/#" target="_blank">Мои оценки</a>
                        </li>
                        <li>
                            <a class="header-button" href="https://sfedu.ru/www/stat_pages15.show?p=LKS/profil/D" target="_blank">ЛК Студента</a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </nav>
</header>

<div class="container">
    <main role="main" class="pb-3">
        @yield('content')
    </main>
</div>

<footer class="border-top footer text-muted">
    <div class="container">
        &copy; 2021 - iStudent (0.5) - <a href="{{ route("about") }}">О нас</a> | <a href="{{ route("info") }}">Об iStudent</a>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src=" {{ asset(env("META_DIR") . "js/app.js")}} "></script>
<script>
    const dropdown_g = document.querySelector('#dropdown-groups');
    const dropdownContent_g = document.querySelector('#dropdown-groups-content');
    const dropdown_l = document.querySelector('#dropdown-lessons');
    const dropdownContent_l = document.querySelector('#dropdown-lessons-content');
    const dropdown_t = document.querySelector('#dropdown-teachers');
    const dropdownContent_t = document.querySelector('#dropdown-teachers-content');

    dropdown_t.onclick = function (e) {
        e.stopPropagation();
        dropdownContent_t.classList.toggle('display-none');
        dropdownContent_l.classList.add('display-none');
        dropdownContent_g.classList.add('display-none');
    }

    dropdown_l.onclick = function (e) {
        e.stopPropagation();
        dropdownContent_t.classList.add('display-none');
        dropdownContent_l.classList.toggle('display-none');
        dropdownContent_g.classList.add('display-none');
    }

    dropdown_g.onclick = function (e) {
        e.stopPropagation();
        dropdownContent_t.classList.add('display-none');
        dropdownContent_l.classList.add('display-none');
        dropdownContent_g.classList.toggle('display-none');
    }

    window.onclick = function () {
        dropdownContent_t.classList.add('display-none');
        dropdownContent_l.classList.add('display-none');
        dropdownContent_g.classList.add('display-none');
    }
</script>
</body>
</html>
