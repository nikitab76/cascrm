
@extends('Main.Layouts.sidebar')

@section('content')

    <style>
        :root {
            --schedule-bg: #f5f7fb;
            --schedule-border: #e5eaf1;
            --schedule-text: #344054;
            --schedule-muted: #98a2b3;
            --schedule-green: #a1debb;
            --schedule-green-dark: #159957;
            --schedule-red: #ab3e3e;
            --schedule-red-dark: #dc3545;
        }

        .vault-page {
            padding: 20px;
            background: var(--schedule-bg);
            min-height: 100vh;
        }

        .vault-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .vault-heading h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1d2939;
            margin: 0 0 5px;
        }

        .vault-subtitle {
            color: var(--schedule-muted);
            font-size: 13px;
        }

        .vault-print-btn {
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(13, 110, 253, .15);
        }

        .vault-card {
            background: #fff;
            border: 1px solid var(--schedule-border);
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(16, 24, 40, .04);
            overflow: hidden;
        }

        .vault-card-header {
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--schedule-border);
        }

        .vault-card-header h3 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: #344054;
        }

        .schedule-legend {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            font-size: 12px;
            color: #667085;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 4px;
        }

        .legend-free {
            background: var(--schedule-green);
            border: 1px solid #a6e8c1;
        }

        .legend-busy {
            background: var(--schedule-red);
            border: 1px solid #f5b5b5;
        }

        .schedule-wrapper {
            width: 100%;
            max-height: 72vh;
            overflow: auto;
            position: relative;
            isolation: isolate;
            -webkit-overflow-scrolling: touch;
        }

        .schedule-table {
            width: max-content;
            min-width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
            margin: 0;
            font-size: 12px;
        }

        .schedule-table th,
        .schedule-table td {
            box-sizing: border-box;
            padding: 0;
            text-align: center;
            border-right: 1px solid var(--schedule-border);
            border-bottom: 1px solid var(--schedule-border);
        }

        /* Колонка залов */

        .schedule-table .room-column {
            position: sticky;
            left: 0;
            z-index: 5;
            width: 145px;
            min-width: 145px;
            max-width: 145px;
            padding: 10px 12px;
            text-align: left;
            white-space: normal;
            background: #fff;
            border-right: 2px solid #d5dce7;
            font-weight: 600;
            color: #344054;
        }

        .room-name {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .room-icon {
            width: 28px;
            height: 28px;
            min-width: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #eef4ff;
            color: #4778d0;
            font-size: 13px;
        }

        /* Заголовки */

        .schedule-table thead th {
            position: sticky;
            z-index: 4;
            background: #f8fafc;
            color: #667085;
        }

        .schedule-table thead tr:first-child th {
            top: 0;
            height: 38px;
        }

        .schedule-table thead tr:nth-child(2) th {
            top: 38px;
            height: 30px;
        }

        .schedule-table thead .room-column {
            z-index: 7;
            background: #f1f5f9;
            vertical-align: middle;
        }

        .schedule-table .hour-header {
            width: 104px;
            min-width: 104px;
            font-size: 12px;
            font-weight: 700;
            color: #344054;
            border-bottom: 1px solid #dce3ed;
        }

        .schedule-table .quarter-header {
            width: 26px;
            min-width: 26px;
            font-size: 10px;
            font-weight: 500;
            color: #98a2b3;
        }

        /* Ячейки */

        .schedule-table .quarter-cell {
            width: 26px;
            min-width: 26px;
            height: 42px;
            padding: 4px;
            background: #fff;
        }

        .slot-indicator {
            display: block;
            width: 100%;
            height: 100%;
            min-height: 30px;
            border-radius: 4px;
            transition: background .15s ease;
        }

        .schedule-table td.free-cell .slot-indicator {
            background: var(--schedule-green);
        }

        .schedule-table td.busy-cell .slot-indicator {
            background: var(--schedule-red);
        }

        .schedule-table td.free-cell:hover .slot-indicator {
            background: #a6e8c1;
        }

        .schedule-table td.busy-cell:hover .slot-indicator {
            background: #f5b5b5;
        }

        .schedule-table tbody tr:hover .room-column {
            background: #f8fafc;
        }

        .schedule-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .schedule-table tbody tr:last-child .room-column {
            border-bottom: 0;
        }

        .schedule-footer {
            padding: 12px 20px;
            border-top: 1px solid var(--schedule-border);
            color: #98a2b3;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* Адаптивность */

        @media (max-width: 768px) {
            .vault-page {
                padding: 10px;
            }

            .vault-heading h1 {
                font-size: 20px;
            }

            .schedule-table .room-column {
                width: 110px;
                min-width: 110px;
                max-width: 110px;
                padding: 7px;
                font-size: 11px;
            }

            .schedule-table .quarter-cell {
                height: 34px;
            }

            .slot-indicator {
                min-height: 24px;
            }
        }

        /* Печать */

        @media print {
            @page {
                size: landscape;
                margin: 7mm;
            }

            body {
                background: #fff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .main-sidebar,
            .main-header,
            .main-footer,
            .content-header,
            .no-print,
            .vault-print-btn {
                display: none !important;
            }

            .vault-page,
            .content-wrapper,
            .content,
            .vault-card,
            .schedule-wrapper {
                width: 100% !important;
                max-width: none !important;
                max-height: none !important;
                height: auto !important;
                overflow: visible !important;
                position: static !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            .schedule-table {
                width: 100% !important;
                min-width: 0 !important;
                table-layout: auto !important;
                font-size: 7px !important;
                border-collapse: collapse !important;
            }

            .schedule-table th,
            .schedule-table td {
                position: static !important;
                width: auto !important;
                min-width: 0 !important;
                height: 24px !important;
                border: 1px solid #ccc !important;
            }

            .schedule-table .room-column {
                width: 90px !important;
                min-width: 90px !important;
                max-width: 90px !important;
            }

            .schedule-table thead {
                display: table-header-group !important;
            }

            .schedule-table td.busy-cell .slot-indicator {
                background: var(--schedule-red) !important;
            }

            .schedule-table td.free-cell .slot-indicator {
                background: var(--schedule-green) !important;
            }

            .vault-card-header {
                padding: 0 0 10px !important;
                border: none !important;
            }

            .schedule-table tr {
                break-inside: avoid !important;
            }
        }
    </style>

    <div class="content-wrapper">
        <section class="content">
            <div class="vault-page">

                <div class="vault-heading">
                    <div>
                        <h1>
                            <i class="fas fa-calendar-alt text-primary mr-2"></i>
                            График загруженности
                        </h1>
                        <div class="vault-subtitle">
                            Расписание залов на {{ \Carbon\Carbon::today()->format('d.m.Y') }}
                        </div>
                    </div>

                    <button type="button"
                            class="btn btn-primary vault-print-btn no-print"
                            onclick="window.print()">
                        <i class="fas fa-print mr-1"></i>
                        Распечатать
                    </button>
                </div>

                <div class="vault-card">

                    <div class="vault-card-header">
                        <h3>
                            <i class="fas fa-clock text-primary mr-2"></i>
                            Расписание по времени
                        </h3>

                        <div class="schedule-legend">
                            <div class="legend-item">
                                <span class="legend-color legend-free"></span>
                                Свободно
                            </div>

                            <div class="legend-item">
                                <span class="legend-color legend-busy"></span>
                                Занято
                            </div>

                            {{--<div class="legend-item">
                                <i class="fas fa-grip-lines text-muted"></i>
                                Шаг 15 минут
                            </div>--}}
                        </div>
                    </div>

                    <div class="schedule-wrapper">
                        <table id="roomVault" class="schedule-table">
                            <thead>
                            <tr>
                                <th class="room-column" rowspan="2">
                                    <i class="fas fa-door-open mr-1"></i>
                                    Зал
                                </th>

                                @for ($hour = 7; $hour < 22; $hour++)
                                    <th colspan="4" class="hour-header">
                                        {{ sprintf('%02d:00', $hour) }}
                                    </th>
                                @endfor
                            </tr>

                            <tr>
                                @for ($hour = 7; $hour < 22; $hour++)
                                    @foreach ([0, 15, 30, 45] as $minute)
                                        <th class="quarter-header">
                                            {{ sprintf('%02d', $minute) }}
                                        </th>
                                    @endforeach
                                @endfor
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($schedule as $row)
                                <tr>
                                    <td class="room-column">
                                        <div class="room-name">
                                            <span class="room-icon">
                                                <i class="fas fa-door-open"></i>
                                            </span>
                                            <span>{{ $row['title'] }}</span>
                                        </div>
                                    </td>

                                    @foreach ($row['slots'] as $time => $slot)

                                        @php
                                            $busy = $slot['busy'];

                                            $tooltip = $busy
                                                ? collect($slot['trainings'])->map(function ($training) {
                                                    $text = '';

                                                    if (!empty($training['profile'])) {
                                                        $text .= ' Направление: ' . $training['profile'] . PHP_EOL;
                                                    }

                                                    $text .= ' | Тренер: ' . ($training['coach'] ?: 'Не указан') . PHP_EOL;

                                                    $text .= ' | Время: ' . $training['time_start']
                                                        . ' - ' . $training['time_end'] . PHP_EOL;

                                                    if (!empty($training['comment'])) {
                                                        $text .= ' | Комментарий: ' . $training['comment'];
                                                    }

                                                    return $text;
                                                })->implode("\n\n")
                                                : 'Свободно · ' . $time;
                                        @endphp

                                        <td
                                            class="quarter-cell {{ $busy ? 'busy-cell' : 'free-cell' }}"
                                            title="{{ $tooltip }}"
                                        >
                                            <span class="slot-indicator"></span>
                                        </td>

                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="schedule-footer">
                    <span>
                        <i class="fas fa-info-circle mr-1"></i>
                        Каждая ячейка соответствует 15 минутам
                    </span>
                        <span>
                        Всего залов: {{ count($schedule) }}
                    </span>
                    </div>

                </div>
            </div>
        </section>
    </div>

@endsection
