@extends('pages.layout')

@section('title', __('pages.statistics'))

@section('content')
    <h1 class="mt-5">{{ __('pages.statistics') }}</h1>

    <div class="col-12 mb-3">
        {!! __('statistics.current', [
            'amount' => '<span class="text-primary fw-bold">' . $totalAEDs . '</span>',
            'cities' => '<span class="text-primary fw-bold">' . $distinctCities . '</span>',
        ]) !!}

        <div class="row">
            <div class="col-12 col-md-4">
                <h3 class="mt-4">{{ __('statistics.per_province') }}</h3>
                <table class="table table-striped table-fixed">
                    <thead>
                        <tr>
                            <th class="col-8">{{ __('statistics.province') }}</th>
                            <th class="col-4">AED's</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aedPerProvince as $aeds)
                            <tr>
                                <td class="col-8">{{ $aeds->province }}</td>
                                <td class="col-4">{{ $aeds->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-md-4">
                <h3 class="mt-4">{{ __('statistics.per_city') }}</h3>
                <table class="table table-striped table-fixed">
                    <thead>
                        <tr>
                            <th class="col-8">{{ __('statistics.city') }}</th>
                            <th class="col-4">AED's</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aedPerCity as $aeds)
                            <tr>
                                <td class="col-8">{{ $aeds->city }} ({{ $aeds->province }})</td>
                                <td class="col-4">{{ $aeds->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-md-4">
                <h3 class="mt-4">{{ __('statistics.per_operator') }}</h3>
                <table class="table table-striped table-fixed">
                    <thead>
                        <tr>
                            <th class="col-8">{{ __('statistics.operator') }}</th>
                            <th class="col-4">AED's</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aedPerOperator as $aeds)
                            <tr>
                                <td class="col-8">{{ $aeds->operator }}</td>
                                <td class="col-4">{{ $aeds->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
