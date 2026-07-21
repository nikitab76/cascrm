<?php

namespace App\Http\Controllers;

use App\Models\MagazineVisits;
use App\Models\Training;
use App\Models\traning_group;
use App\Models\Users;
use App\Models\UsersDocument;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\ZipArchive;
use PhpOffice\PhpWord\SimpleType\Jc;
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
        $num = '';
        $firstLine = true;

        $file->import($request->file, function ($line) use (&$users, &$coach, &$sport, &$firstLine, &$num) {
            if ($firstLine) {
                $coach = $line['инструктор'] ?? '';
                $sport = $line['спорт'] ?? '';
                $num = $line['нрмер'] ?? $line['номер'];
                $firstLine = false;
            }

            $row = [
                'sport' => $sport,
                'coach' => $coach,
                'num' => $num,
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
        // Дни недели в числовом формате (1 — понедельник, 7 — воскресенье)
        $weekdays = range(1, 7);
        shuffle($weekdays);
        $randomWeekdays = array_slice($weekdays, 0, 3);

        //____________________________________________
        // Создаем таблицу, общие сведения
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $tableUser = $section->addTable([
            'borderSize' => 6,  // Толщина границы (0.75 pt)
            'borderColor' => '000000', // Черный цвет границы
            'cellMargin' => 50, // Отступ внутри ячеек
        ]);

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50,
        ];

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
            $fullName = trim($user['user']);
            if (preg_match('/^\S+/', $fullName, $matches)) {
                $lastName = $matches[0]; // Фамилия
            }
            $user_id = Users::query()->where('surname', $lastName)->value('id');
            $userPhone = '';
            if ($user_id) {
                $userPhone = UsersDocument::query()->where('user_id', $user_id)->value('representative_phone') ?? ' ';
            }
            $tableUser->addRow();
            $tableUser->addCell(200, ['borderSize' => 6, 'borderColor' => '000000'])->addText($key + 1);
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText(trim($user['user']));
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['br'] ?? '');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['start'] ?? '');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['end'] ?? '');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($userPhone);
        }

        //_____________________________________________________
        //создаем таблицу расписаний
        $tableSchedule = $section->addTable($tableStyle);

        // Заголовки колонок
        $headerRow = ['', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
        $tableSchedule->addRow();
        foreach ($headerRow as $header) {
            $tableSchedule->addCell(1500, ['valign' => 'center'])->addText($header, ['bold' => true], ['alignment' => Jc::CENTER]);
        }

        // Месяцы
        $months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        $times = ["10:00-11:00\n11:15-12:15", "11:00-12:00\n12:15-13:15", "12:00-13:00\n13:15-14:15", "13:00-14:00\n14:15-15:15", "14:00-15:00\n15:15-16:15", "15:00-16:00\n16:15-17:15",
            "16:00-17:00\n17:15-18:15", "17:00-18:00\n18:15-19:15", "18:00-19:00\n19:15-20:15"];
        $time = $times[array_rand($times)];

        // Заполняем таблицу
        foreach ($months as $month) {
            $tableSchedule->addRow();
            $tableSchedule->addCell(1500, ['valign' => 'center'])->addText($month, ['bold' => true]);

            // Заполняем ячейки пустыми значениями
            for ($i = 1; $i <= 7; $i++) {
                $cell = $tableSchedule->addCell(1500, ['valign' => 'center']);

                // Добавляем расписание только для сентября–декабря (как на картинке)
                if (in_array($month, ['Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']) && in_array($i, $randomWeekdays)) {
                    $cell->addText($time, ['size' => 10], ['alignment' => Jc::CENTER]);
                }
            }
        }

        $daySep = self::getRandomWeekdaysDates(2024, 9, $randomWeekdays);
        $dayOct = self::getRandomWeekdaysDates(2024, 10, $randomWeekdays);
        $dayNov = self::getRandomWeekdaysDates(2024, 11, $randomWeekdays);
        $dayDes = self::getRandomWeekdaysDates(2024, 12, $randomWeekdays);

        $tableVisitSept = self::createTableVisitDoc('Сентябрь', $daySep, $users);
        $tableVisitOct = self::createTableVisitDoc('Октябрь', $dayOct, $users);
        $tableVisitNov = self::createTableVisitDoc('Ноябрь', $dayNov, $users);
        $tableVisitDes = self::createTableVisitDoc('Декабрь', $dayDes, $users);


        //_____________________________________________________
        // Заменяем метку ${tegs} в шаблоне на HTML-код таблицы
        $doc->setComplexBlock('user_table', $tableUser);
        $doc->setComplexBlock('table_schedule', $tableSchedule);
        $doc->setComplexBlock('user_visit_sep', $tableVisitSept);
        $doc->setComplexBlock('user_visit_oct', $tableVisitOct);
        $doc->setComplexBlock('user_visit_nov', $tableVisitNov);
        $doc->setComplexBlock('user_visit_des', $tableVisitDes);
        $doc->setValue('coach', $coach);
        $doc->setValue('sport', $sport);
        $doc->setValue('num', $num);

        // Сохраняем итоговый документ

        $filename = "$coach $sport $num" . '.docx';
        $path = storage_path('app/public/success/' . $filename);
        $doc->saveAs($path);

        // Отправляем файл на скачивание
        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function execelMagazine(Request $request)
    {
        $file = new \Rap2hpoutre\FastExcel\FastExcel();
        $zip = new ZipArchive();
        $zipFileName = 'журналы_' . now()->format('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/public/success/' . $zipFileName);

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Не удалось создать архив');
        }
        $arrayUser = $file->import($request->file('file'));
        $result = [];
        foreach ($arrayUser as $row) {
            $instructor = trim($row['Инструктор'] ?? '');
            if (!$instructor) continue;
            $result[$instructor]['вид_спорта'] = $row['вид спорта'] ?? null;
            $result[$instructor]['расписание'] = [
                'пн' => $row['пн'] ?? '',
                'вт' => $row['вт'] ?? '',
                'ср' => $row['ср'] ?? '',
                'чт' => $row['чт'] ?? '',
                'пт' => $row['пт'] ?? '',
                'сб' => $row['сб'] ?? '',
                'вс' => $row['вс'] ?? '',
            ];

            $dateValue = $row['дата рож'] ?? '';
            if ($dateValue instanceof \DateTimeInterface) {
                $dateValue = $dateValue->format('d.m.Y');
            }
            $result[$instructor]['группа'][] = [
                'фио' => $row['ФИО'] ?? '',
                'дата_рождения' => $dateValue,
                'пол' => $row['Пол'] ?? '',
                'телефон' => $row['Телефон'] ?? '',
                'адрес' => $row['Адрес'] ?? '',
                'представитель' => $row['Представитель'] ?? '',
                'приказ' => $row['№ Приказ о зачислении'] ?? ''
            ];

        }
        //dd($result);
        foreach ($result as $coach => $magazine) {
            $doc = new TemplateProcessor(storage_path('app/public/doc/proba.docx'));
            //____________________________________________
            // Создаем таблицу, общие сведения
            $phpWord = new PhpWord();
            $tableStyle = [
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 50,
            ];
            $months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
            $section = $phpWord->addSection();
            $tableUser = $section->addTable($tableStyle);

            // Добавляем заголовки с границами
            $tableUser->addRow();
            $tableUser->addCell(200, ['borderSize' => 6, 'borderColor' => '000000'])->addText('');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('ФИО');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('Дата рождения');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('Зачисление');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('Отчисление');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('адрес');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('Контактные данные');
            $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText('представитель');

            // Добавляем данные пользователей с границами
            foreach ($magazine['группа'] as $user) {
                //dd($user);
                $fullName = trim($user['фио']);
                if (preg_match('/^\S+/', $fullName, $matches)) {
                    $lastName = $matches[0]; // Фамилия
                }


                $tableUser->addRow();
                $tableUser->addCell(200, ['borderSize' => 6, 'borderColor' => '000000'])->addText();
                $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText(trim($fullName));
                $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['дата_рождения']);
                $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['приказ'] ?? '');
                $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['end'] ?? '');
                $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['адрес'] ?? '');
                $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['телефон']);
                $tableUser->addCell(2000, ['borderSize' => 6, 'borderColor' => '000000'])->addText($user['представитель']);
            }
            // таблица расписаний
            $tableSchedule = $section->addTable($tableStyle);
            $tableSchedule->addRow();
            $tableSchedule->addCell(1500, ['valign' => 'center'])->addText('');
            $tableSchedule->addCell(1500, ['valign' => 'center'])->addText('Пн');
            $tableSchedule->addCell(1500, ['valign' => 'center'])->addText('Вт');
            $tableSchedule->addCell(1500, ['valign' => 'center'])->addText('Ср');
            $tableSchedule->addCell(1500, ['valign' => 'center'])->addText('Чт');
            $tableSchedule->addCell(1500, ['valign' => 'center'])->addText('Пт');
            $tableSchedule->addCell(1500, ['valign' => 'center'])->addText('Сб');
            $tableSchedule->addCell(1500, ['valign' => 'center'])->addText('Вс');
            foreach ($months as $month) {
                $tableSchedule->addRow();
                $tableSchedule->addCell(1500, ['valign' => 'center'])->addText($month);
                foreach ($magazine['расписание'] as $time) {
                    $tableSchedule->addCell(1500, ['valign' => 'center'])->addText($time);
                }
            }

            //таблица посещений
            $date = self::datesDay($magazine['расписание'], 2025);
            // Создаём контейнер TextRun
            //$table = self::createTableVisitDoc('январь', $date['январь'], $magazine['группа']);
            $sport = htmlspecialchars(substr($magazine['вид_спорта'], 0, -1));

            $doc->setValue('sport', $sport);
            $doc->setValue('coach', substr($coach, 0, -1));
            $doc->setComplexBlock('table_schedule', $tableSchedule);
            $doc->setComplexBlock('user_table', $tableUser);
            $doc->setComplexBlock('user_visit_jun', self::createTableVisitDoc('январь', $date['январь'], $magazine['группа']));
            $doc->setComplexBlock('user_visit_feb', self::createTableVisitDoc('февраль', $date['февраль'], $magazine['группа']));
            $doc->setComplexBlock('user_visit_much', self::createTableVisitDoc('март', $date['март'], $magazine['группа']));
            $doc->setComplexBlock('user_visit_apr', self::createTableVisitDoc('апрель', $date['апрель'], $magazine['группа']));
            $doc->setComplexBlock('user_visit_may', self::createTableVisitDoc('май', $date['май'], $magazine['группа']));
            $doc->setComplexBlock('user_visit_june', self::createTableVisitDoc('июнь', $date['июнь'], $magazine['группа']));

            $filename = "$coach " . $magazine['вид_спорта'] . '.docx';
            $path = storage_path('app/public/success/' . $filename);
            $doc->saveAs($path);
            $zip->addFile($path, $filename);

            // Отправляем файл на скачивание
            //return response()->download($path)->deleteFileAfterSend(true);
            //return true;

        }
        $zip->close();
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function datesDay(array $input, int $year = null)
    {
        $year = $year ?? date('Y');

        $monthMap = [
            'январь' => 1,
            'февраль' => 2,
            'март' => 3,
            'апрель' => 4,
            'май' => 5,
            'июнь' => 6,
        ];

        $weekdayMap = [
            'вс' => 0,
            'пн' => 1,
            'вт' => 2,
            'ср' => 3,
            'чт' => 4,
            'пт' => 5,
            'сб' => 6,
        ];

        $result = [];

        foreach ($monthMap as $monthName => $monthNumber) {
            // Определяем количество дней в месяце
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNumber, $year);

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $year, $monthNumber, $day));
                $dayOfWeek = (int)$date->format('w'); // 0 (вс) – 6 (сб)

                foreach ($weekdayMap as $weekdayStr => $weekdayNum) {
                    if (!empty($input[$weekdayStr]) && $dayOfWeek === $weekdayNum) {
                        // Разделяем строку на начало и конец
                        [$startStr, $endStr] = explode('-', $input[$weekdayStr]);

                        // Создаем объекты времени
                        $start = new DateTime($startStr);
                        $end = new DateTime($endStr);

                        // Если конец раньше начала (например, 23:00–01:00), считаем, что это на следующий день
                        if ($end < $start) {
                            $end->modify('+1 day');
                        }

                        // Получаем разницу
                        $interval = $start->diff($end);

                        // Считаем общее количество часов (включая дни)
                        $totalHours = $interval->days * 24 + $interval->h;

                        // Форматируем результат
                        $result[$monthName][] = [
                            'время' => $totalHours . ':' . str_pad($interval->i, 2, '0', STR_PAD_LEFT),
                            'дата' => $date->format('d'),
                        ];
                    }
                }
            }
        }

        return $result;

    }

    public function getRandomWeekdaysDates($year, $month, $daysWeek)
    {


        $dates = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateString = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $weekday = date('N', strtotime($dateString));

            if (in_array($weekday, $daysWeek)) {
                $dates[$weekday][] = date('d', strtotime($dateString));
            }
        }

        // Объединяем все даты в один массив и сортируем
        $allDates = array_merge(...array_values($dates));
        sort($allDates);

        $data = ['day' => $daysWeek, 'days' => $allDates];

        return $data;
    }

    public function createTableVisitDoc($month, $day, $users)
    {
        //dd($day);
        $count = count($day);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 50,
        ];

        $tableVisit = $section->addTable($tableStyle);
        // Заголовок
        $tableVisit->addRow();
        $tableVisit->addCell(1000, ['vMerge' => 'restart'])->addText('№', ['bold' => true]);
        $tableVisit->addCell(4000, ['vMerge' => 'restart'])->addText('Фамилия, имя', ['bold' => true]);
        $tableVisit->addCell(8000, ['gridSpan' => $count])->addText($month, ['bold' => true]);

        // Даты
        $tableVisit->addRow();
        $tableVisit->addCell(1000, ['vMerge' => 'continue']);
        $tableVisit->addCell(4000, ['vMerge' => 'continue']);
        foreach ($day as $date) {
            $tableVisit->addCell(1000)->addText($date['дата']);
        }

        $userCount = count($users);
        $visitTable = [];

        // 1. Инициализируем массив ячеек с 'н'
        foreach ($users as $userIndex => $user) {
            for ($i = 0; $i < $count; $i++) {
                $visitTable[$userIndex][$i] = 'н';
            }
        }

        // 2. Для каждой даты выбираем минимум 3 случайных посетителя и очищаем им ячейки
        for ($i = 0; $i < $count; $i++) {
            $selectedUsers = array_rand($users, min(3, $userCount));
            if (!is_array($selectedUsers)) {
                $selectedUsers = [$selectedUsers];
            }

            foreach ($selectedUsers as $userIdx) {
                $visitTable[$userIdx][$i] = ''; // посещение: оставить пусто
            }

            // Опционально: можно добавить больше случайных посещений
            foreach ($users as $userIdx => $user) {
                if (!in_array($userIdx, $selectedUsers)) {
                    if (rand(0, 1) === 1) {
                        $visitTable[$userIdx][$i] = ''; // тоже посещал
                    }
                }
            }
        }

        // 3. Заполняем таблицу
        $columnCounts = array_fill(1, $count, 0);
        foreach ($users as $userIndex => $user) {
            $tableVisit->addRow();
            $tableVisit->addCell(1000)->addText($userIndex + 1);
            $tableVisit->addCell(4000)->addText($user['фио']);

            for ($i = 0; $i < $count; $i++) {
                $value = $visitTable[$userIndex][$i];
                $tableVisit->addCell(1000)->addText($value);
                if ($value === '') {
                    $columnCounts[$i + 1]++;
                }
            }
        }

        // 4. Добавляем пустые строки до 15 человек
        $row = 15 - count($users);
        for ($r = 0; $r < $row; $r++) {
            $tableVisit->addRow();
            $tableVisit->addCell(1000)->addText('');
            $tableVisit->addCell(4000)->addText('');
            for ($i = 0; $i < $count; $i++) {
                $tableVisit->addCell(1000)->addText('');
            }
        }

        // 5. Итоговые строки
        // Присутствовало
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('Присутствовало');
        foreach ($columnCounts as $val) {
            $tableVisit->addCell(1000)->addText($val);
        }

        // Продолжительность
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('Продолжительность(час)');
        foreach ($day as $date) {
            $tableVisit->addCell(1000)->addText($date['время']);
        }

        // В том числе
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('В том числе');
        for ($i = 0; $i < $count; $i++) {
            $tableVisit->addCell(1000)->addText('');
        }

        /*// ОФП
        // Разделяем строку на часы и минуты
        [$hours, $minutes] = explode(':', $date['время']);

        // Переводим всё во время в минутах
        $totalMinutes = $hours * 60 + $minutes;

        // Делим
        $dividedMinutes = (int) floor($totalMinutes / 2);

        // Получаем обратно часы и минуты
        $newHours = intdiv($dividedMinutes, 60);
        $newMinutes = $dividedMinutes % 60;*/

        // Форматируем строку
        //$timeOfp = $newHours . ':' . str_pad($newMinutes, 2, '0', STR_PAD_LEFT);
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('ОФП');
        foreach ($day as $date) {
            if (!empty($date['время']) && strpos($date['время'], ':') !== false) {
                [$hours, $minutes] = explode(':', $date['время']);
                $totalMinutes = ((int)$hours) * 60 + (int)$minutes;
                $dividedMinutes = (int) floor($totalMinutes / 2);
                $newHours = intdiv($dividedMinutes, 60);
                $newMinutes = $dividedMinutes % 60;
                $timeOfp = $newHours . ':' . str_pad($newMinutes, 2, '0', STR_PAD_LEFT);
            } else {
                $timeOfp = ''; // если формат неправильный или пустой
            }

            $tableVisit->addCell(1000)->addText($timeOfp);
        }

        // СФП
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('СФП');
        foreach ($day as $date) {
            if (!empty($date['время']) && strpos($date['время'], ':') !== false) {
                [$hours, $minutes] = explode(':', $date['время']);
                $totalMinutes = ((int)$hours) * 60 + (int)$minutes;
                $dividedMinutes = (int) floor($totalMinutes / 2);
                $newHours = intdiv($dividedMinutes, 60);
                $newMinutes = $dividedMinutes % 60;
                $timeOfp = $newHours . ':' . str_pad($newMinutes, 2, '0', STR_PAD_LEFT);
            } else {
                $timeOfp = ''; // если формат неправильный или пустой
            }

            $tableVisit->addCell(1000)->addText($timeOfp);
        }

        // Теория
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('Теория');
        for ($i = 0; $i < $count; $i++) {
            $tableVisit->addCell(1000)->addText('');
        }

        // Подпись инструктора
        $tableVisit->addRow();
        $tableVisit->addCell(1000)->addText('');
        $tableVisit->addCell(4000)->addText('Подпись инструктора');
        for ($i = 0; $i < $count; $i++) {
            $tableVisit->addCell(1000)->addText('');
        }

        return $tableVisit;
    }
}
