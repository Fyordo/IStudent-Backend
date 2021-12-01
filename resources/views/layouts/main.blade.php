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
<header>
    <nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-light border-bottom box-shadow mb-3" style="background-color: #EFEFEF;">
        <div class="container">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse d-sm-inline-flex justify-content-between">
                @if(!isset($student))
                    <ul class="navbar-nav flex-grow-1">
                        <li class="nav-item">
                            <a class="navbar-brand" href=" {{ route("home") }} ">iStudent</a>
                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>
                    <a class="nav-link" href="{{ route("login") }}">Войти</a>
                @else
                    <ul class="navbar-nav flex-grow-1">
                        <li class="nav-item">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Группы
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if ($student->email != "lagutinfedya@gmail.com")
                                        <a class="dropdown-item" href="{{route("group", ['id' => $student->groupId])}}">Моя группа</a>
                                    @endif
                                    <a class="dropdown-item" href="{{route("all")}}">Все группы</a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Расписание
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if ($student->email != "lagutinfedya@gmail.com")
                                        <a class="dropdown-item" href="{{ route("lessonList", ['groupId' => $student->groupId, 'day' => $day, 'month' => $month, 'year' => $year]) }}">
                                            Сегодня
                                        </a>
                                        <a class="dropdown-item" href="{{route("fullSchedule", ['groupId' => $student->groupId])}}">Полное расписание</a>
                                    @endif
                                    @if ($student->isHeadman)
                                        <a class="dropdown-item" href="#">Создать пару</a>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="navbar-brand" href="{{ route("home") }} ">iStudent</a>
                        </li>

                        <div style="width:1px;height:100%;margin:0 auto;"></div>

                        <li class="nav-item">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= $student->name ?>
                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route("page", ['id' => $student->id]) }}">Моя страница</a>
                                    <a class="dropdown-item" href="https://grade.sfedu.ru/#" target="_blank">Мои оценки</a>
                                    <a class="dropdown-item" href="https://sfedu.ru/www/stat_pages15.show?p=LKS/profil/D" target="_blank">ЛК Студента</a>
                                    <a class="dropdown-item" href="http://edu.mmcs.sfedu.ru/" target="_blank">Moodle</a>
                                    <a class="dropdown-item" href="{{ route("logout") }}">Выйти</a>
                                </div>
                            </div>
                        </li>
                    </ul>

                @endif

            </div>
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
</body>
</html>
