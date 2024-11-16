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
                                <h5 class="description-header">35</h5>
                                <span class="description-text">PRODUCTS</span>
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
                <div class="card">
                    <ul class="nav nav-tabs" id="user_info" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home-tab-pane" type="button" role="tab"
                                    aria-controls="home-tab-pane" aria-selected="true">Общее
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile-tab-pane" type="button" role="tab"
                                    aria-controls="profile-tab-pane" aria-selected="false">Документы
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel"
                             aria-labelledby="home-tab" tabindex="0">
                            <div class="p-3">
                                <h3>Общее</h3>
                                <div class="content">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                {{--@dump($user)--}}
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
                                                <div class="row">
                                                    <label for="phone">Телефон</label>
                                                    <input type="tel" class="form-control" id="user_phone"
                                                           pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                                           name="user_phone" autocomplete="off"  value="{{$user->phone}}">
                                                    <label for="speciality">Направление</label>
                                                    <input type="text" class="form-control" id="speciality" name="speciality">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--kanban--}}
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
                    </div>
                </div>
            </div>
            {{--<div class="card">
                <div class="card-header">
                    <h3 class="card-title">Любая нужная инфа</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    Start creating your amazing application!
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    Footer
                </div>
                <!-- /.card-footer-->
            </div>--}}
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
