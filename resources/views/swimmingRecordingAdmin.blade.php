@extends('Main.Layouts.sidebar')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="container">
                    <h1>Списки записавшихся</h1>
                    {{--@dump($trening)--}}
                    @if(isset($trening))
                        <div class="accordion accordion-flush"
                             id="accordionFreeSwimming"> @foreach($trening as $date => $items)
                                {{-- ДАТА --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#date{{ str_replace('-', '', $date) }}"
                                                aria-expanded="false"
                                                aria-controls="date{{ str_replace('-', '', $date) }}">
                                            Занятия {{ date('d.m.Y', strtotime($date)) }} </button>
                                    </h2>
                                    <div id="date{{ str_replace('-', '', $date) }}" class="accordion-collapse collapse"
                                         data-bs-parent="#accordionFreeSwimming">
                                        <div
                                            class="accordion-body"> {{-- ТРЕНИРОВКИ В ЭТОТ ДЕНЬ --}} @foreach($items as $tr)
                                                <div class="card mb-3">
                                                    <div class="card-header"><strong> {{ $tr->time_start }}
                                                            - {{ $tr->time_end }} </strong> <span
                                                            class="badge bg-primary float-end"> {{ $tr->freeFloatings->count() }} </span>
                                                    </div>
                                                    <div
                                                        class="card-body"> {{-- ЗАПИСАВШИЕСЯ --}} @if($tr->freeFloatings->isNotEmpty())
                                                            <div
                                                                class="list-group"> @foreach($tr->freeFloatings as $user)
                                                                    <div class="list-group-item">
                                                                        <div class="fw-bold"> {{ $user->fio }} </div>
                                                                        <div class="small text-muted"> Дата
                                                                            рождения: {{ \Carbon\Carbon::parse($user->birthday)->format('d.m.Y') }} </div>
                                                                        <div class="small text-muted">
                                                                            Телефон: {{ $user->phone }} </div>
                                                                        <div class="small text-muted">
                                                                            Нозология: {{ $user->nozologe }} </div> @if($user->mail)
                                                                            <div class="small text-muted">
                                                                                Email: {{ $user->mail }} </div>
                                                                        @endif </div>
                                                                @endforeach </div>
                                                        @else
                                                            <div class="text-muted"> На тренировку никто не записался
                                                            </div>
                                                        @endif </div>
                                                </div>
                                            @endforeach </div>
                                    </div>
                                </div>
                            @endforeach </div>
                    @else
                        Запись пуста
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
