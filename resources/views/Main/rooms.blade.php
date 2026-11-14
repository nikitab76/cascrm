@extends('Main.Layouts.sidebar')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Все помещения</h1>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-success float-sm-right" data-toggle="modal"
                                data-target="#createModal">
                            Добавить объект
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
                    <form role="form" method="post" action="{{route('rooms.store')}}">
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
                                    <input type="text" class="form-control" id="roomName" name="roomName"
                                           placeholder="">
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
                        <h3 class="card-title">Список объектов</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Название</th>
                                <th>Загруженность за месяц <small class="text-muted">
                                        ({{ $monthStart->format('d.m.Y') }} —
                                        {{ $monthEnd->format('d.m.Y') }})
                                    </small></th>
                                <th style="width: 80px">Процент</th>
                                <th style="width: 40px">Удалить</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($rooms as $room)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td style="width: 25%">
                                        <a href="{{ route('rooms.show', ['room' => $room->slug]) }}">
                                            {{ $room->title }}
                                        </a>
                                    </td>

                                    <td>
                                        <div class="progress progress-xs">
                                            <div
                                                class="progress-bar
                        {{ $room->load_percent >= 80 ? 'bg-danger' : ($room->load_percent >= 50 ? 'bg-warning' : 'bg-success') }}"
                                                style="width: {{ min($room->load_percent, 100) }}%">
                                            </div>
                                        </div>

                                        <small class="text-muted">
                                            {{ $room->occupied_hours }} из {{ $room->available_hours }} часов
                                        </small>
                                    </td>

                                    <td>
            <span class="badge
                {{ $room->load_percent >= 80 ? 'bg-danger' : ($room->load_percent >= 50 ? 'bg-warning' : 'bg-success') }}">
                {{ $room->load_percent }}%
            </span>
                                    </td>

                                    <td>
                                        <form action="{{ route('rooms.destroy', ['room' => $room->id]) }}"
                                              method="post">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Подтвердите удаление')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
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
