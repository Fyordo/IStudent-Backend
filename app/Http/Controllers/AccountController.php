<?php

namespace App\Http\Controllers;

use App\Models\Classes\GroupClass;
use App\Models\Classes\StudentClass;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

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

            if ($studentFind && $studentFind["password"] == $request->input('password')) {
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
    public function add(Request $request, $message = "")
    {
        if ($request->isMethod('post')) {
            $validateFileds = $request->validate([
                'groupId' => 'required',
                'password' => 'required|confirmed',
            ]);

            // Проверка, есть ли у группы староста
            $group = Group::where('id', (integer)$request->input('groupId'))->first();
            if ($group['headmanId'] != null && !($request->input('isHeadman') == null)) {
                return redirect(route(
                    "loginAdd",
                    [
                        'message' => "У этой группы уже есть староста"
                    ]
                ));
            }

            Student::where('id', Auth::id())->update([
                'groupId' => (integer)$request->input('groupId'),
                'password' => $request->input('password'),
                'isHeadman' => !($request->input('isHeadman') == null)
            ]);

            $studentFind = Student::where('email', Auth::user()["email"])->first();

            if (!($request->input('isHeadman') == null)) {
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

        foreach ($groupsDB as $group) {
            array_push($groups, new GroupClass($group));
        }

        return view("account.add", [
            'student' => StudentClass::getStudent(Auth::user()),
            'groups' => $groups,
            'message' => $message
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
        $callback = env("APP_URL") . "/callback";
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

        $url = "https://login.microsoftonline.com/" . $tenant . "/oauth2/v2.0/authorize?" . http_build_query($parameters);

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
            'redirect_uri' => env("APP_URL") . '/callback',
        );

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $result = json_decode(file_get_contents($url, false, $context));
        $access_token = $result->access_token;

        // Подключаем GraphMicrosoft API

        $graph = new Graph();
        $graph->setAccessToken($access_token);
        $user = $graph->createRequest("GET", "/me")
            ->setReturnType(Model\User::class)
            ->execute();

        $email = $user->getUserPrincipalName();
        $fio = $user->getDisplayName();

        $photo = $graph->createRequest("GET", "/me/photo/\$value")->execute();
        $photo = base64_encode($photo->getRawBody());

        $isEmpty = Student::where('email', $email)->get()->isEmpty(); // Существует ли уже такой студент

        // Проверка, что почта sfedu-шная

        $match = substr($email, strlen($email) - 8, 8) === "sfedu.ru"
            && $email[strlen($email) - 9] === '@';

        if (!$match) {
            return redirect(route("login", [
                'message' => "Войти можно только через @sfedu.ru"
            ]));
        }

        if ($isEmpty) {
            DB::table('students')->insert([
                [
                    'name' => $fio,
                    'email' => $email,
                    'password' => $email,
                    'groupId' => 0,
                    'isHeadman' => false,
                    'photo' => $photo
                ]
            ]);
        } else {
            return redirect(route("login", [
                'message' => "Такой пользователь уже есть"
            ]));
        }

        $studentFind = Student::where('email', $email)->first();

        Auth::login($studentFind, true);
        return redirect(route('loginAdd'));
    }
}
