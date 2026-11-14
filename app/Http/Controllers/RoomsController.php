<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Training;
use App\Models\traning_group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $daysInMonth = $monthStart->daysInMonth;

        // Максимальное количество доступных часов за месяц
        $availableHours = 16 * $daysInMonth;

        // Все занятия за текущий месяц
        $trainings = Training::query()
            ->whereBetween('date', [
                $monthStart->toDateString(),
                $monthEnd->toDateString(),
            ])
            ->get()
            ->groupBy('slug_room');

        $rooms = Room::query()->get();

        foreach ($rooms as $room) {
            $roomTrainings = $trainings->get($room->slug, collect());

            // Группируем занятия по датам.
            // Важно: пересечения учитываются только в пределах одного дня.
            $trainingsByDate = $roomTrainings->groupBy(function ($training) {
                return Carbon::parse($training->date)->toDateString();
            });

            $occupiedMinutes = 0;

            foreach ($trainingsByDate as $dayTrainings) {
                $intervals = [];

                foreach ($dayTrainings as $training) {
                    if (!$training->time_start || !$training->time_end) {
                        continue;
                    }

                    $start = Carbon::parse($training->time_start);
                    $end = Carbon::parse($training->time_end);

                    $startMinute = max(
                        $start->hour * 60 + $start->minute,
                        7 * 60
                    );

                    $endMinute = min(
                        $end->hour * 60 + $end->minute,
                        23 * 60
                    );

                    if ($endMinute > $startMinute) {
                        $intervals[] = [
                            'start' => $startMinute,
                            'end' => $endMinute,
                        ];
                    }
                }

                // Сортируем занятия по времени начала
                usort($intervals, function ($a, $b) {
                    return $a['start'] <=> $b['start'];
                });

                // Объединяем пересекающиеся интервалы
                $currentStart = null;
                $currentEnd = null;

                foreach ($intervals as $interval) {
                    if ($currentStart === null) {
                        $currentStart = $interval['start'];
                        $currentEnd = $interval['end'];
                        continue;
                    }

                    if ($interval['start'] <= $currentEnd) {
                        $currentEnd = max(
                            $currentEnd,
                            $interval['end']
                        );
                    } else {
                        $occupiedMinutes += $currentEnd - $currentStart;

                        $currentStart = $interval['start'];
                        $currentEnd = $interval['end'];
                    }
                }

                // Добавляем последний интервал дня
                if ($currentStart !== null) {
                    $occupiedMinutes += $currentEnd - $currentStart;
                }
            }

            // Переводим минуты в часы
            $occupiedHours = $occupiedMinutes / 60;

            $room->occupied_hours = round($occupiedHours, 2);
            $room->available_hours = $availableHours;

            // Процент фактической загрузки
            $room->load_percent = $availableHours > 0
                ? round(($occupiedHours / $availableHours) * 100, 1)
                : 0;
        }

        return view('Main.rooms', compact(
            'rooms',
            'monthStart',
            'monthEnd'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->roomName);
        Room::create([
            'title' => $request->roomName,
            'slug' =>Str::slug($request->roomName)
        ]);
        return redirect()->route('rooms.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room['room'] = Room::where('slug', $id)->first();
        $info = self::infoTraining($id);
        if (isset($info) && !empty($info)){
            $room['info'] = $info;
        }
        $room['day'] = ['2024-10-05', '2024-10-06'];
        return view('Main.roomProfile', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Room::find($id)->delete();
        return redirect()->route('rooms.index');
    }

    public function nextWeek(Request $request)
    {
        dd($request->all());
    }

    public function infoTraining($id){
        $training = Training::where('slug_room', $id)/*->where('date', '>=', $to)->where('date' , '<=', $from)*/->get();
        $list = [];
        foreach ($training as $train){
            $row['coach'] = $train->coach;
            if ($train->quarter == 1){
                $row['quarter'] = 'весь зал';
            } elseif ($train->quarter == 2){
                $row['quarter'] = '2/4';
            } else {
                $row['quarter'] = '1/4';
            }
            $row['comment'] = $train->comment;
            $row['profile'] = $train->profile;
            $row['time'] = $train->time;
            $row['date'] = $train->date;
            $list[]= $row;
        }
        return $list;
    }

    public function showTable(Request $request){
        $trainings = Training::where('slug_room', $request->id)->get();
        $list = [];
        foreach ($trainings as $training){
            $group = traning_group::query()->where('id', $training->group)->value('group_num');
            $row['id'] = $training->id;
            $row['name'] = $training->coach;
            $row['coach'] = $training->coach;
            $row['profile'] = $training->profile;
            $row['comment'] = $training->comment;
            $row['start'] = $training->date . 'T' . $training->time_start . ':00';
            $row['end'] = $training->date . 'T' . $training->time_end . ':00';
            $row['quarter'] = self::colorEvent($training->quarter);
            $row['quarter_id'] = $training->quarter;
            $row['body'] = '<strong>Начало тренировки:</strong> ' . $training->time_start . '<br>' .
            '<strong>Конец тренировки:</strong> ' . $training->time_end . '<br>' .
                '<strong>Инструктор:</strong> ' . $training->coach . '<br>'.
                '<strong>Группа:</strong> ' . $group . '<br>'.
            '<strong>Комментарий:</strong> ' . self::getOrganisation($training->quarter) . ' ' . $training->comment;
            $list[] = $row;
        }
        return $list;
    }
    public function colorEvent($data){
        switch ($data){
            case 4:
                return '#9379d9';
            case 2:
                return '';
            case 1:
                return '#d5b42c';
            case 5:
                return '#1cc6e1';
            case 6:
                return '#c71ce1';
        }
    }

    public function getOrganisation($data)
    {
        switch ($data){
            case 4:
                return 'Цс';
            case 2:
                return 'Цас';
            case 1:
                return 'Сшор';
            case 5:
                return 'Платные услуги';
            case 6:
                return 'Мероприятия';
        }
    }
    public function showVault(Request $request)
    {
        // Проверяем дату из GET-запроса
        $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        // Если дата не передана — используем сегодня
        $selectedDate = $request->filled('date')
            ? Carbon::createFromFormat('Y-m-d', $request->date)->startOfDay()
            : Carbon::today();

        $rooms = Room::query()->get();

        // Получаем тренировки именно на выбранную дату
        $trainings = Training::query()
            ->where('date', $selectedDate->toDateString())
            ->get()
            ->groupBy('slug_room');

        $schedule = [];

        $start = 7 * 60;  // 07:00
        $end = 23 * 60;   // 23:00
        $step = 15;

        foreach ($rooms as $room) {
            $roomTrainings = $trainings->get($room->slug, collect());

            // Подготавливаем интервалы тренировок
            $intervals = $roomTrainings
                ->filter(fn ($training) => $training->time_start && $training->time_end)
                ->map(function ($training) {
                    $startTime = Carbon::parse($training->time_start);
                    $endTime = Carbon::parse($training->time_end);

                    return [
                        'start' => $startTime->hour * 60 + $startTime->minute,
                        'end' => $endTime->hour * 60 + $endTime->minute,

                        'group' => $training->group ?? '',
                        'profile' => $training->profile ?? '',
                        'coach' => $training->coach ?? 'Не указан',
                        'comment' => $training->comment ?? '',
                        'time_start' => $training->time_start,
                        'time_end' => $training->time_end,
                    ];
                })
                ->values();

            $slots = [];

            for ($minute = $start; $minute < $end; $minute += $step) {
                $slotEnd = $minute + $step;

                // Ищем тренировки, пересекающиеся с текущей ячейкой
                $matchingTrainings = $intervals->filter(function ($interval) use ($minute, $slotEnd) {
                    return $interval['start'] < $slotEnd
                        && $interval['end'] > $minute;
                });

                $time = sprintf(
                    '%02d:%02d',
                    intdiv($minute, 60),
                    $minute % 60
                );

                $slots[$time] = [
                    'busy' => $matchingTrainings->isNotEmpty(),

                    'trainings' => $matchingTrainings->map(function ($training) {
                        return [
                            'group' => $training['group'],
                            'profile' => $training['profile'],
                            'coach' => $training['coach'],
                            'comment' => $training['comment'],
                            'time_start' => $training['time_start'],
                            'time_end' => $training['time_end'],
                        ];
                    })->values()->all(),
                ];
            }

            $schedule[] = [
                'title' => $room->title,
                'slots' => $slots,
            ];
        }

        return view('Main.roomsVault', compact(
            'schedule',
            'selectedDate'
        ));
    }
}
