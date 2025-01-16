@extends('Main.Layouts.sidebar')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Занятие</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="content">
            @foreach($users as $user)
                <div class="col">{{\App\Models\Users::where('id', $user)->first()->fullName()}}</div>
            @endforeach
        </div>
    </div>
@endsection
