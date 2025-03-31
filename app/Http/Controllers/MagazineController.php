<?php

namespace App\Http\Controllers;

use App\Models\MagazineVisits;
use App\Models\Training;
use App\Models\traning_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\IOFactory;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use function PHPUnit\Framework\isEmpty;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use http\Client\Response;

class MagazineController extends Controller
{
    public function index()
    {
        $trainig = Training::where('coach', Auth::user()->surname)->where('date', '>=', date('Y-m-d'))->orderBy('date', 'asc')->get();
        return view('coach.magazine', compact('trainig'));
    }

    public function show($id, $day, $profile)
    {
        $group = traning_group::query()->where('id', $id)->first();
        $users = json_decode($group->users_list);
        $data = [];
        $dayChekit = MagazineVisits::query()->where('date', $day)->get();
        foreach ($dayChekit as $chek) {
            foreach ($users as $user) {
                if ($chek->user == $user) {
                    if ($chek->on_visit) {
                        $data[$user] = 1;
                    } else {
                        $data[$user] = 0;
                    }
                }
            }
        }
        if (empty($data)) {
            foreach ($users as $user) {
                $data[$user] = 0;
            }
        }
        return view('coach.listMagazine', compact(['data', 'day', 'profile']));
    }

    public function noteUsers(Request $request)
    {
        if (isset($request->users)) {
            foreach ($request->users as $user => $value) {
                $user = str_replace('user_', '', trim($user));
                MagazineVisits::updateOrCreate([
                    'user' => $user,
                    'profile' => $request->profile,
                    'date' => $request->day,
                    'coach' => $request->coach
                ],
                    ['on_visit' => $value]);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Занимающиеся отмечены!'
        ]);
    }

    public function showMagazinesCoach()
    {
        $groups = Training::query()->where('group', '!=', '')->where('date', '<=', date('Y-m-d'))->orderBy('date', 'desc')->get()->groupBy('coach')->sortKeys();

        foreach ($groups as $coach => $training) {
            foreach ($training as $train) {
                if (isset($train->group)) {
                    $users = json_decode(traning_group::query()->where('id', $train->group)->value('users_list'));
                    $dayChekit = MagazineVisits::query()->where('date', $train->date)->get();
                    $data = [];
                    foreach ($dayChekit as $chek) {
                        foreach ($users as $user) {
                            if ($chek->user == $user) {
                                if ($chek->on_visit) {
                                    $data[$user] = 1;
                                } else {
                                    $data[$user] = 0;
                                }
                            }
                        }
                    }
                    $train->users = $data;
                }
            }
        }

        return view('coach.magazinesCoach', compact('groups'));
    }

    public function exelMagazine()
    {
        return view('exelMagazine');
    }

