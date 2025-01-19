<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Training;
use App\Models\traning_group;
use App\Models\Users;
use App\Models\UsersDocument;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class trainingController extends Controller
{
    public function create(Request $request)
    {
        Training::create([
            'slug_room' => $request->roomsSlug,
            'coach' => $request->classCoach,
            'profile' => $request->classProfile,
            'date' => $request->classDate,
            'time_start' => $request->classTime,
            'time_end' => $request->classTimeEnd,
            'quarter' => $request->classQuarter,
            'comment' => $request->classComment ? $request->classComment : '-',
        ]);
        return redirect()->route('rooms.show', ['room' => $request->roomsSlug]);
    }

    public function createTrainingCoach(Request $request)
    {
        $flag = true;
        if (isset($request->class)) {
            $class = Room::where('id', $request->class)->value('slug');
        }
        if (!isset($request->classProfile)) {
            $flag = false;
            return response()->json([
                'success' => false,
                'error' => 'заполните секцию'
            ]);
            //$profile = $request->classProfile;
        }
        if (!isset($request->classDate)) {
            $flag = false;
            return response()->json([
                'success' => false,
                'error' => 'заполните дату'
            ]);
        }
        if (!isset($request->classTime)) {
            $flag = false;
            return response()->json([
                'success' => false,
                'error' => 'заполните время начала'
            ]);
        }
        if (!isset($request->classTimeEnd)) {
            $flag = false;
            return response()->json([
                'success' => false,
                'error' => 'заполните время окончания'
            ]);
        }
        if (!isset($request->group)) {
            $flag = false;
            return response()->json([
                'success' => false,
                'error' => 'заполните группу'
            ]);
        }
        if ($flag) {
            $coach = Users::where('id', $request->coach)->value('surname');
            Training::create([
                'slug_room' => $class,
                'coach' => $coach,
                'group' => $request->group,
                'profile' => $request->classProfile,
                'date' => $request->classDate,
                'time_start' => $request->classTime,
                'time_end' => $request->classTimeEnd,
                'quarter' => 1,
                'comment' => $request->classComment ? $request->classComment : '-',
            ]);
            return response()->json([
                'success' => true,
                'error' => 'Тренировка добавлена'
            ]);
        }
    }

    public function edit(Request $request)
    {
        $traning = Training::where('id', $request->id)->first();
        $traning->date = date('Y-m-d', strtotime($request->date));
        $traning->time_start = date('H:i', strtotime($request->start));
        $traning->time_end = date('H:i', strtotime($request->end));
        $traning->save();
    }

    public function delete(Request $request)
    {
        Training::where('id', $request->id)->delete();
    }

    public function trainingProfile()
    {
        $trainig = Training::where('coach', Auth::user()->surname)->where('date', '>=', date('Y-m-d'))->orderBy('date', 'asc')->get();
        return view('coach.treningList', compact('trainig'));
    }

    public function trainingList()
    {
        return view('traingListAll');
    }

    public function getCoachTrening()
    {
        $days = [
            'пн',
            'вт',
            'ср',
            'чт',
            'пт',
            'сб',
            'вс'
        ];
        $trainigs = Training::all();
        $data = [];
        foreach ($trainigs as $trainig) {
            $row['name'] = $trainig->coach;
            $row['profile'] = $trainig->profile;
            $row['room'] = \App\Models\Room::where('slug', $trainig->slug_room)->value('title');
            $dat = date('d-m-Y', strtotime($trainig->date));
            $dayWeek = $days[date('w', (strtotime($trainig->date)) - 1)];
            $row['data'] = date('Y-m-d', strtotime($dat));
            $row['day'] = $dayWeek;
            $row['start'] = $trainig->time_start;
            $row['end'] = $trainig->time_end;
            $data[] = $row;
        }
        return response()->json($data);

    }

    public function groupsIndex()
    {
        $trainig = traning_group::where('coach_id', Auth::user()->id)->get();
        return view('users.groups', compact('trainig'));
    }

    public function groupsCreate(Request $request)
    {
        $count = traning_group::where('coach_id', $request->coach)->count();
        if ($request->numGroup <= $count) {
            return response()->json([
                'success' => false,
                'message' => 'Номер группы не может быть меньше существующего!'
            ]);
        }
        foreach ($request->users as $user) {
            $user = Users::where('id', $user)->first();
            if ($doc = UsersDocument::where('user_id', $user->id)->first()) {
                //dd($doc);
                if (isset($doc->coach)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'У ' . $user->fullName() . ' уже есть тренер!'
                    ]);
                } else {
                    $doc->coach = $request->coach;
                    $doc->save();
                }
            }
        }
        //dd(11);
        traning_group::create([
            'coach_id' => $request->coach,
            'group_num' => $request->numGroup,
            'users_list' => json_encode($request->users)
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Группа успешно создана!'
        ]);
    }

    public function addTraing(Request $request)
    {
        //dd($request);
        Training::where('id', $request->traingId)->update([
            'time_start' => $request->classTimeStart,
            'time_end' => $request->classTimeEnd,
            'slug_room' => Room::where('id', $request->classAdd)->value('slug'),
        ]);
        return true;
    }

    public function getHourCoach(Request $request)
    {
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));
        $coach = Users::where('id', $request->coach)->first();

        // Создаем объекты даты
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $endDate->modify('+1 day'); // Добавляем 1 день, чтобы включить конечную дату

        // Устанавливаем интервал (1 день)
        $interval = new DateInterval('P1D');
        // Генерируем период
        $datePeriod = new DatePeriod($startDate, $interval, $endDate);

        // Преобразуем период в массив

        $dates = [];
        $data = [];

        // Преобразуем объекты DateTime в строки формата 'Y-m-d' для массива $dates
        foreach ($datePeriod as $date) {
            $day = $date->format('Y-m-d'); // Строковый формат даты
            $dates[] = $day;
        }

        // Получаем данные из базы
        $trs = Training::where('coach', $coach->surname)
            ->where('date', '>=', $start) // Используем строковые даты
            ->where('date', '<=', $end)  // Используем строковые даты
            ->get();

        // Обрабатываем данные

        foreach ($trs as $tr) {
            $row['coach'] = $tr->coach;
            $row['room'] = $tr->slug_room;
            $row['profile'] = $tr->profile;
            foreach ($dates as $day) {
                $row[$day] = false; // Инициализируем значение как false
                $trDate = $tr->date; // Убедитесь, что $tr->date — это строка формата 'Y-m-d'
                if ($trDate == $day) {
                    $time1 = new DateTime($tr->time_end);
                    $time2 = new DateTime($tr->time_start);
                    // Преобразуем в timestamp
                    $timestamp1 = $time1->getTimestamp();
                    $timestamp2 = $time2->getTimestamp();
                    $diffInSeconds = abs($timestamp1 - $timestamp2);
                    // Переводим в минуты
                    $minutes = $diffInSeconds / 60;
                    // Вычисляем часы и оставшиеся минуты
                    $hours = floor($minutes / 60);
                    $remainingMinutes = $minutes % 60;
                    // Форматируем результат
                    $timeFormatted = sprintf('%02d:%02d', $hours, $remainingMinutes);
                    $row[$day] = $timeFormatted;
                }

            }
            array_push($data, $row);
        }
//        $data[] = $row; // Если дата совпадает, устанавливаем 'ok'
        $answer = [
            'days' => $dates,
            'result' => $data,
        ];
        return response()->json($answer,
            200,
            ['Content-Type' => 'application/json; charset=utf-8'],
            JSON_UNESCAPED_UNICODE);
    }

    public function indexHourCoach()
    {
        return view('hourCoach');
    }
}
