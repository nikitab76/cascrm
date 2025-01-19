@extends('Main.Layouts.sidebar')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Журнал посещений</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="content">
            {{--<div class="accordion" id="accordionExample">--}}
            @foreach($trainig as $train)
                <a href="{{route('magazine.show', ['id'=>$train->group, 'day'=>$train->profile, 'profile'=>$train->date])}}"
                   style="color: #ffffff">
                    <div class="alert alert-info" role="alert">
                        <h5 class="accordion-header">
                            <span style="color: black">{{$train->profile . ' ' . $train->time_start}}</span>
                            {{$train->date}}
                        </h5>
                    </div>
                </a>
            @endforeach
            {{--</div>--}}
        </div>
    </div>
@endsection
