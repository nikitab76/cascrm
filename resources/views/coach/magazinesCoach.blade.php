@extends('Main.Layouts.sidebar')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Журналы тренеров</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        {{-- @dump($groups)--}}
        <div class="content">
            <div class="accordion" id="accordionExample">
                @if (!empty($groups))
                    @foreach($groups as $id => $group)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#{{$id}}" aria-expanded="false"
                                        aria-controls="{{$id}}">
                                    {{$id}}
                                </button>
                            </h2>
                            <div id="{{$id}}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    @foreach($group as $train)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#group_{{$train->id}}" aria-expanded="false"
                                                        aria-controls="group_{{$train->id}}">
                                                    {{$train->profile  . ' ' . $train->time_start . ' ' . $train->date}}
                                                </button>
                                            </h2>
                                            <div id="group_{{$train->id}}" class="accordion-collapse collapse"
                                                 data-bs-parent="#accordionExampleGroup">
                                                <div class="accordion-body">
                                                    @if(isset($train->users))
                                                        @foreach($train->users as $user => $value)
                                                            <div class="col mt-2">
                                                                <input type="text" class="col-6 form-control"
                                                                       value="{{\App\Models\Users::where('id', $user)->value('surname')}}"
                                                                       @if($value) style="color: green"
                                                                       @else style="color: red" @endif>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
