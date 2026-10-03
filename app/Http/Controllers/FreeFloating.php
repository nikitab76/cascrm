<?php

namespace App\Http\Controllers;

use App\Models\freeFloatingModel;
use App\Models\Training;
use Illuminate\Http\Request;

class FreeFloating extends Controller
{
    public function index()
    {
        //dd('index работает');
        return view('freeFloating');
    }

    public function getUser()
    {
        $user = freeFloatingModel::all()
            ->unique('fio')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function createUser(Request $request)
    {
        if (isset($request->swPhone)) {
            $phone = trim($request->swPhone);
            $phone = str_replace(' ', '', $phone);
            $onesim = substr($phone, 0, 1);
            if ($onesim == '+') {
                $phone = substr($phone, 1);
            } elseif ($onesim == 8) {
                $phone = '7' . substr($phone, 1);
            }
            if (mb_strlen($phone) != 11) {
                return null;
            }
        }

        $nozologe = [
            'ovz' => 'ОВЗ, Общая',
            'lin' => 'ЛИН',
            'poda' => 'ПОДА',
            'sluh' => 'Слух',
            'zrenie' => 'Зрение',
        ];

        $swName = $request->swName;
        $swNoz = $request->swNoz;
        $swDat = date('d-m-Y', strtotime($request->swDat));
        // Получаем человеческое название
        $swNozName = $nozologe[$swNoz] ?? 'Не указано';
        //dd($phone, $swDat, $swNoz, $swName);

        freeFloatingModel::create([
            'fio' => $swName,
            'phone' => $phone,
            'nozologe' => $swNozName,
            'date_spravka' => $swDat
        ]);

        return view('freeFloating');
    }

    public function showUserPage()
    {
        $trening = Training::query()
            ->where('profile', '=', 'Свободное плавание')
            ->whereDate('date', '>=', today())
            ->orderBy('date', 'asc')
            ->orderBy('time_start', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return date('Y-m-d', strtotime($item->date));
            });
        return view('swimmingRecording', compact('trening'));
    }

    public function saveUserFreeSwimming(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'training_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'dr' => ['required', 'date'],
            'noz' => ['required', 'in:ovz,lin,poda,slykh,zrenie'],
            'phone' => ['required', 'string'],
            'mail' => ['nullable', 'email'],
            'personal_data_consent' => ['required', 'accepted'],
        ], [
            'training_id.required' => 'Не указана тренировка.',
            'training_id.integer' => 'Некорректный идентификатор тренировки.',

            'name.required' => 'Укажите ФИО участника.',
            'name.string' => 'ФИО указано некорректно.',
            'name.max' => 'ФИО не должно превышать 255 символов.',

            'dr.required' => 'Укажите дату рождения.',
            'dr.date' => 'Укажите корректную дату рождения.',

            'noz.required' => 'Выберите нозологическую группу.',
            'noz.in' => 'Выберите корректную нозологическую группу.',

            'phone.required' => 'Укажите номер телефона.',

            'mail.email' => 'Укажите корректный адрес электронной почты.',

            'personal_data_consent.required' => 'Необходимо согласие на обработку персональных данных.',
            'personal_data_consent.accepted' => 'Необходимо согласие на обработку персональных данных.',
        ]);

        $param = $request->all();

        if (isset($param['phone'])) {
            $phone = trim($param['phone']);
            $phone = str_replace(' ', '', $phone);
            $onesim = substr($phone, 0, 1);
            if ($onesim == '+') {
                $phone = substr($phone, 1);
            } elseif ($onesim == 8) {
                $phone = '7' . substr($phone, 1);
            }
            if (mb_strlen($phone) != 11) {
                return back()
                    ->withErrors(['phone' => 'Номер телефона должен содержать 11 цифр.'])
                    ->withInput();
            }
        }

        $nozologe = [
            'ovz' => 'ОВЗ',
            'lin' => 'ЛИН',
            'poda' => 'ПОДА',
            'slykh' => 'Слух',
            'zrenie' => 'Зрение',
        ];
        $swNozName = $nozologe[$param['noz']] ?? 'Не указано';

        $maxUser = Training::query()->where('id', '=', $param['training_id'])->value('max_user');
        $countUser = freeFloatingModel::query() ->where('training_id', $param['training_id'])->count();
        if ($countUser <= $maxUser){
            freeFloatingModel::create([
                'fio' => $param['name'],
                'birthday' => $param['dr'],
                'phone' => $phone,
                'nozologe' => $swNozName,
                'mail' => $param['mail'],
                'training_id' => $param['training_id'],
                'date_spravka' => '01-01-1900',
                'personal_data_consent' => $param['personal_data_consent']
            ]);
        } else {
            return back()->with('error', 'В данной группе нет мест, запишитесь на другое занятие');
        }


        return back()->with('success', 'Вы успешно записались!');
    }

    public function showAdminUsersPage()
    {
        $trening = Training::query()
            ->where('profile', 'Свободное плавание')
            ->whereDate('date', '>=', today())
            ->with('freeFloatings')
            ->orderBy('date', 'asc')
            ->orderBy('time_start', 'asc')
            ->get()
            ->groupBy(function ($item) {
            return date('Y-m-d', strtotime($item->date));
        });
        return view('swimmingRecordingAdmin', compact('trening'));
    }
}
