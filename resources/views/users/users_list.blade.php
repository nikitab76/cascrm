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
                    <form role="form" method="post" action="#">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Добавить объект</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="roomName">Название</label>
                                    <input type="text" class="form-control" id="roomName" name="roomName" placeholder="">
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
                        {{--<table class="table">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Название</th>
                                <th>Загруженность</th>
                                <th style="width: 40px">Label</th>
                                <th style="width: 40px">Delight</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($rooms as $room)
                                <tr>
                                    <td>#</td>
                                    <td style="width: 25%"><a href="{{route('rooms.show', ['room'=>$room->slug])}}">{{$room->title}}</a>
                                    </td>
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-danger">55%</span></td>
                                    <td>
                                        <form action="{{ route('rooms.destroy', ['room'=>$room->id]) }}" method="post" class="float-left">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Подтвердите удаление')">
                                                <i
                                                    class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>--}}
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
