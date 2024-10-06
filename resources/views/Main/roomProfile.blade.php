@extends('Main.Layouts.sidebar')

@section('content')
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
                <div class="card card-default" >
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
                                    <div class="form-group">
                                        <input style="display: none" name="roomsSlug" value="{{{$room['room']->slug}}}">
                                        <label for="classCoach">Инструктор</label>
                                        <input type="text" class="form-control" id="classCoach" name="classCoach"
                                               placeholder="">
                                        <div class="pt-2">
                                            <label for="classProfile">Секция</label>
                                            <input type="text" class="form-control" id="classProfile"
                                                   name="classProfile"
                                                   placeholder="">
                                        </div>
                                        <div class="pt-2">
                                            <label for="classTime">Время</label>
                                            <input type="time" class="form-control" id="classTime" name="classTime"
                                                   placeholder="">
                                        </div>
                                        <div class="pt-2">
                                            <label for="classDate">Дата</label>
                                            <input type="date" class="form-control" id="classDate" name="classDate"
                                                   placeholder="">
                                        </div>
                                        <div class="pt-2">
                                            <div class="form-group">
                                                <label for="classQuarter">Четверть</label>
                                                <select class="form-control" name="classQuarter" id="classQuarter">
                                                    <option selected value="4">1/4</option>
                                                    <option value="2">2/4</option>
                                                    <option value="1">весь зал</option>
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
                                    <p>сводная таблица в процессе обмозгования</p>
                                    {{--<table class="table table-bordered" id="myTable">
                                        <thead>
                                        <tr>
                                            <th style="width: 10%">Время</th>
                                            <th style="width: 15%">Пн</th>
                                            <th>Вт</th>
                                            <th>ср</th>
                                            <th>Чт</th>
                                            <th>Пт</th>
                                            <th>Сб</th>
                                            <th>Вс</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @for($i=0; $i<24; $i+=1)
                                            <tr>
                                                <td>{{$i}}:00</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        @endfor
                                        </tbody>
                                    </table>--}}
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
        </section>
    </div>
    <script>
        $(document).ready(function (){
            $('#alertmess').hide();
        })
        function show(){
            $('#alertmess').show();
        }
        function hide(){
            $('#alertmess').hide();
        }

    </script>
@endsection
