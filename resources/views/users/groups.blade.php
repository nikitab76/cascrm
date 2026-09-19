@extends('Main.Layouts.sidebar')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Мои группы</h1>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-success float-sm-right" data-toggle="modal"
                                data-target="#createGroupe">
                            Добавить группу
                        </button>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="content">
            {{--@dump($trainig)--}}
            <div class="accordion" id="accordionExample">
                @foreach($trainig as $train)
                    {{--@dump($train)--}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#{{$train->id}}" aria-expanded="false"
                                    aria-controls="{{$train->id}}">
                                {{$train->group_num}}
                            </button>
                        </h2>
                        <div id="{{$train->id}}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                @foreach(json_decode($train['users_list']) as $user)
                                    <div class="row user-row-edd d-flex align-items-center mt-3">
                                        <div class="col-auto">
                                            <input type="text" class="form-control"
                                                   value="{{ \App\Models\Users::where('id', $user)->value('surname') }}">
                                        </div>
                                        {{--<div class="col-auto">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeBtnEdd()">
                                                &minus;
                                            </button>
                                        </div>--}}
                                    </div>
                                @endforeach
                                {{--<div class="col-sm-6">
                                    <button type="button" class="btn btn-success mt-2" data-toggle="modal"
                                            data-target="#editGroupe">
                                        Редактировать группу
                                    </button>
                                </div>--}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="createGroupe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form role="form" method="post" id="creategroupform">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Добавить группу</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <meta name="csrf-token" content="{{ csrf_token()}}">
                            <div class="card-body">
                                <div class="form-group" id="user-container">
                                    <div class="alert alert-danger" role="alert" id="error" style="display: none"></div>
                                    <div class="alert alert-success" role="alert" id="success"
                                         style="display: none"></div>
                                    <input type="text" name="coach" id="coach" class="form-control"
                                           value="{{\Illuminate\Support\Facades\Auth::user()->id}}"
                                           style="display: none">
                                    <label for="numGroup">Название группы</label>
                                    <input type="text" class="form-control" id="numGroup" name="numGroup"
                                           placeholder="">

                                    <label for="users[]">Занимающиеся</label>
                                    <hr>
                                    <div class="user-row d-flex">
                                        <div class="col-10">
                                            <label for="surname">Фамилия</label>
                                            <input type="text" class="form-control" id="surname" name="surname"
                                                   placeholder="Иванов" value="{{old('surname')}}" autocomplete="off">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="name">Имя</label>
                                                    <input type="text" class="form-control" id="name" name="name"
                                                           placeholder="Иван" value="{{old('name')}}" autocomplete="off">
                                                </div>
                                                <div class="col-6">
                                                    <label for="second_name">Отчество</label>
                                                    <input type="text" class="form-control" id="second_name" name="second_name"
                                                           placeholder="Иванович" value="{{old('second_name')}}"
                                                           autocomplete="off">
                                                </div>
                                            </div>
                                            {{--<select name="users[]" class="form-control" id="users">
                                                <option value="null"></option>
                                                @foreach(\App\Models\Users::where('role', 'user')->orderBy('surname', 'asc')->get() as $user)
                                                    <option value="{{$user->id}}">{{$user->fullName()}}</option>
                                                @endforeach
                                            </select>--}}
                                        </div>
                                        {{--<div class="col">
                                            <button type="button" class="add-btn btn btn-light" onclick="addBtn()"
                                                    id="add-btn">+
                                            </button>
                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" onclick="createGroup()" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function addBtn() {
                const newRow = `
            <div class="user-row d-flex mt-3">
                <div class="col-10">
                <select name="users[]" class="form-control">
                    <option value="null"></option>
                                                @foreach(\App\Models\Users::where('role', 'user')->orderBy('surname', 'asc')->get() as $user)
                <option value="{{$user->id}}">{{$user->fullName()}}</option>
                                                @endforeach
                </select>
                </div>
                <button type="button" class="add-btn btn btn-light mr-1" onclick="addBtn()" id="add-btn">+</button>
                <button type="button" class="remove-btn btn btn-danger" onclick="removeBtn()" id="remove-btn">-</button>

            </div>
        `;
                $('#user-container').append(newRow);
            }

            function removeBtn() {
                $('#user-container').on('click', '.remove-btn', function () {
                    $(this).closest('.user-row').remove();
                });
            }

            function createGroup() {
                let pageData = $('#creategroupform').serialize();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: '{{ route('groups.create') }}',
                    dataType: 'json',
                    data: pageData,
                    success: function (data) {
                        if (data.success) {
                            $('#error').hide();
                            $('#success').show();
                            $('#success').text(data.message);
                            window.location.reload();
                        } else {
                            $('#success').hide();
                            $('#error').show()
                            $('#error').text(data.message)
                        }
                    },
                });
            }
        </script>
    </div>
@endsection
