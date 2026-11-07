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

        #user_table tfoot th {
            font-weight: bold !important;
            background: #e8e8e8 !important;
            text-align: center;
            white-space: nowrap;
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
                        <meta name="csrf-token" content="{{ csrf_token() }}">

                        <table width="100%" id="user_table"
                               class="table table-striped table-bordered table-hover dataTable dtr-inline word-break">

                            <thead>
                            <tr></tr>
                            </thead>

                            <tbody></tbody>

                            <tfoot>
                            <tr>
                                <th>Итого:</th>
                            </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function timeToMinutes(time) {
        if (!time || time === false || time === '-') {
            return 0;
        }

        const parts = time.split(':');

        return (parseInt(parts[0]) * 60) + parseInt(parts[1]);
    }

    function minutesToTime(minutes) {
        const hours = Math.floor(minutes / 60);
        const remainingMinutes = minutes % 60;

        return String(hours).padStart(2, '0') + ':' +
            String(remainingMinutes).padStart(2, '0');
    }

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

        let columns = [];
        let days = data.days;

        // Первая колонка
        columns.push({
            title: 'Занятие',
            data: 'profile',
            className: "cursor penalty column-100 text-align-center vertical-align-middle",
        });

        // Колонки с датами
        days.forEach(function (day) {
            columns.push({
                title: day,
                data: day,
                className: "cursor column-100 text-align-center vertical-align-middle",
                width: '100',
                render: function (value) {
                    return value && value !== false ? value : '-';
                }
            });
        });

        // Уничтожаем старую таблицу
        if ($.fn.DataTable.isDataTable('#user_table')) {
            $('#user_table').DataTable().clear().destroy();
        }

        // Восстанавливаем структуру таблицы
        $('#user_table').html(`
        <thead>
            <tr></tr>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr></tr>
        </tfoot>
    `);

        // Формируем footer под все столбцы
        let footerHtml = '<th><strong>Итого:</strong></th>';

        for (let i = 1; i < columns.length; i++) {
            footerHtml += '<th>00:00</th>';
        }

        $('#user_table tfoot tr').html(footerHtml);

        // Если нет данных, не обращаемся к data.result[0]
        let coachName = data.result.length
            ? data.result[0].coach
            : 'Тренер';

        // Создаем DataTable
        table = $('#user_table').DataTable({

            scrollX: true,
            deferRender: true,
            iDisplayLength: 25,
            order: [[0, "asc"]],

            language: {
                url: "{{asset('assets/profile/plugins/datatables/ru.json')}}",
            },

            data: data.result,

            dom: "<'row padding-top-5 mt-1 padding-left-15 padding-right-15' <'col-md-12'B>><'row padding-left-15 padding-right-15'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row padding-left-15 padding-right-15'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",

            buttons: [
                {
                    extend: 'collection',
                    text: ' Сохранить',
                    className: 'btn red btn-outline fa fa-download',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            className: 'btn',
                            text: 'EXCEL',
                            title: coachName,
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
                },
                {
                    extend: 'colvis',
                    className: 'btn dark btn-outline fa fa-angle-down',
                    text: ' Выбрать колонки',
                },
            ],

            columns: columns,

            // Подсчет итогов
            footerCallback: function (row, data, start, end, display) {

                let api = this.api();

                let totalAll = 0;

                for (let column = 1; column < api.columns().count(); column++) {

                    let totalMinutes = 0;

                    // Берем все отфильтрованные строки
                    api.column(column, {
                        search: 'applied'
                    }).data().each(function (value) {

                        totalMinutes += timeToMinutes(value);

                    });

                    totalAll += totalMinutes;

                    // Записываем сумму за день
                    $(api.column(column).footer()).html(
                        '<strong>' + minutesToTime(totalMinutes) + '</strong>'
                    );
                }

                // Общая сумма за период
                $(api.column(0).footer()).html(
                    '<strong>Итого: ' + minutesToTime(totalAll) + '</strong>'
                );
            }

        });

    }

</script>

</body>
</html>

@endsection
