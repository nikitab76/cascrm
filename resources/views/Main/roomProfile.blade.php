@extends('Main.Layouts.sidebar')

@section('content')
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.css"/>
    <!-- TUI Date Picker (обязательная зависимость) -->
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-date-picker/latest/tui-date-picker.min.css">
    <!-- TUI Time Picker (обязательная зависимость) -->
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-time-picker/latest/tui-time-picker.min.css">
    <style>
        #popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 20px;
            text-align: left;
        }
        #popup h3 {
            margin-top: 0;
        }
        #popup button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
        }
        #popup button:hover {
            background-color: #0056b3;
        }
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        #calendar {
            height: 800px;
        }

        .tui-full-calendar-popup {
            max-width: 400px; /* Максимальная ширина стандартного окна */
        }

        #eventModal {
            display: none; /* Скрыто по умолчанию */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid text-center">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>{{$room['room']->title}}</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="col-md-12">
                <!-- Widget: user widget style 1 -->
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header text-white"
                         style="background:url('{{asset('assets/profile/img/photo1.png')}}') center center;">
                        <h3 class="widget-user-username text-right">{{$room['room']->title}}</h3>
                    </div>
                    {{--<div class="widget-user-image">
                        <img class="img-circle" src="../dist/img/user3-128x128.jpg" alt="User Avatar">
                    </div>--}}
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">Длительность занятия</h5>
                                    <span class="description-text">45 минут</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">Одновременно групп</h5>
                                    <span class="description-text">4</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">Нужная инфа</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            </div>
            <!-- /.widget-user -->
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header" data-card-widget="collapse">
                        <h3 class="card-title">Фильтр расписания</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="col">
                                    <label for="coachFilter">Инструктор:</label>
                                    <input type="text" id="coachFilter" name="coachFilter" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="profileFilter">Направление:</label>
                                    <input type="text" id="profileFilter" name="profileFilter" class="form-control">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="col">
                                    <label for="dataToFilter">Дата с:</label>
                                    <input type="date" id="dataToFilter" name="dataToFilter" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="dataForFilter">Дата до:</label>
                                    <input type="date" id="dataForFilter" name="dataForFilter" class="form-control">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="col">
                                    <label for="timeToFilter">Время с:</label>
                                    <input type="time" id="timeToFilter" name="timeToFilter" class="form-control">
                                </div>
                                <div class="col">
                                    <label for="timeForFilter">Время до:</label>
                                    <input type="time" id="timeForFilter" name="timeForFilter" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center mt-5">
                                <div class="alert alert-primary" id="alertmess" style="display: none">
                                    Фильтр пока не работает
                                    <button type="button" class="btn btn-primary" style="color: white" onclick="hide()">
                                        X
                                    </button>
                                </div>
                                <button type="button" class="btn btn-primary" style="color: white" onclick="show()">
                                    Показать
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--<section class="content-header">--}}
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-success float-sm-right" data-toggle="modal"
                                data-target="#createClass">
                            Добавить занятие
                        </button>
                    </div>
                </div>
            </div>
            {{--</section>--}}

            <!-- Modal -->
            <div class="modal fade" id="createClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form role="form" method="post" action="{{route('training.create')}}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Добавить занятие</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="list-unstyled">
                                                @foreach($errors->all() as $error)
                                                    <li>{{$error}}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <input style="display: none" name="roomsSlug" value="{{{$room['room']->slug}}}">
                                        <label for="classCoach">Инструктор</label>
                                        <input type="text" class="form-control" id="classCoach" name="classCoach"
                                               placeholder="" value="{{old('classCoach')}}">
                                        <div class="pt-2">
                                            <label for="classProfile">Секция</label>
                                            <input type="text" class="form-control" id="classProfile"
                                                   name="classProfile"
                                                   placeholder="" value="{{old('classProfile')}}">
                                        </div>
                                        <div class="pt-2">
                                            <label for="classTime">Время начала</label>
                                            <input type="time" class="form-control" id="classTime" name="classTime"
                                                   placeholder="" value="{{old('classTime')}}">
                                        </div>
                                        <div class="pt-2">
                                            <label for="classTime">Время окончания</label>
                                            <input type="time" class="form-control" id="classTimeEnd"
                                                   name="classTimeEnd"
                                                   placeholder="" value="{{old('classTimeEnd')}}">

                                            <div class="pt-2">
                                                <label for="classDate">Дата</label>
                                                <input type="date" class="form-control" id="classDate" name="classDate"
                                                       placeholder="" value="{{old('classDate')}}">
                                            </div>
                                            <div class="pt-2">
                                                <div class="form-group">
                                                    <label for="classQuarter">Организация</label>
                                                    <select class="form-control" name="classQuarter" id="classQuarter">
                                                        <option selected value="4">Цс</option>
                                                        <option value="2">Цас</option>
                                                        <option value="1">Сшор</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="pt-2">
                                                <label for="classComment">Комментарий</label>
                                                <input type="text" class="form-control" id="classComment"
                                                       name="classComment"
                                                       placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            @if(isset($room['info']))

                <div class="col-md-12">
                    <div class="card">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                        data-bs-target="#home-tab-pane" type="button" role="tab"
                                        aria-controls="home-tab-pane" aria-selected="true">Таблица
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " id="profile-tab" data-bs-toggle="tab"
                                        data-bs-target="#profile-tab-pane" type="button" role="tab"
                                        aria-controls="profile-tab-pane" aria-selected="false">Канбар
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
                                 aria-labelledby="home-tab" tabindex="0">
                                <div class="p-3">
                                    <h3>Сводная таблица</h3>
                                    <div class="content">
                                        <div class="nav-buttons mb-2">
                                            <button id="prev-week" class="btn btn-outline-warning">← Предыдущая неделя</button>
                                            <button id="next-week" class="btn btn-outline-warning">Следующая неделя →</button>
                                            <button id="today" class="btn btn-outline-warning">Сегодня</button>
                                        </div>
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                                <div id="popup">
                                    <h3 id="popupTitle"></h3>
                                    <p><span id="popupComment"></span></p>
                                    <p>{{--<strong>Время:</strong> <span id="popupTime"></span></p>--}}
                                    <button id="closePopup">Закрыть</button>
                                </div>
                            </div>
                            {{--kanban--}}
                            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel"
                                 aria-labelledby="profile-tab"
                                 tabindex="0">
                                <div class="p-3 row">
                                    @foreach($room['day']  as $day)
                                        <div class="card card-row card-primary m-1">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    {{date('l' ,strtotime($day))}}
                                                </h3>
                                                <div class="card-tools">
                                                    <h6 class="btn btn-tool btn-link">{{date('d-m' ,strtotime($day))}}</h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @foreach($room['info'] as $trainig)
                                                    @if($trainig['date'] === $day)
                                                        <div class="card card-primary card-outline collapsed-card">
                                                            <div class="card-header accordion-item">
                                                                <h3 class="card-title" data-card-widget="collapse">
                                                                    <a href="javascript:;"
                                                                       style="color: black">{{$trainig['profile']}}</a>
                                                                </h3>
                                                                <div class="card-tools">
                                                                    <span class="btn btn-tool"
                                                                          data-card-widget="collapse">{{$trainig['time']}}</span>
                                                                    <button type="button" class="btn btn-tool"
                                                                            data-card-widget="collapse"><i
                                                                            class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="card-body" style="display: none;">
                                                                <div class="alert alert-light">
                                                                    <label>Тренер: {{$trainig['coach']}}</label>
                                                                </div>
                                                                <div class="alert alert-light">
                                                                    <label>Площадь: {{$trainig['quarter']}}</label>
                                                                </div>
                                                                <div class="alert alert-light">
                                                                    <label>Комментарий:</label><br>
                                                                    <label>{{$trainig['comment']}}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-light">
                                                            <label>В этот день нет тренировок</label>
                                                        </div>
                                                        @break
                                                    @endif
                                                @endforeach

                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <meta name="csrf-token" content="{{ csrf_token() }}">
        </section>
        @dump($room['room']->slug)
    </div>
    <script src="https://uicdn.toast.com/tui-date-picker/latest/tui-date-picker.min.js"></script>
    <script src="https://uicdn.toast.com/tui-time-picker/latest/tui-time-picker.min.js"></script>
    <script src="https://uicdn.toast.com/tui.code-snippet/latest/tui-code-snippet.min.js"></script>
    <script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function show() {
            $('#alertmess').show();
        }

        function hide() {
            $('#alertmess').hide();
        }
        $(document).ready(function (){
            showCalendar();
        })

        function showCalendar(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST', // отправляем в POST формате, можно GET
                url: '{{ route('rooms.calendar', ['id'=>$room['room']->slug])}}', // путь дo обработчика
                dataType: 'json', // ответ ждём в json формате
                data: '', // данные для отправки
                beforeSend: function(data) { // событие дo отправки запроса
                },
                success: function(data){ // событие в случае удачного запроса
                    renderCalendar(data)
                },
            })
        }

        function renderCalendar(data) {
            var calendar = new tui.Calendar('#calendar', {
                defaultView: 'week',
                taskView: false,
                scheduleView: ['time'],
                useDetailPopup: true,  // Включаем всплывающее окно
                useCreationPopup: true,  // Включаем создание событий через всплывающие окна
                useResizeHandle: true,   // Включаем изменение размера событий
                useDrag: true,           // Включаем перетаскивание событий
                week: {
                    startDayOfWeek: 1,
                    hourStart: 6,
                    hourEnd: 23,
                },
                template: {
                    time: function (schedule) {
                        var start = schedule.start;
                        var timeString = start.getHours().toString().padStart(2, '0') + ':' + start.getMinutes().toString().padStart(2, '0');
                        return timeString + ' ' + schedule.title;
                    }
                }
            });

            data.forEach(function (item) {
                calendar.createSchedules([{
                    id: item.id,
                    calendarId: '1',
                    title: item.profile,
                    category: 'time',
                    start: item.start,
                    end: item.end,
                    body:item.body,
                    bgColor:item.quarter,
                }]);
            });
            calendar.on('beforeUpdateSchedule', function(event) {
                var schedule = event.schedule;
                var changes = event.changes;

                // Создаем текст для отображения изменений
                var content = `
            <p><strong>Название события:</strong> ${schedule.title}</p>
            <p><strong>Новый период:</strong>${changes.start ? new Date(changes.start).toLocaleString() : new Date(schedule.start).toLocaleString()} - ${changes.end ? new Date(changes.end).toLocaleString() : new Date(schedule.end).toLocaleString()}</p>`;
                $('#popupTitle').text(schedule.title);
                $('#popupComment').html(content);

                $('#overlay').show();
                $('#popup').show();
                // Показать модальное окно с изменениями
                /*showModal(content);*/

                // Обновить событие в календаре
                calendar.updateSchedule(schedule.id, schedule.calendarId, changes);
            });

            // Обработчик для удаления события
            calendar.on('beforeDeleteSchedule', function(event) {
                var schedule = event.schedule;

                // Удаление события
                calendar.deleteSchedule(schedule.id, schedule.calendarId);
            });

            // Обработчик события для изменения стандартного всплывающего окна
            /*calendar.on('clickSchedule', function(event) {
                var schedule = event.schedule;

                // Заполнение данных в всплывающем окне
                $('#popupTitle').text(schedule.title);
                $('#popupComment').html(schedule.body);

                // Показать всплывающее окно
                $('#overlay').show();
                $('#popup').show();
            });*/

            $('#closePopup').click(function() {
                $('#popup').hide();
                $('#overlay').hide();
            });


            // Закрытие модального окна
            $('.close').click(function() {
                $('#eventModal').fadeOut();
            });

            // Закрытие модального окна при клике вне него
            $(window).click(function(event) {
                if ($(event.target).is('#eventModal')) {
                    $('#eventModal').fadeOut();
                }
            });
            // Функции для переключения между неделями
            document.getElementById('prev-week').addEventListener('click', function () {
                calendar.prev();  // Переход на предыдущую неделю
            });

            document.getElementById('next-week').addEventListener('click', function () {
                calendar.next();  // Переход на следующую неделю
            });

            document.getElementById('today').addEventListener('click', function () {
                calendar.today();  // Возврат на текущую неделю
            });
        }
    </script>
@endsection
