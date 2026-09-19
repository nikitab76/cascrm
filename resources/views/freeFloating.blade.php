@extends('Main.Layouts.sidebar')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Список занимающихся свободным плаванием</h1>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-success float-sm-right" data-toggle="modal"
                                data-target="#createModal">
                            Добавить занимающегося
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
                                    <label for="swName">ФИО</label>
                                    <input type="text" class="form-control" id="swName" name="swName" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="swPhone">Телефон</label>
                                    <input type="text" class="form-control" id="swPhone" name="swPhone" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="swNoz">Нозология</label>
                                    <input type="text" class="form-control" id="swNoz" name="swNoz" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="swDat">Справка</label>
                                    <input type="date" class="form-control" id="swDat" name="swDat" placeholder="">
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
                        <h3 class="card-title">Список</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">

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
