@extends('Main.Layouts.sidebar')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{$profile . ' ' . $day}}</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="content">
            <form role="formAdd" method="post" class="formAddTr">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input id="coach" value="{{\Illuminate\Support\Facades\Auth::user()->id}}" style="display: none">
                <input id="day" value="{{$day}}" style="display: none">
                <input id="profile" value="{{$profile}}" style="display: none">
                <div class="row container">
                    @foreach($data as $user => $key)
                        @if($user = \App\Models\Users::where('id', $user)->first())
                            <div class="form-check form-control">
                                <div class="container">
                                    <input class="form-check-input user-checkbox" type="checkbox"
                                           value="{{'user_' . $user->id}}"
                                           id="{{$user->role . '_' . $user->id}}"
                                            @if($key) checked @endif>
                                    <label class="form-check-label" for="{{$user->role . '_' . $user->id}}">
                                        <div class="col">{{$user->fullName()}}</div>
                                    </label>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <button type="button" class="btn btn-success form-check form-control" style="color: white" onclick="noteUsers()">
                        Отметить
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function noteUsers() {
            // Собираем значения отмеченных чекбоксов
            const usersData = {};

            $('.user-checkbox').each(function () {
                const userId = $(this).val(); // Получаем ID из data-id
                const isChecked = $(this).is(':checked') ? 1 : 0; // Определяем отметку
                usersData[userId] = isChecked; // Добавляем в объект
            });

            // Получаем CSRF-токен
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var data = {
                users: usersData,
                day: $('#day').val(),
                coach: $('#coach').val(),
                profile: $('#profile').val()
            }
            console.log(data)
            // Отправляем данные на сервер
            $.ajax({
                url: '{{route('magazine.noteUsers')}}', // Укажите путь к вашему обработчику
                type: 'POST',
                headers: {'X-CSRF-TOKEN': csrfToken},
                data: data,
                success: function (response) {
                    alert(response.message);
                }
            });
        }
    </script>
@endsection
