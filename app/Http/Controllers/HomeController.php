<?php

namespace App\Http\Controllers;

use App\Models\Classes\StudentClass;
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
     * Об iStudent
     */
    public function info()
    {
        return view("home.info")->with([
            'student' => StudentClass::getStudent(Auth::user())
        ]);
    }
}
