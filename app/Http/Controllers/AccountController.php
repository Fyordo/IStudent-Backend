<?php

namespace App\Http\Controllers;

use App\Models\Classes\GroupClass;
use App\Models\Classes\StudentClass;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    /**
     * Авторизация
     */
    public function login(Request $request, $message = "")
    {
        if ($request->isMethod('post')) {
            $validateFileds = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $studentFind = Student::where('email', $request->input('email'))->first();

            if ($studentFind && $studentFind["password"] == $request->input('password')){
                Auth::login($studentFind, true);
                return redirect(route('home'));
            }
        }

        return view("account.login", [
            'message' => $message
        ]);
    }

    /**
     * Выход из аккаунта
     */
    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return redirect(route("home"));
    }

    /**
     * Подтверждение страницы студента
     */
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $validateFileds = $request->validate([
                'groupId' => 'required',
                'password' => 'required|confirmed',
            ]);

            Student::where('id', Auth::id())->update([
                'groupId' => (integer)$request->input('groupId'),
                'password' => $request->input('password'),
                'isHeadman' => !($request->input('isHeadman') == null)
            ]);

            $studentFind = Student::where('email', Auth::user()["email"])->first();

            if (!($request->input('isHeadman') == null))
            {
                Group::where("id", (integer)$request->input('groupId'))
                    ->update([
                        'headmanId' => Auth::id()
                    ]);
            }

            Auth::logout();
            Auth::login($studentFind, true);



            return redirect(route("home"));
        }

        $groupsDB = Group::orderBy("groupCourse")->orderBy('groupNumber')->get(); // Данные из БД
        $groups = []; // Массив нормальных классов

        foreach ($groupsDB as $group){
            array_push($groups, new GroupClass($group));
        }

        return view("account.add", [
            'student' => StudentClass::getStudent(Auth::user()),
            'groups' => $groups
        ]);
    }

    // ----------------- САМАЯ СТРАШНАЯ ЧАСТЬ ----------------------

    /**
     * Авторизация через Microsoft
     */
    public function signin()
    {
        session_start();

        $tenant = "common";
        $client_id = "cccbc182-4e70-41ed-a39a-5b658f2b0ee9";
        //$callback = "http://localhost:8000/callback";
        $callback = "https://istudent-sfedu.herokuapp.com/callback";
        $scopes = ["User.Read"];

        $_SESSION["state"] = random_int(1, 200000);

        $parameters = [
            'client_id' => $client_id,
            'response_type' => 'code',
            'redirect_uri' => $callback,
            'response_mode' => 'query',
            'scope' => implode(' ', $scopes),
            'state' => $_SESSION["state"]
        ];

        $url = "https://login.microsoftonline.com/" . $tenant . "/oauth2/v2.0/authorize?". http_build_query($parameters);

        return redirect($url);
    }

    /**
     * Первичная запись пользователя в БД
     */
    public function callback()
    {
        session_start();

        $url = 'https://login.microsoftonline.com/common/oauth2/v2.0/token';

        $data = array(
            'grant_type' => 'authorization_code',
            'code' => $_REQUEST['code'],
            'client_id' => 'cccbc182-4e70-41ed-a39a-5b658f2b0ee9',
            'client_secret' => '-UA7Q~O8AzrUWBEaR16C7mys3jPamdrcrE37U',
            //'redirect_uri' => 'http://localhost:8000/callback',
            'redirect_uri' => 'https://istudent-sfedu.herokuapp.com/callback',
        );

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = json_decode(file_get_contents($url, false, $context));

        // Create a stream
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Authorization: Bearer " . $result->access_token
            )
        );

        $context = stream_context_create($opts);

        $file = json_decode(file_get_contents('https://graph.microsoft.com/v1.0/me', false, $context));

        $isEmpty = Student::where('email', $file->userPrincipalName)->get()->isEmpty(); // Существует ли уже такой студент

        // Проверка, что почта sfedu-шная

        $match = substr($file->userPrincipalName, strlen($file->userPrincipalName)-8, 8) === "sfedu.ru"
        && $file->userPrincipalName[strlen($file->userPrincipalName)-9] === '@';

        if (!$match){
            return redirect(route("login", [
                'message' => "Войти можно только через @sfedu.ru"
            ]));
        }

        if ($isEmpty){
            DB::table('students')->insert([
                [
                    'name' => $file->displayName,
                    'email' => $file->userPrincipalName,
                    'password' => $file->userPrincipalName,
                    'groupId' => 0,
                    'isHeadman' => false
                ]
            ]);
        }
        else{
            return redirect(route("login", [
                'message' => "Такой пользователь уже есть"
            ]));
        }

        $studentFind = Student::where('email', $file->userPrincipalName)->first();

        Auth::login($studentFind, true);
        return redirect(route('loginAdd'));
    }
}
