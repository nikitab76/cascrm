@extends('Main.Layouts.sidebar')
@section('content')
    <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Календарь</title>
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.css"/>
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
    <h1>Часы тренеров в неделю</h1>
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header" data-card-widget="collapse">
                <h3 class="card-title">Фильтр</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <meta name="csrf-token" content="{{ csrf_token()}}">
                        <div class="col">
                            <label for="coach">Тренер:</label>
                            <select name="coach" class="form-control" id="coach">
                                <option value="null"></option>
                                @foreach(\App\Models\Users::where('role', 'coach')->orderBy('surname', 'asc')->get() as $user)
                                    <option value="{{$user->id}}">{{$user->fullName()}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="col">
                            <label for="date_start">Дата с:</label>
                            <input type="date" id="date_start" name="date_start" class="form-control"
                                   value="{{date('Y-m-d', (strtotime('first day this week') - 1))}}">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="col">
                            <label for="date_end">Дата до:</label>
                            <input type="date" id="date_end" name="date_end" class="form-control"
                                   value="{{date('Y-m-d', (strtotime('last day of this week')))}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center mt-5">
                        <button type="button" class="btn btn-primary" style="color: white" onclick="tableRender()">
                            Показать
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-content margin-top-50" id="user_table_div">
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-body" id="tableDiv">
                    <div class="table-container">
                        <meta name="csrf-token" content="{{ csrf_token()}}">
                        <table width="100%" id="user_table"
                               class="table table-striped table-bordered table-hover dataTable dtr-inline word-break"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Вызываем функцию при загрузке страницы
    $(document).ready(function () {
        setCurrentWeekRange();
    })

    function setCurrentWeekRange() {
        const now = new Date(); // Текущая дата
        const dayOfWeek = now.getDay(); // День недели (0 - воскресенье, 1 - понедельник, и т.д.)

        // Вычисляем разницу до понедельника (1) и воскресенья (7)
        const diffToMonday = (dayOfWeek === 0 ? -6 : 1) - dayOfWeek;
        const diffToSunday = 7 - dayOfWeek;

        // Получаем начало и конец недели
        const monday = new Date(now);
        monday.setDate(now.getDate() + diffToMonday);

        const sunday = new Date(now);
        sunday.setDate(now.getDate() + diffToSunday);

        // Преобразуем в формат YYYY-MM-DD для input[type="date"]
        const formatDate = (date) => date.toISOString().split('T')[0];

        // Устанавливаем значения инпутов
        $('#date_start').val(formatDate(monday));
        $('#date_end').val(formatDate(sunday));
    }

    function tableRender() {
        let data = {
            'coach': $('#coach').val(),
            'start': $('#date_start').val(),
            'end': $('#date_end').val(),

        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '{{ route('get.hour.coach') }}',
            dataType: 'json',
            data: data,
            success: function (data) {
                render(data)
            },
        });
    }
    let table;

    function render(data) {
        // Формируем столбцы
        let columns = [];
        let days = data['days'];

        let title = data.result['profile'];

        // Добавляем колонку для "Занятие"
        columns.push({
            title: 'Занятие',
            data: 'profile', // Свойство profile для отображения в первой колонке
            className: "cursor penalty column-100 text-align-center vertical-align-middle",
        });

        // Добавляем колонки для дней
        days.forEach(function (day) {
            columns.push({
                title: day,
                data: day, // Имя поля совпадает с названием дня
                className: "cursor column-100 text-align-center vertical-align-middle",
                width: '100',
                render: function (data) {
                    return data && data !== false ? data : '-'; // Если значение есть, отображаем его, иначе "-"
                },
            });
        });

        // Уничтожаем старую таблицу, если она существует
        if (table) {
            table.clear().destroy();
            $('#user_table').empty();
        }

        // Создаем новую таблицу
        table = $('#user_table').DataTable({
            "scrollX": true,
            "deferRender": true,
            "iDisplayLength": 25,
            "order": [[0, "asc"]],
            "language": {
                "url": "{{asset('assets/profile/plugins/datatables/ru.json')}}", // Локализация
            },
            "data": data.result, // Используем свойство "result", где массив данных
            "dom": "<'row padding-top-5 mt-1 padding-left-15 padding-right-15' <'col-md-12'B>><'row padding-left-15 padding-right-15'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row padding-left-15 padding-right-15'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
            "buttons": [
                {
                    extend: 'collection',
                    text: ' Сохранить',
                    className: 'btn red btn-outline fa fa-download',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            className: 'btn',
                            text: 'EXCEL',
                            title: data.result[0].coach,
                        },
                        {
                            extend: 'csvHtml5',
                            className: 'btn',
                            text: 'CSV',
                        },
                    ]
                },
                {
                    extend: 'copy',
                    className: 'btn purple btn-outline fa fa-copy',
                    text: ' Копировать',
                },
                {
                    extend: 'print',
                    className: 'btn blue btn-outline fa fa-print',
                    text: ' Печать',
                    exportOptions: {
                        stripHtml: false,
                    }
                },
                {
                    extend: 'colvis',
                    className: 'btn dark btn-outline fa fa-angle-down',
                    text: ' Выбрать колонки',
                },
            ],
            "columns": columns
        });
    }

</script>

</body>
</html>

@endsection