    public function exelMagazineCreate(Request $request)
    {
        $file = new \Rap2hpoutre\FastExcel\FastExcel();
        $users = [];
        $coach = '';
        $sport = '';
        $firstLine = true;

        $file->import($request->file, function ($line) use (&$users, &$coach, &$sport, &$firstLine) {
            if ($firstLine) {
                $coach = $line['инструктор'] ?? '';
                $sport = $line['спорт'] ?? '';
                $firstLine = false;
            }

            $row = [
                'sport' => $sport,
                'coach' => $coach,
                'user' => $line['группа'] ?? null,
                'br' => $line['др'] instanceof \DateTimeImmutable
                    ? $line['др']->format('Y-m-d')
                    : null,
                'start' => $line['зачислен'] instanceof \DateTimeImmutable
                    ? $line['зачислен']->format('Y-m-d')
                    : '2024-09-11',
                'end' => $line['отчислен'] instanceof \DateTimeImmutable
                    ? $line['отчислен']->format('Y-m-d')
                    : null,
            ];

            $users[] = $row;
        });
        //dump($users);

        $doc = new TemplateProcessor(storage_path('app/public/doc/proba.docx'));

        $day = self::getRandomWeekdaysDates(2024, 9);
        $count = count($day['days']);

        // Создаем таблицу, которую нужно вставить в шаблон
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $tableUser = $section->addTable([
            'borderSize' => 6,  // Толщина границы (0.75 pt)
            'borderColor' => '000000', // Черный цвет границы
            'cellMargin' => 50, // Отступ внутри ячеек
        ]);

        // Добавляем заголовки с границами
        $tableUser->addRow();
        $tableUser->addCell(200, ['borderSize' => 6, 'borderColor' => '000000'])->addText('');
        $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('ФИО');
        $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('Дата рождения');
        $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('Зачисление');
        $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('Отчисление');
        $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('Контактные данные');

        // Добавляем данные пользователей с границами
        foreach ($users as $key => $user) {
            $tableUser->addRow();
            $tableUser->addCell(200, ['borderSize' => 6, 'borderColor' => '000000'])->addText($key + 1);
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['user']);
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['br'] ?? '');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['start'] ?? '');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['end'] ?? '');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('3454353453453');
        }
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50,
        ];

        $tableVisit = $section->addTable($tableStyle);
        // Заголовок
        $tableVisit->addRow();
        $tableVisit->addCell(1000, ['vMerge' => 'restart', 'valign' => 'center'])->addText('№', ['bold' => true]);
        $tableVisit->addCell(4000, ['vMerge' => 'restart', 'valign' => 'center'])->addText('Фамилия, имя', ['bold' => true]);
        $tableVisit->addCell(8000, ['gridSpan' => $count, 'align' => 'center'])->addText('Сентябрь', ['bold' => true]);

        // Вторая строка (даты)
        $tableVisit->addRow();
        $tableVisit->addCell(1000, ['vMerge' => 'continue']); // Продолжение объединенной ячейки №
        $tableVisit->addCell(4000, ['vMerge' => 'continue']); // Продолжение объединенной ячейки Фамилия, имя
        foreach ($day['days'] as $date) {
            $tableVisit->addCell(1000)->addText($date);
        }

        //строки посещений
        $columnCounts = array_fill(1, $count, 0);

        foreach ($users as $key => $user) {
            $tableVisit->addRow();
            $tableVisit->addCell(1000)->addText($key + 1);
            $tableVisit->addCell(4000)->addText($user['user']);

            for ($i = 1; $i <= $count; $i++) {
                $arrayVisit = ['n', '+'];
                $key = array_rand($arrayVisit, 1);
                $value = $arrayVisit[$key]; // Получаем сам текст
                $tableVisit->addCell(1000)->addText($value); // Добавляем в таблицу

                if ($value == '+') { // Проверяем непосредственно текст
                    $columnCounts[$i]++;
                }
            }
        }

        $row = 15 - count($users);

        for ($r = 1; $r <= $row; $r++) {
            $tableVisit->addRow();
            $tableVisit->addCell(1000)->addText('');
            $tableVisit->addCell(4000)->addText('');

            for ($i = 1; $i <= $count; $i++) {
                $tableVisit->addCell(1000)->addText(''); // Добавляем в таблицу
            }
        }

        //Присутствовало
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('Присутствовало');
        foreach ($columnCounts as $value) {
            $tableVisit->addCell(1000)->addText($value);
        }

        //Продолжительность(час)
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('Продолжительность(час)');
        for ($i = 1; $i <= $count; $i++) {
            $tableVisit->addCell(1000)->addText(2); // Добавляем в таблицу
        }

        //В том числе
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('В том числе');
        for ($i = 1; $i <= $count; $i++) {
            $tableVisit->addCell(1000)->addText(''); // Добавляем в таблицу
        }

        //ОФП
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('ОФП');
        for ($i = 1; $i <= $count; $i++) {
            $tableVisit->addCell(1000)->addText(1); // Добавляем в таблицу
        }

        //СФП
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('СФП');
        for ($i = 1; $i <= $count; $i++) {
            $tableVisit->addCell(1000)->addText(1); // Добавляем в таблицу
        }

        //Теория
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('Теория');
        for ($i = 1; $i <= $count; $i++) {
            $tableVisit->addCell(1000)->addText(''); // Добавляем в таблицу
        }

        //Подпись инструктора
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('Подпись инструктора');
        for ($i = 1; $i <= $count; $i++) {
            $tableVisit->addCell(1000)->addText(''); // Добавляем в таблицу
        }

        // Заменяем метку ${tegs} в шаблоне на HTML-код таблицы
        $doc->setComplexBlock('user_table', $tableUser);
        $doc->setComplexBlock('user_visit', $tableVisit);
        $doc->setValue('coach', $coach);
        $doc->setValue('sport', $sport);

        // Сохраняем итоговый документ
        $filename = 'users_table_from_template.docx';
        $path = storage_path('app/public/success/' . $filename);
        $doc->saveAs($path);

        // Отправляем файл на скачивание
        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function getRandomWeekdaysDates($year, $month) {
        // Дни недели в числовом формате (1 — понедельник, 7 — воскресенье)
        $weekdays = range(1, 7);
        shuffle($weekdays);
        $randomWeekdays = array_slice($weekdays, 0, 3);

        $dates = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateString = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $weekday = date('N', strtotime($dateString));

            if (in_array($weekday, $randomWeekdays)) {
                $dates[$weekday][] = date('d', strtotime($dateString));
            }
        }

        // Объединяем все даты в один массив и сортируем
        $allDates = array_merge(...array_values($dates));
        sort($allDates);

        $data = ['day' => $randomWeekdays, 'days' => $allDates];

        return $data;
    }
}
