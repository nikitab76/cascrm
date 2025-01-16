@extends('Main.Layouts.sidebar')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Занятия</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="content">
            {{--<div class="accordion" id="accordionExample">--}}
            @foreach($trainig as $train)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <a href="{{route('magazine.show', ['id'=>$train->group])}}">{{$train->profile . ' ' . $train->date}}</a>
                    </h2>
                    <div id="{{$train->id}}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                        </div>
                    </div>
                </div>
            @endforeach
            {{--</div>--}}
        </div>
    </div>
@endsection
