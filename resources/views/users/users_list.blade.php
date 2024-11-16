@extends('Main.Layouts.sidebar')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Список пользователей</h1>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-success float-sm-right" data-toggle="modal"
                                data-target="#createModal">
                            Добавить пользователя
                        </button>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form role="form" method="post" action="{{route('users.create')}}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Добавить пользователя</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="surname">Фамилия</label>
                                    <input type="text" class="form-control" id="surname" name="surname" placeholder="Иванов" value="{{old('surname')}}" autocomplete="off">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="name">Имя</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Иван" value="{{old('name')}}" autocomplete="off">
                                        </div>
                                        <div class="col-6">
                                            <label for="second_name">Отчество</label>
                                            <input type="text" class="form-control" id="second_name" name="second_name" placeholder="Иванович" value="{{old('second_name')}}" autocomplete="off">
                                        </div>
                                    </div>
                                    <label for="phone">Телефон</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{old('phone')}}" pattern="(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?" autocomplete="off">
                                    <script>
                                        $(function(){
                                            $("#phone").mask("+7(999) 999-9999");
                                        });
                                    </script>
                                    <label for="job_title">Должность</label>
                                    {{--<input type="text" class="form-control" id="job_title" name="job_title" placeholder="">--}}
                                    <select class="form-control" name="job_title" id="job_title">
                                        @foreach(\App\Models\Job_title::all() as $job)
                                            <option selected value="{{$job->name}}">{{$job->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Список пользователей</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Имя</th>
                                <th>Должность</th>
                                <th>Роль</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach(\App\Models\Users::all() as $user)
                                <tr>
                                    <td>#</td>
                                    <td style="width: 25%"><a href="{{route('users.show', ['id'=>$user->id])}}">{{$user->fullName()}}</a>
                                    </td>
                                    <td>
                                        {{$user->job_title}}
                                    </td>
                                    <td>{{$user->role}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
