<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CASCRM</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="{{asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/profile/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/profile/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css"/>
    <link rel="stylesheet" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="{{asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/profile/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('assets/profile/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/profile/dist/css/adminlte.min.css')}}">
</head>
<body class="container">
<div class="row">
    <div class="container">
        <h1>Запись на свободное плавание</h1>
        @dump($trening)
        <div class="accordion accordion-flush" id="accordionFlushExample"> @foreach($trening as $date => $items)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ str_replace('-', '', $date) }}" aria-expanded="false"
                                aria-controls="collapse{{ str_replace('-', '', $date) }}">
                            Занятия {{ date('d-m-Y', strtotime($date)) }} </button>
                    </h2>
                    <div id="collapse{{ str_replace('-', '', $date) }}" class="accordion-collapse collapse"
                         data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body"> @foreach($items as $tr)
                                <button type="button" class="btn btn-outline-primary w-100 mb-2" data-bs-toggle="modal"
                                        data-bs-target="#trainingModal{{ $tr->id }}"> Записаться на
                                    тренировку {{ $tr->time_start }} - {{ $tr->time_end }} </button> <!-- Modal -->
                                <div class="modal fade" id="trainingModal{{ $tr->id }}" tabindex="-1"
                                     aria-labelledby="trainingModalLabel{{ $tr->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header"><h5 class="modal-title"
                                                                          id="trainingModalLabel{{ $tr->id }}"> Запись
                                                    на тренировку </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Закрыть"></button>
                                            </div>
                                            <div class="modal-body"><p>
                                                    <strong>Дата:</strong> {{ date('d-m-Y', strtotime($date)) }} </p>
                                                <p><strong>Время:</strong> {{ $tr->time_start }} - {{ $tr->time_end }}
                                                </p>
                                                <form action="{{--{{ route('training.register') }}--}}" method="POST"> @csrf
                                                    <input type="hidden" name="training_id" value="{{ $tr->id }}">
                                                    <div class="mb-3"><label for="name{{ $tr->id }}" class="form-label">
                                                            Имя </label> <input type="text" class="form-control"
                                                                                id="name{{ $tr->id }}" name="name"
                                                                                required></div>
                                                    <div class="mb-3"><label for="phone{{ $tr->id }}"
                                                                             class="form-label"> Телефон </label> <input
                                                            type="text" class="form-control" id="phone{{ $tr->id }}"
                                                            name="phone" required></div>
                                                    <button type="submit" class="btn btn-primary w-100"> Записаться
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach </div>
                    </div>
                </div>
            @endforeach </div>
    </div>
</div>


<!-- jQuery -->
<script src="{{asset('assets/profile/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/profile/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/profile/dist/js/adminlte.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>
</html>
