<?php

namespace App\Http\Controllers;

use App\Models\Job_title;
use App\Models\Room;
use App\Models\Training;
use App\Models\traning_group;
use App\Models\User;
use App\Models\Users;
use App\Models\UsersDocument;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class testcontroller extends Controller
{
    public function index()
    {

        // Получаем список всех таблиц
        // Замените 'users' на нужное имя таблицы
        $data = DB::table('users')->get();

        // Экспортируем данные в Excel
        return (new FastExcel($data))->download('users.xlsx');


        exit;
        $start = date('Y-m-d', strtotime('01.10.2024'));
        $end = date('Y-m-d', strtotime('31.10.2024'));

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
        $trs = Training::where('coach', 'Броня')
            ->where('date', '>=', $start) // Используем строковые даты
            ->where('date', '<=', $end)  // Используем строковые даты
            ->get();

        // Обрабатываем данные
        foreach ($dates as $day) {
            $data[$day] = false; // Инициализируем значение как '-'
        }

        foreach ($trs as $tr) {
            $trDate = $tr->date; // Убедитесь, что $tr->date — это строка формата 'Y-m-d'
            if (isset($data[$trDate])) {
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
                $row['time'] = $timeFormatted;
                $row['coach'] = $tr->coach;
                $row['room'] = $tr->slug_room;
                $data[$trDate] = $row; // Если дата совпадает, устанавливаем 'ok'
            }
        }


        dd($data);
        exit;
        $d = Job_title::getRole('Занимающийся');
        dd($d);
        $t = new RoomsController();
        dump($t->showTable());
    }

    public static function exel($file)
    {
        $user = new FastExcel();
        $user->import($file, function ($line) {
            //dd($line);
            $name = explode(" ", trim($line['ФИО занимающегося']));
            //dump($name);
            if (isset($name[1])) {
                $user = Users::create([
                    'name' => $name[1],
                    'surname' => $name[0] ?? null,
                    'second_name' => $name[2] ?? null,
                    'job_title' => 'Занимающийся',
                    'phone' => null,
                    'login' => null,
                    'password' => null,
                    'role' => Job_title::getRole('Занимающийся')
                ]);
                $user_dok = UsersDocument::create([
                    'user_id' => $user->id,
                    'user_birth' => $line['дата рождения'] instanceof \DateTimeImmutable
                        ? $line['дата рождения']->format('Y-m-d')
                        : null,
                    'coach' => null,
                    'medical_certificate' => $line['Справка 1144Н'] instanceof \DateTimeImmutable
                        ? $line['Справка 1144Н']->format('Y-m-d')
                        : null,
                    'representative' => $line['ФИО представителя'] ?? null,
                    'representative_phone' => $line['телефон'] ?? null,
                    'nosology' => $line['Группа нозологий'],
                ]);
                //dd($user, $user_dok);
            }
        });
        return true;
    }

    public static function excelAddUsers($file)
    {
        $user = new FastExcel();
        $user->import($file, function ($line) {
            $name = explode(" ", trim($line['ФИО занимающегося']));
            Users::where('id', $line['id'])->update(
            [
                'name' => $name[1],
                'surname' => $name[0] ?? null,
                'second_name' => $name[2] ?? null
            ]);
            UsersDocument::where('user_id', $line['id'])
                ->update(
                  [
                      'user_birth' => $line['дата рождения'] instanceof \DateTimeImmutable
                          ? $line['дата рождения']->format('Y-m-d')
                          : null,
                      'medical_certificate' => $line['Справка 1144Н'] instanceof \DateTimeImmutable
                          ? $line['Справка 1144Н']->format('Y-m-d')
                          : null,
                      'representative' => $line['ФИО представителя'] ?? null,
                      'representative_phone' => $line['телефон'] ?? null,
                      'nosology' => $line['Группа нозологий'],
                  ]
                );

        });
        return true;
    }
}
