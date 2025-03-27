@extends('Main.Layouts.sidebar')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <form role="form" method="post" action="{{route('magazines.exel.create')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Загрузить</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <script>

    </script>
@endsection
