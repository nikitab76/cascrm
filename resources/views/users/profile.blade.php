@extends('Main.Layouts.sidebar')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{$user->surname . ' ' . $user->name}}</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">


            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user shadow">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">{{$user->name . ' ' . $user->surname}}</h3>
                    <h5 class="widget-user-desc">{{$user->job_title}}</h5>
                </div>
                <div class="widget-user-image">
                    <img class="img-circle elevation-2" src="{{asset("assets/profile/img/user1-128x128.jpg")}}"
                         alt="User Avatar">
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Роль</h5>
                                <span class="description-text">{{$user->role}}</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">Должность</h5>
                                <span class="description-text">{{$user->job_title}}</span>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                @if($user->role != 'user')
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">PRODUCTS</span>
                                @else
                                    <h5 class="description-header">Справка</h5>
                                    <span class="description-text">PRODUCTS</span>
                                @endif
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.widget-user -->
            <!-- Default box -->
            <div class="col-md-12">
                @if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
                    <div class="row" id="saveButton" style="display: none">
                        <div class="alert alert-default-warning" role="alert">
                            <span id="alertText"></span>
                            <button type="button" class="btn btn-primary ml-5" style="color: white" onclick="eddUser()">
                                сохранить
                            </button>
                        </div>
                    </div>
                    <div class="alert alert-success" role="alert" id="successEdd"
                         style="display: none"></div>
                @endif
                <div class="card">
                    <ul class="nav nav-tabs" id="user_info" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home-tab-pane" type="button" role="tab"
                                    aria-controls="home-tab-pane" aria-selected="true">Общее
                            </button>
                        </li>
                        <li class="nav-item ml-1" role="presentation">
                            <button class="nav-link " id="profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile-tab-pane" type="button" role="tab"
                                    aria-controls="profile-tab-pane" aria-selected="false">Документы
                            </button>
                        </li>
                        @if(\Illuminate\Support\Facades\Auth::user()->id === $user->id)
                            <li class="nav-item ml-1" role="presentation">
                                <button class="nav-link " id="safety-tab" data-bs-toggle="tab"
                                        data-bs-target="#safety-tab-pane" type="button" role="tab"
                                        aria-controls="safety-tab-pane" aria-selected="false">Авторизация
                                </button>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
                             aria-labelledby="home-tab" tabindex="0">
                            <div class="p-3">
                                <h3>Общее</h3>
                                <div class="content" id="profile">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                {{--@dump($user)--}}
                                                <input id="user_id" value="{{$user->id}}" style="display: none">
                                                <label for="surname">Фамилия</label>
                                                <input type="text" class="form-control" id="surname" name="surname"
                                                       value="{{$user->surname}}" autocomplete="off">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label for="name">Имя</label>
                                                        <input type="text" class="form-control" id="name" name="name"
                                                               value="{{$user->name}}" autocomplete="off">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="second_name">Отчество</label>
                                                        <input type="text" class="form-control" id="second_name"
                                                               name="second_name" value="{{$user->second_name}}"
                                                               autocomplete="off">
                                                    </div>
                                                </div>
                                                <label for="job_title">Должность</label>
                                                {{--<input type="text" class="form-control" id="job_title" name="job_title" placeholder="">--}}
                                                <select class="form-control" name="job_title" id="job_title">
                                                    <option selected
                                                            value="{{$user->job_title}}">{{$user->job_title}}</option>
                                                    @foreach(\App\Models\Job_title::all() as $job)
                                                        <option value="{{$job->name}}">{{$job->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                @if($user->role != 'user')
                                                    <div class="row">
                                                        <label for="phone">Телефон</label>
                                                        <input type="tel" class="form-control" id="user_phone"
                                                               pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                                               name="user_phone" autocomplete="off"
                                                               value="{{$user->phone}}">
                                                        <label for="speciality">Направление</label>
                                                        <input type="text" class="form-control" id="speciality"
                                                               name="speciality">
                                                    </div>
                                                @else
                                                    <label for="user_coach">Инструктор</label>
                                                    <input type="text" class="form-control" id="user_coach"
                                                           name="user_coach" autocomplete="off"
                                                           value="{{\App\Models\Users::where('id', $user->coach)->value('surname')}}">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label for="representative">Представитель</label>
                                                            <input type="text" class="form-control" id="representative"
                                                                   name="representative"
                                                                   value="{{$user->representative}}">
                                                        </div>
                                                        <div class="col-6">
                                                            <label for="phone">Телефон</label>
                                                            <input type="tel" class="form-control" id="user_phone"
                                                                   pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                                                   name="user_phone" autocomplete="off"
                                                                   value="{{$user->phone}}">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label for="nosology">Нозология</label>
                                                            <input type="text" class="form-control" id="nosology"
                                                                   name="nosology" value="{{$user->nosology}}">
                                                        </div>
                                                        <div class="col-6">
                                                            <label for="medical_certificate">Справка</label>
                                                            <input type="text" class="form-control"
                                                                   id="medical_certificate"
                                                                   name="medical_certificate"
                                                                   value="{{$user->medical_certificate}}"
                                                                   autocomplete="off">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--документы--}}
                        <div class="tab-pane fade show" id="profile-tab-pane" role="tabpanel"
                             aria-labelledby="profile-tab" tabindex="0">
                            <div class="p-3">
                                <h3>Документы</h3>
                                <div class="content">
                                    <div class="form-group">
                                        <h1>Различные документы</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--безопасность--}}
                        @if(\Illuminate\Support\Facades\Auth::user()->id === $user->id)
                            <div class="tab-pane fade show" id="safety-tab-pane" role="tabpanel"
                                 aria-labelledby="safety-tab" tabindex="0">
                                <div class="p-3">
                                    <div class="content">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label for="userLogin">Login</label>
                                                        <input type="text" class="form-control" id="userLogin"
                                                               name="userLogin" autocomplete="off"
                                                               value="{{$user->login}}" disabled>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <label for="userPassword">Password</label>
                                                                <input type="password" class="form-control"
                                                                       id="userPassword" name="userPassword"
                                                                       value="{{$user->password}}">
                                                            </div>
                                                            <div class="col-6 d-flex">
                                                                <button type="button" class="btn btn-warning mt-auto"
                                                                        data-toggle="modal"
                                                                        data-target="#addPassword">
                                                                    изменить
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal fade" id="addPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form role="form" method="post" action="">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Изменить пароль</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="alert alert-danger" role="alert" id="error"
                                             style="display: none"></div>
                                        <div class="alert alert-success" role="alert" id="success"
                                             style="display: none"></div>
                                        <label for="oldPassword">Старый пароль</label>
                                        <input type="password" class="form-control" id="oldPassword" name="oldPassword"
                                               autocomplete="off">
                                        <label for="newPassword">Новый пароль</label>
                                        <input type="password" class="form-control" id="newPassword" name="newPassword"
                                               autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" onclick="update()" class="btn btn-primary">Изменить</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $('#profile').on('change', 'input, select', function () {
                        const fieldName = $(this).attr('name') || $(this).attr('id');
                        const fieldValue = $(this).val();
                        const message = `Изменение в поле "${fieldName}": новое значение - "${fieldValue}"`;

                        $('#alertText').attr('name', fieldName);
                        $('#alertText').attr('data-val', fieldValue);
                        // Устанавливаем текст в div
                        $('#alertText').text(message);

                        // Показываем кнопку
                        $('#saveButton').show();
                    });
                });

                function update() {
                    var data = {
                        'user': {{$user->id}},
                        'oldPassword': $('#oldPassword').val(),
                        'newPassword': $('#newPassword').val(),
                    }
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('password.update') }}',
                        dataType: 'json',
                        data: data,
                        success: function (data) {
                            if (data.success) {
                                $('#error').hide();
                                $('#success').show();
                                $('#success').text(data.message);
                                window.location.reload();
                            } else {
                                $('#success').hide();
                                $('#error').show()
                                $('#error').text(data.message)
                            }
                        },
                    });
                }

                function eddUser() {
                    const user = $('#user_id').val();
                    const name = $('#alertText').attr('name');
                    const val = $('#alertText').attr('data-val');
                    console.log(name, val)
                    var data = {
                        [name]: val,
                        'user': user
                    }

                    $.ajax({ // инициализациям ajax запрос
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST', // отправляем в POST формате, можно GET
                        url: '{{route('edd.users')}}', // путь дo обработчика
                        dataType: 'json', // ответ ждём в json формате
                        data: data, // данные для отправки
                        success: function (data) { // событие в случае удачного запроса
                            if (data.success) {
                                $('#saveButton').hide();
                                $('#successEdd').show();
                                $('#successEdd').text(data.message);
                                window.location.reload();
                            }
                        },
                    })
                }
            </script>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
