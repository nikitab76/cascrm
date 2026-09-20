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
                    @if(\Illuminate\Support\Facades\Auth::user()->role == 'admin')
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-success float-sm-right" data-toggle="modal"
                                data-target="#createModal">
                            Добавить занимающегося
                        </button>
                    </div>
                    @endif
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form role="form" method="post" action="{{route('createUser.freeFloating')}}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Добавить занимающегося</h5>
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
                                    <select class="form-control" name="swNoz" id="swNoz">
                                        <option selected value="ovz">ОВЗ, Общая</option>
                                        <option selected value="lin">ЛИН</option>
                                        <option selected value="poda">ПОДА</option>
                                        <option selected value="sluh">Слух</option>
                                        <option selected value="zrenie">Зрение</option>
                                    </select>
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
                        <div class="portlet-body" id="tableDiv">
                            <div class="table-container">
                                <meta name="csrf-token" content="{{ csrf_token()}}">
                                <table width="100%" id="user_table"
                                       class="table table-striped table-bordered table-hover dataTable dtr-inline word-break"></table>
                            </div>
                        </div>
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
    <script>
        $(document).ready(function () {
            $.ajax({ // инициализациям ajax запрос
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST', // отправляем в POST формате, можно GET
                url: '{{route('getUser.freeFloating')}}', // путь дo обработчика
                dataType: 'json', // ответ ждём в json формате
                data: '', // данные для отправки
                success: function (data) { // событие в случае удачного запроса
                    console.log(data)
                    if (data.success) {
                        tableRender(data.data)
                    }
                },
            })
        });

        let table = false;

        function tableRender(data) {
            if (table) {
                table.destroy();
            }
            console.log(data)
            table = $('#user_table').DataTable({
                "deferRender": true,
                "iDisplayLength": 25,
                "order": [[0, "asc"]],
                "language": {
                    "url": "{{asset('assets/profile/plugins/datatables/ru.json')}}",
                },
                "data": data,
                "dom": "<'row padding-top-5 mt-1 padding-left-15 padding-right-15' <'row padding-left-15 padding-right-15'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row padding-left-15 padding-right-15'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
                /*"buttons": [
                    {
                        extend: 'collection',
                        text: ' Сохранить',
                        className: 'btn red btn-outline fa fa-download',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                className: 'btn',
                                text: 'EXCEL',
                            },
                            {
                                extend: 'csvHtml5',
                                className: 'btn',
                                text: 'CSV',
                            },
                        ]
                    },
                    {
                        extend: 'copy',
                        className: 'btn purple btn-outline fa fa-copy',
                        text: ' Копировать',
                    },
                    {
                        extend: 'print',
                        className: 'btn blue btn-outline fa fa-print',
                        text: ' Печать',
                        exportOptions: {
                            stripHtml: false,
                        }
                    },
                    {
                        extend: 'colvis',
                        className: 'btn dark btn-outline fa fa-angle-down',
                        text: ' Выбрать колонки',
                    },
                ],*/
                "columns": [
                    {
                        title: "Занимающиеся",
                        className: "column-160 text-align-center vertical-align-middle",
                        data: 'fio',
                        render: function (data, type, row) {

                            const parts = row.date_spravka.split('-');

                            const dateSpravka = new Date(
                                parts[2],
                                parts[1] - 1,
                                parts[0]
                            );

                            const today = new Date();
                            today.setHours(0, 0, 0, 0);

                            // Сколько дней осталось до окончания
                            const diffDays = Math.ceil(
                                (dateSpravka - today) / (1000 * 60 * 60 * 24)
                            );

                            let dateClass = 'text-success';

                            if (diffDays < 0) {
                                // Уже просрочена
                                dateClass = 'text-danger';
                            } else if (diffDays <= 7) {
                                // Осталось 7 дней или меньше
                                dateClass = 'text-warning';
                            }

                            return `
                                    <div>
                                        <div>
                                            <strong>${row.fio}</strong> <span class="${dateClass}">${row.date_spravka}</span>
                                        </div>
                                        <div>
                                            ${row.nozologe}
                                        </div>
                                        <div>
                                            <a href="tel:${row.phone}">${row.phone}</a>
                                        </div>
                                    </div>
                                `;
                        }
                    },
                ],
                "headerCallback": function (row, data, start, end, display) {
                    table.columns().iterator('column', function (settings, column) {
                        if (settings.aoColumns[column].tooltip !== undefined) {
                            $(table.column(column).header()).attr('data-original-title', settings.aoColumns[column].tooltip);
                        }
                    });
                },
                //Установка подписей к названиям колонок
                "initComplete": function (settings) {
                    $('thead th[data-original-title]').tooltip({
                        "delay": 0,
                        "track": true,
                        "fade": 250,
                        "container": 'body'
                    });
                },
            });

        }
    </script>
@endsection
