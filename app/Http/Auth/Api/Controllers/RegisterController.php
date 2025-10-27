<?php

namespace App\Http\Auth\Api\Controllers;

use App\Actions\Users\StoreUserAction;
use App\Http\Auth\Api\Requests\RegisterRequest;
use App\Models\User;
//use App\Support\EmailServices;
use Illuminate\Auth\Events\Registered;

final class RegisterController extends Controller
{
    /**
     *  @api {post} /api/register 01. Email: Реєстрація
     *  @apiVersion 1.0.0
     *  @apiName PostRegister
     *  @apiGroup Authorisation
     *
     *  @apiDescription Реєстрація нового користувача за допомогою Email.
     *  Після успішної реєстрації користувач отримає лист для підтвердження електронної пошти.<br>
     *
     *  У кожному запиті необхідно передавати аліас поточного домену:
     *  - Параметр запиту: `sHost=dropshop` **або** заголовок: `sHost: dropshop`
     *
     *  Додатково можна передавати локаль і кошик:
     *  - Локаль: `sLocale=uk` (Header: `sLocale: uk`)
     *  - ID кошика: `sCart=345` (Header: `sCart: 345`)
     *
     *  @apiBody {String} name Ім’я
     *  @apiBody {String} [lastname] Прізвище
     *  @apiBody {String} [middlename] По батькові
     *  @apiBody {String} email Email-адреса
     *  @apiBody {String} phone Номер телефону
     *  @apiBody {Date} [birthday] Дата народження (YYYY-MM-DD)
     *  @apiBody {String} password Пароль
     *  @apiBody {String} [password_confirmation] Підтвердження пароля
     *  @apiBody {Boolean=0,1} [accept] Чи прийнято умови використання
     *
     *  @apiParamExample {json} Body-Example:
     *  {
     *      "name": "Іван",
     *      "lastname": "Іванов",
     *      "middlename": "Іванович",
     *      "email": "ivan@example.com",
     *      "phone": "+380501234567",
     *      "birthday": "1990-01-01",
     *      "password": "secret123",
     *      "password_confirmation": "secret123",
     *      "accept": 1
     *  }
     */
    public function register(RegisterRequest $request)
    {
        /** @var User $user */
        $user = StoreUserAction::run($request->validated());

        //event(new Registered($user));

        // VISIT
//        \App\Actions\VisitAction::run('register', $user);

//        if (\Domain::getOpt('users.need_verified_email')) {
//            return response()->json([
//                'message' => trans('auth.register_need_verify_email'),
//                'mail_service_url' => EmailServices::findByEmail($request->email, 'url'),
//                'state' => 'unverified',
//            ]  /*+ $this->authResource($user)*/);
//        }

        return response()->json([
            'message' => trans('auth.register'),
            'state' => 'active',
        ] + $this->authResource($user));
    }
}
