@extends('Main.Layouts.sidebar')

@section('content')
    <div class="content-wrapper">
        <div class="content">
            @if(!empty($trainig))
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Тренер</th>
                            <th>тренировка</th>
                            <th>зал</th>
                            <th>дата</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($trainig as $train)
                            <tr>
                                <td>#</td>
                                <td>{{$train->coach}}</td>
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
        </div>
    </div>
@endsection
