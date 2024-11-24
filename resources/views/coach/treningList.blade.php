@extends('Main.Layouts.sidebar')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Мои занятия</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="content">
            @if(!empty($trainig))
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>тренировка</th>
                            <th>зал</th>
                            <th>дата</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($trainig as $train)
                            <tr>
                                <td>#</td>
                                <td> {{$train->profile}}
                                </td>
                                <td>
                                    {{\App\Models\Room::where('slug', $train->slug_room)->value('title')}}
                                </td>
                                <td>{{$train->date}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            @endif
            <div class="card card-success mt-5">
                <div class="card-header">
                    <h3 class="card-title">Добавить занятия</h3>
                </div>
                <div class="card-body">
                    <form role="form" method="post">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="alert alert-danger" role="alert" id="error" style="display: none"></div>
                                    <div class="alert alert-success" role="alert" id="success" style="display: none"></div>
                                    <input type="text" name="coach" id="coach" class="form-control"
                                           value="{{\Illuminate\Support\Facades\Auth::user()->id}}"
                                           style="display: none">
                                    <label for="classProfile">Секция</label>
                                    <input type="text" class="form-control" id="classProfile"
                                           name="classProfile"
                                           placeholder="" value="{{old('classProfile')}}" autocomplete="off">
                                    <label for="class">Зал</label>
                                    <select class="form-control" name="class" id="class">
                                        @foreach(\App\Models\Room::all() as $room)
                                            <option value="{{$room->id}}">{{$room->title}}</option>
                                        @endforeach
                                    </select>
                                    <div class="pt-2">
                                        <label for="classTime">Время начала</label>
                                        <input type="time" class="form-control" id="classTime" name="classTime"
                                               placeholder="" value="{{old('classTime')}}">
                                    </div>
                                    <label for="classTime">Время окончания</label>
                                    <input type="time" class="form-control" id="classTimeEnd"
                                           name="classTimeEnd"
                                           placeholder="" value="{{old('classTimeEnd')}}">
                                    <div class="pt-2">
                                        <label for="classDate">Дата</label>
                                        <input type="date" class="form-control" id="classDate" name="classDate"
                                               placeholder="" value="{{old('classDate')}}">
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
                            <button type="button" class="btn btn-primary" onclick="createTrening()">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function createTrening() {
            var data = {
                'classDate': $('#classDate').val(),
                'classTime': $('#classTime').val(),
                'classTimeEnd': $('#classTimeEnd').val(),
                'comment': $('#classComment').val(),
                'classProfile': $('#classProfile').val(),
                'class': $('#class').val(),
                'coach': $('#coach').val(),
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: '{{ route('training.coach.create') }}',
                dataType: 'json',
                data: data,
                success: function (data) {
                    console.log(data)
                    if(data.success){
                        $('#error').hide();
                        $('#success').show();
                        $('#success').text(data.error);
                        window.location.reload();
                    } else {
                        $('#success').hide();
                        $('#error').show()
                        $('#error').text(data.error)
                    }
                },
            });
        }
    </script>
@endsection
