@extends('Main.Layouts.sidebar')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Группы тренеров</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="content">
            <div class="accordion" id="accordionExample">
                @foreach($groups as $id => $group)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#{{\App\Models\Users::where('id', $id)->value('surname')}}" aria-expanded="false"
                                    aria-controls="{{\App\Models\Users::where('id', $id)->value('surname')}}">
                                {{\App\Models\Users::where('id', $id)->value('surname')}}
                            </button>
                        </h2>
                        <div id="{{\App\Models\Users::where('id', $id)->value('surname')}}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                @foreach($group as $train)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                    data-bs-target="#group_{{$train->id}}" aria-expanded="false"
                                                    aria-controls="group_{{$train->id}}">
                                                {{$train->group_num}} группа
                                            </button>
                                        </h2>
                                        <div id="group_{{$train->id}}" class="accordion-collapse collapse" data-bs-parent="#accordionExampleGroup">
                                            <div class="accordion-body">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">id</th>
                                                        <th scope="col">ФИО</th>
                                                        <th scope="col">Дата рождения</th>
                                                        <th scope="col">Нозология</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach(json_decode($train['users_list']) as $user)
                                                        {{--<div class="col mt-2">
                                                            <input type="text" class="col-6 form-control" value="{{\App\Models\Users::where('id', $user)->first()->fullName()}}">
                                                        </div>--}}
                                                        <tr>
                                                            <th scope="row">{{$user}}</th>
                                                            <td>{{\App\Models\Users::where('id', $user)->first()->fullName()}}</td>
                                                            <td>{{\App\Models\UsersDocument::where('user_id', $user)->value('user_birth')}}</td>
                                                            <td>{{\App\Models\UsersDocument::where('user_id', $user)->value('nosology')}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
