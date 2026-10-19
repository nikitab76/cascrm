@extends('Main.Layouts.sidebar')

@section('content')
    <style>
        .schedule-wrapper {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow: auto;
            max-height: 75vh;
            position: relative;
            isolation: isolate;
            -webkit-overflow-scrolling: touch;
        }

        .schedule-table {
            width: max-content;
            min-width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
            margin: 0;
        }

        .schedule-table th,
        .schedule-table td {
            box-sizing: border-box;
            width: 52px;
            min-width: 52px;
            height: 34px;
            padding: 4px;
            text-align: center;
            white-space: nowrap;
            border-right: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
        }

        .schedule-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f8f9fa;
        }

        .schedule-table .room-column {
            position: sticky;
            left: 0;
            width: 160px;
            min-width: 160px;
            max-width: 160px;
            text-align: left;
            white-space: normal;
            overflow-wrap: anywhere;
            background: #fff;
            z-index: 3;
            border-right: 2px solid #adb5bd;
        }

        .schedule-table thead .room-column {
            z-index: 4;
            background: #e9ecef;
        }

        .schedule-table td.busy-cell {
            background: #dc3545;
        }

        .schedule-table td.free-cell {
            background: #d4edda;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>График загруженности на день</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Button trigger modal -->

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Расписание</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="schedule-wrapper">
                        <table id="roomVault" class="schedule-table">
                            <thead>
                            <tr>
                                <th class="room-column">Зал</th>

                                @foreach (array_keys($schedule[0]['slots'] ?? []) as $time)
                                    <th>{{ $time }}</th>
                                @endforeach
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($schedule as $row)
                                <tr>
                                    <td class="room-column">
                                        {{ $row['title'] }}
                                    </td>

                                    @foreach ($row['slots'] as $time => $busy)
                                        <td
                                            class="{{ $busy ? 'busy-cell' : 'free-cell' }}"
                                            title="{{ $time }} — {{ $busy ? 'Занято' : 'Свободно' }}"
                                        ></td>
                                    @endforeach
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
    <script>
        $(document).ready(function () {
            $('#roomVault').DataTable({
                paging: false,
                searching: false,
                info: false,
                ordering: false,
                scrollX: true,
                scrollY: '70vh',
                scrollCollapse: true,
                autoWidth: false,
                responsive: false
            });
        });
    </script>
@endsection
