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
        $user = freeFloatingModel::all();

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function createUser(Request $request)
    {
        if (isset($request->swPhone)){
            $phone = trim($request->swPhone);
            $phone = str_replace(' ', '', $phone);
            $onesim = substr($phone, 0, 1);
            if ($onesim == '+'){
                $phone = substr($phone, 1);
            } elseif ($onesim == 8){
                $phone = '7' . substr($phone, 1);
            }
            if(mb_strlen($phone) != 11){
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
        $swDat = date('d-m-Y',strtotime($request->swDat));
        // Получаем человеческое название
        $swNozName = $nozologe[$swNoz] ?? 'Не указано';
        //dd($phone, $swDat, $swNoz, $swName);

        freeFloatingModel::create([
            'fio' =>$swName,
            'phone' =>$phone,
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
            ->get()
            ->groupBy(function ($item) {
                return date('Y-m-d', strtotime($item->date));
            });
        return view('swimmingRecording', compact('trening'));
    }
}
