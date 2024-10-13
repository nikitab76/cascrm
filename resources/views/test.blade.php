@extends('Main.Layouts.sidebar')
@section('content')
    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Календарь</title>
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.css" />
    <style>
        #calendar {
            height: 800px;
            margin: 20px auto;
        }

        .content-wrapper {
            text-align: center;
        }

        .nav-buttons {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="content-wrapper">
    <h1>Календарь событий</h1>
    <div class="nav-buttons">
        <button id="prev-week">← Предыдущая неделя</button>
        <button id="next-week">Следующая неделя →</button>
        <button id="today">Сегодня</button>
    </div>
    <div id="calendar"></div> <!-- Контейнер для календаря -->
</div>

<!-- Подключение зависимостей -->
<script src="https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js"></script>
<script src="https://uicdn.toast.com/tui-calendar/v1.13.0/tui-calendar.min.js"></script>

<script>
    // Инициализация календаря
    var calendar = new tui.Calendar('#calendar', {
        defaultView: 'week',
        taskView: false,           // Отключаем представление задач
        scheduleView: ['time'],    // Отображаем события по времени
        useDetailPopup: true,      // Включаем всплывающие окна событий
        useCreationPopup: true,    // Включаем создание событий через всплывающие окна
        week: {
            startDayOfWeek: 1,      // Начало недели с понедельника
            hourStart: 6,           // Начало дня в 6:00
            hourEnd: 23,            // Конец дня в 23:00
            timegridLeft: {
                width: '60px',
                timeFormat: function(time) {
                    // Форматирование времени в 24-часовом формате (HH:mm)
                    return time.hour.toString().padStart(2, '0') + ':00';
                }
            },
            timegrid: {
                hourStart: 6,
                hourEnd: 23,
                hourTicks: 4, // Шаг по 15 минут
            },
            hourLabelFormat: 'H',
        },
        template: {
            time: function(schedule) {
                var start = schedule.start;
                var timeString = start.getHours().toString().padStart(2, '0') + ':' + start.getMinutes().toString().padStart(2, '0');
                return timeString + ' ' + schedule.title;
            }
        }
    });

    // Создаем тестовые события
    calendar.createSchedules([
        {
            id: '1',
            calendarId: '1',
            title: 'Встреча с клиентом',
            category: 'time',
            start: '2024-10-13T10:00:00',
            end: '2024-10-13T12:00:00'
        },
        {
            id: '2',
            calendarId: '2',
            title: 'Обед',
            category: 'time',
            start: '2024-10-13T13:00:00',
            end: '2024-10-13T14:00:00'
        },
        {
            id: '3',
            calendarId: '3',
            title: 'Совещание',
            category: 'time',
            start: '2024-10-13T15:00:00',
            end: '2024-10-13T16:30:00'
        }
    ]);

    // Функции для переключения между неделями
    document.getElementById('prev-week').addEventListener('click', function() {
        calendar.prev();  // Переход на предыдущую неделю
    });

    document.getElementById('next-week').addEventListener('click', function() {
        calendar.next();  // Переход на следующую неделю
    });

    document.getElementById('today').addEventListener('click', function() {
        calendar.today();  // Возврат на текущую неделю
    });
</script>

</body>
</html>

@endsection
