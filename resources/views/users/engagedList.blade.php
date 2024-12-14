@extends('Main.Layouts.sidebar')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Список занимающихся</h1>
                    </div>
                    {{--<div class="col-sm-6">
                        <button type="button" class="btn btn-success float-sm-right" data-toggle="modal"
                                data-target="#createModal">
                            Добавить пользователя
                        </button>
                    </div>--}}
                    <div class="content">
                        <div class="row card mt-3">
                            <div class="col-md-12">
                                <div class="profile-content margin-top-50" id="user_table_div">
                                    <div class="portlet light portlet-fit portlet-datatable bordered">
                                        <div class="portlet-body" id="tableDiv">
                                            <div class="table-container">
                                                <meta name="csrf-token" content="{{ csrf_token()}}">
                                                <table width="100%" id="user_table"
                                                       class="table table-striped table-bordered table-hover dataTable dtr-inline word-break"></table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        $(document).ready(function () {
            $.ajax({ // инициализациям ajax запрос
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST', // отправляем в POST формате, можно GET
                url: '{{route('engaged.list.get')}}', // путь дo обработчика
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

            table = $('#user_table').DataTable({
                "deferRender": true,
                "iDisplayLength": 25,
                "order": [[0, "asc"]],
                "language": {
                    "url": "{{asset('assets/profile/plugins/datatables/ru.json')}}",
                },
                "data": data,
                "dom": "<'row padding-top-5 mt-1 padding-left-15 padding-right-15' <'col-md-12'B>><'row padding-left-15 padding-right-15'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row padding-left-15 padding-right-15'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
                "buttons": [
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
                ],
                "columns": [
                    {
                        title: "Имя",
                        className: "column-120 text-align-center vertical-align-middle",
                        data: 'user',
                        render: function (data){
                            return '<a href="/users/profile/' + data.id +'">'+ data.name +'</a>'
                        }
                    },
                    {
                        title: "Нозология",
                        className: "column-160 text-align-center vertical-align-middle",
                        data: 'nosology',
                    },
                    {
                        title: "Тренер",
                        className: "column-160 text-align-center vertical-align-middle",
                        data: 'coach',
                        render: function (data){
                            return '<a href="/users/profile/' + data.id +'">'+ data.name +'</a>'
                        }
                    },
                    {
                        title: "справка",
                        className: "column-160 text-align-center vertical-align-middle",
                        data: 'mc',
                    },
                    {
                        title: "Представитель",
                        className: "column-160 text-align-center vertical-align-middle",
                        data: 'repres',
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
