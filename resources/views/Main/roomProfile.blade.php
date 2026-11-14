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

        #calendar-print-wrapper {
            width: 100%; /* или задайте конкретную ширину */
            height: 900px; /* задайте желаемую высоту */
            overflow: hidden; /* отключаем скролл */
        }

        /* Пример настройки самого календаря */
        #calendar {
            width: 100%;
            height: 100%;
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
                        <button type="button"
                                id="addTrainingButton"
                                class="btn btn-success float-sm-right"
                                data-toggle="modal"
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
                        <form id="trainingForm" role="form" method="post"
                              action="{{ route('training.create') }}">
                            @csrf
                            <input type="hidden" name="training_id" id="editingTrainingId">
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
                                        {{--<input type="text" class="form-control" id="classCoach" name="classCoach"
                                               placeholder="" value="{{old('classCoach')}}">--}}
                                        <select class="form-control" name="classCoach" id="classCoach">
                                            @foreach(\App\Models\Users::where('role', 'coach')->get() as $user)
                                                <option value="{{$user->surname}}">{{$user->fullName()}}</option>
                                            @endforeach
                                        </select>
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
                                                <label for="classDateFor">Растянуть до:</label>
                                                <input type="date" class="form-control" id="classDateFor" name="classDateFor"
                                                       placeholder="" value="{{old('classDate')}}">
                                            </div>

                                            <div class="pt-2">
                                                <div class="form-group">
                                                    <label for="classQuarter">Организация</label>
                                                    <select class="form-control" name="classQuarter" id="classQuarter">
                                                        <option selected value="4">Цс</option>
                                                        <option value="2">Цас</option>
                                                        <option value="1">Сшор</option>
                                                        <option value="5">Платные услуги</option>
                                                        <option value="6">Мероприятия</option>
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
                {{--@dump("кнопка 'Перенести на неделю' пока что не работает")
                @dump($room['room']->slug)--}}

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
                                            {{--<button id="nextweek" class="btn btn-outline-secondary">Перенести на неделю</button>--}}
                                            <button id="export-pdf" class="btn btn-outline-success">🖨 Печать</button>

                                        </div>
                                        <div id="calendar-print-wrapper">
                                            <h3 class="widget-user-username text-center">{{$room['room']->title}}</h3>
                                            <div id="calendar"></div>
                                        </div>
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
    </div>
    <script src="https://uicdn.toast.com/tui-date-picker/latest/tui-date-picker.min.js"></script>
    <script src="https://uicdn.toast.com/tui-time-picker/latest/tui-time-picker.min.js"></script>
    <script src="https://uicdn.toast.com/tui.code-snippet/latest/tui-code-snippet.min.js"></script>
    <script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        $('#createClass').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);

            // Открытие для создания нового занятия
            if (button.length && button.data('mode') === 'create') {
                const form = document.getElementById('trainingForm');

                form.reset();

                $('#editingTrainingId').val('');

                form.action = "{{ route('training.create') }}";

                $('#exampleModalLabel').text('Добавить занятие');
            }
        });
        // Глобальные данные календаря
        var calendarInstance = null;
        var trainingData = {};
        var selectedTraining = null;

        // Функции фильтра
        function show() {
            $('#alertmess').show();
        }

        function hide() {
            $('#alertmess').hide();
        }

        $(document).ready(function () {
            showCalendar();

            // Возвращаем исходный заголовок модалки при закрытии
            $('#createClass').on('hidden.bs.modal', function () {
                $('#createClass .modal-title').text('Добавить занятие');
                $('#editingTrainingId').val('');
            });
        });


        // Загрузка календаря
        function showCalendar() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '{{ route('rooms.calendar', ['id'=>$room['room']->slug]) }}',
                dataType: 'json',
                data: '',
                success: function (data) {
                    if (data) {
                        renderCalendar(data);
                    }
                },
                error: function (xhr) {
                    console.error('Ошибка загрузки календаря:', xhr.responseText);
                }
            });
        }


        // Удаление занятия
        function deleteTrening(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('training.delete') }}',
                dataType: 'json',
                data: {
                    id: id
                },
                success: function (data) {
                    renderCalendar(data);
                },
                error: function (xhr) {
                    console.error('Ошибка удаления:', xhr.responseText);
                }
            });
        }


        // Изменение времени занятия при перетаскивании
        function editTrening(id, date, timeStart, timeEnd) {
            $.ajax({
                type: 'POST',
                url: '{{ route('training.edit') }}',
                dataType: 'json',
                data: {
                    id: id,
                    date: date,
                    start: timeStart,
                    end: timeEnd
                },
                success: function (data) {
                    renderCalendar(data);
                },
                error: function (xhr) {
                    console.error('Ошибка изменения занятия:', xhr.responseText);
                }
            });
        }


        // Форматирование даты YYYY-MM-DD
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        function formatTime(date) {
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');

            return `${hours}:${minutes}`;
        }

        // Проверка наличия значения в select
        function setSelectValue(selector, value) {
            if (value === undefined || value === null || value === '') {
                return;
            }

            var select = $(selector);
            var exists = select.find('option').filter(function () {
                return String(this.value) === String(value);
            }).length > 0;

            if (exists) {
                select.val(String(value));
            }
        }

        function setCoachSelect(coach) {
            const select = document.getElementById('classCoach');

            if (!select || !coach) {
                return;
            }

            const value = String(coach).trim().toLowerCase();

            // Ищем по фамилии (value)
            let option = Array.from(select.options).find(item =>
                item.value.trim().toLowerCase() === value
            );

            // Если не нашли, ищем по полному имени
            if (!option) {
                option = Array.from(select.options).find(item =>
                    item.textContent.trim().toLowerCase() === value
                );
            }

            if (option) {
                select.value = option.value;
                $('#classCoach').trigger('change');
            } else {
                console.warn('Тренер не найден:', coach);
            }
        }

        // Открытие существующей модалки с заполненными данными
        function openTrainingEditModal(training) {
            if (!training) {
                alert('Не удалось получить данные занятия');
                return;
            }

            const start = new Date(training.start);
            const end = new Date(training.end);

            const form = document.getElementById('trainingForm');

            form.action = "{{ route('training.update') }}";

            $('#editingTrainingId').val(training.id);

            setCoachSelect(training.coach);

            $('#classProfile').val(training.profile || '');

            $('#classTime').val(formatTime(start));
            $('#classTimeEnd').val(formatTime(end));

            $('#classDate').val(formatDate(start));
            $('#classDateFor').val(formatDate(end));

            // Выбираем организацию по числовому коду
            $('#classQuarter').val(String(training.quarter_id)).trigger('change');

            $('#classComment').val(training.comment || '');

            $('#exampleModalLabel').text('Редактировать занятие');

            $('#createClass').modal('show');

        }


        // При открытии окна через кнопку «Добавить занятие»
        $('#addTrainingButton').on('click', function () {

            const form = document.getElementById('trainingForm');

            // Полностью сбрасываем поля формы
            form.reset();

            // Очищаем скрытый ID занятия
            $('#editingTrainingId').val('');

            // Убираем возможные ошибки валидации
            $('#trainingForm').find('.is-invalid').removeClass('is-invalid');
            $('#trainingForm').find('.invalid-feedback').remove();

            // Возвращаем action создания
            form.action = "{{ route('training.create') }}";

            // Убираем метод PUT/PATCH, если он был добавлен
            $(form).find('input[name="_method"]').remove();

            // Сбрасываем заголовок
            $('#exampleModalLabel').text('Добавить занятие');

        });


        // Перехват кнопки Edit стандартного popup TUI Calendar
        function editPopupCapture(e) {

            var editButton = e.target.closest(
                '.tui-full-calendar-popup-edit'
            );

            if (!editButton) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            if (!selectedTraining) {
                console.error('Данные выбранного занятия не найдены');
                alert('Не удалось получить данные занятия');
                return;
            }

            openTrainingEditModal(selectedTraining);
        }


        // Основная функция построения календаря
        function renderCalendar(data) {

            // Удаляем предыдущий экземпляр календаря
            if (calendarInstance) {
                calendarInstance.destroy();
            }

            trainingData = {};
            selectedTraining = null;

            var calendar = new tui.Calendar('#calendar', {
                defaultView: 'week',
                taskView: false,
                scheduleView: ['time'],

                useDetailPopup: true,
                useCreationPopup: true,
                useResizeHandle: true,
                useDrag: true,
                isReadOnly: false,

                week: {
                    startDayOfWeek: 1,
                    hourStart: 8,
                    hourEnd: 23,
                    dayNames: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']
                },

                template: {
                    time: function (schedule) {
                        const start = schedule.start;

                        const hours = start.getHours()
                            .toString().padStart(2, '0');

                        const minutes = start.getMinutes()
                            .toString().padStart(2, '0');

                        return `${hours}:${minutes} ${schedule.title}`;
                    },

                    timegridDisplayPrimaryTime(hour) {
                        return hour.hour + ':00';
                    }
                }
            });

            calendarInstance = calendar;


            // Загружаем занятия
            data.forEach(function (item) {

                // Сохраняем полные данные занятия
                trainingData[String(item.id)] = item;

                calendar.createSchedules([{
                    id: item.id,
                    calendarId: '1',
                    title: item.profile,
                    category: 'time',
                    start: item.start,
                    end: item.end,
                    body: item.body,
                    bgColor: item.quarter,
                    isReadOnly: false
                }]);
            });


            // Запоминаем выбранное занятие при открытии
            calendar.on('clickSchedule', function (event) {
                const schedule = event.schedule;

                selectedTraining = trainingData[String(schedule.id)];

                console.log('Выбрано занятие:', selectedTraining);
            });


            // Устанавливаем обработчик Edit в capture-фазе
            document.removeEventListener(
                'click',
                editPopupCapture,
                true
            );

            document.addEventListener(
                'click',
                editPopupCapture,
                true
            );


            // Изменение занятия при перемещении или изменении размера
            calendar.on('beforeUpdateSchedule', function (event) {

                var schedule = event.schedule;
                var changes = event.changes;

                var data = changes.start
                    ? new Date(changes.start).toLocaleDateString()
                    : new Date(schedule.start).toLocaleDateString();

                var timeStart = changes.start
                    ? new Date(changes.start).toLocaleTimeString()
                    : new Date(schedule.start).toLocaleTimeString();

                var timeEnd = changes.end
                    ? new Date(changes.end).toLocaleTimeString()
                    : new Date(schedule.end).toLocaleTimeString();

                editTrening(
                    schedule.id,
                    data,
                    timeStart,
                    timeEnd
                );

                // Обновляем локальные данные
                calendar.updateSchedule(
                    schedule.id,
                    schedule.calendarId,
                    changes
                );
            });


            // Удаление занятия
            calendar.on('beforeDeleteSchedule', function (event) {

                var schedule = event.schedule;

                if (confirm('Удалить тренировку ' + schedule.title + '?')) {

                    deleteTrening(schedule.id);

                    calendar.deleteSchedule(
                        schedule.id,
                        schedule.calendarId
                    );
                }
            });


            // Закрытие дополнительного popup
            $('#closePopup').off('click').on('click', function () {
                $('#popup').hide();
                $('#overlay').hide();
            });


            // Закрытие модального окна
            $('.close').off('click').on('click', function () {
                $('#eventModal').fadeOut();
            });


            $(window).off('click.eventModal').on('click.eventModal', function (event) {
                if ($(event.target).is('#eventModal')) {
                    $('#eventModal').fadeOut();
                }
            });


            // Переключение недель
            document.getElementById('prev-week')
                .addEventListener('click', function () {
                    calendar.prev();
                });

            document.getElementById('next-week')
                .addEventListener('click', function () {
                    calendar.next();
                });

            document.getElementById('today')
                .addEventListener('click', function () {
                    calendar.today();
                });


            // Перенос на следующую неделю
            var nextWeekButton = document.getElementById('nextweek');

            if (nextWeekButton) {
                nextWeekButton.addEventListener('click', function () {

                    const date = calendar.getDate();

                    const jsDate = typeof date.toDate === 'function'
                        ? date.toDate()
                        : new Date(date);

                    nextWeek(
                        @json($room['room']->slug),
                        jsDate.toISOString()
                    );
                });
            }


            // Экспорт календаря в PDF
            document.getElementById('export-pdf')
                .addEventListener('click', async function () {

                    const calendarElement = document.getElementById(
                        'calendar-print-wrapper'
                    );

                    const canvas = await html2canvas(calendarElement, {
                        scale: 2,
                        useCORS: true
                    });

                    const imgData = canvas.toDataURL('image/png');

                    const { jsPDF } = window.jspdf;

                    const pdf = new jsPDF({
                        orientation: 'landscape',
                        unit: 'mm',
                        format: 'a4'
                    });

                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();

                    const imgWidth = canvas.width;
                    const imgHeight = canvas.height;

                    const ratio = Math.min(
                        pageWidth / imgWidth,
                        pageHeight / imgHeight
                    );

                    const width = imgWidth * ratio;
                    const height = imgHeight * ratio;

                    const x = (pageWidth - width) / 2;
                    const y = (pageHeight - height) / 2;

                    pdf.addImage(
                        imgData,
                        'PNG',
                        x,
                        y,
                        width,
                        height
                    );

                    const blob = pdf.output('blob');
                    const blobURL = URL.createObjectURL(blob);

                    const printWindow = window.open(blobURL);

                    if (printWindow) {
                        printWindow.onload = function () {
                            printWindow.focus();
                            printWindow.print();
                        };
                    }
                });
        }


        // Перенос занятий на следующую неделю
        function nextWeek(slug, currentDate) {

            $.ajax({
                type: 'POST',
                url: '{{ route("nextWeek") }}',
                dataType: 'json',

                data: {
                    slug: slug,
                    currentDate: currentDate,
                    _token: '{{ csrf_token() }}'
                },

                success: function (data) {
                    renderCalendar(data);
                },

                error: function (xhr) {
                    console.error(xhr.responseText);
                }
            });
        }
        function getOrganisationId(name) {
            const organisations = {
                'Сшор': 1,
                'Цас': 2,
                'Цс': 4,
                'Платные услуги': 5,
                'Мероприятия': 6
            };

            return organisations[name] ?? '';
        }
    </script>
@endsection
