<?php

namespace App\Http\Controllers;

use App\Models\Classes\DirectionClass;
use App\Models\Classes\GroupClass;
use App\Models\Classes\StudentClass;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Главная страница
     */
    public function index()
    {
        return view("home.welcome")->with([
            'student' => StudentClass::getStudent(Auth::user())
        ]);
    }

    /**
     * О нас
     */
    public function about()
    {
        return view("home.about")->with([
            'student' => StudentClass::getStudent(Auth::user())
        ]);
    }

    /**
     * Политика конфиденциальности
     */
    public function privacy()
    {
        return view("home.privacy")->with([
            'student' => StudentClass::getStudent(Auth::user())
        ]);
    }
}
