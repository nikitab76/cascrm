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
                                    <h5 class="description-header">Занятость</h5>
                                    <span class="description-text">сколько то%</span>
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
            <section class="content-header">
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
            </section>

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
                                <h3>сводная таблица</h3>
                                <table class="table table-bordered" id="myTable">
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
                                </table>
                            </div>
                        </div>
                        {{--kanban--}}
                        <div class="tab-pane fade {{--show active--}}" id="profile-tab-pane" role="tabpanel"
                             aria-labelledby="profile-tab"
                             tabindex="0">
                            <div class="p-3 row">
                                <div class="card card-row card-primary m-1">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            Суббота
                                        </h3>
                                        <div class="card-tools">
                                            <h6 class="btn btn-tool btn-link">{{date('d-m-Y' ,strtotime($room['info']->date))}}</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="card card-primary card-outline collapsed-card">
                                            <div class="card-header accordion-item">
                                                <h3 class="card-title" data-card-widget="collapse">
                                                    <a href="javascript:;"
                                                       style="color: black">{{$room['info']->profile}}</a>
                                                </h3>
                                                <div class="card-tools">
                                                    <span class="btn btn-tool"
                                                          data-card-widget="collapse">{{$room['info']->time}}</span>
                                                    <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: none;">
                                                <div class="alert alert-light">
                                                    <label>Тренер: {{$room['info']->coach}}</label>
                                                </div>
                                                <div class="alert alert-light">
                                                    <label>Площадь: {{$room['info']->quarter == 4 ? '1/4': ''}}</label>
                                                </div>
                                                <div class="alert alert-light">
                                                    <label>Комментарий:</label><br>
                                                    <label>{{$room['info']->comment}}</label>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>

                                    </div>
                                </div>
                                <div class="card card-row card-primary m-1">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            Вторник
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="card card-primary card-outline collapsed-card">
                                            <div class="card-header accordion-item">
                                                <h3 class="card-title" data-card-widget="collapse">
                                                    <a href="javascript:;" style="color: black">Primary Outline</a>
                                                </h3>

                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                            data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: none;">
                                                The body of the card
                                            </div>
                                            <!-- /.card-body -->
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            @endif
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <script>


    </script>
@endsection
