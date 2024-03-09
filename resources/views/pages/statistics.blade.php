@extends('pages.layout')

@section('title', 'Statistieken')

@section('content')
    <h1 class="mt-5">Statistieken</h1>

    <div class="col-12 mb-3">
        <span>Op dit moment staan er <span class="text-primary fw-bold">{{ $totalAEDs }}</span> AED's in de
            database in <span class="text-primary fw-bold">{{ $distinctCities }}</span> verschillende woonplaatsen.</span>

        <div class="row">
            <div class="col-12 col-md-4">
                <h3 class="mt-4">Per provincie</h3>
                <table class="table table-striped table-fixed">
                    <thead>
                        <tr>
                            <th class="col-8">Provincie</th>
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
                <h3 class="mt-4">Per woonplaats</h3>
                <table class="table table-striped table-fixed">
                    <thead>
                        <tr>
                            <th class="col-8">Stad</th>
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
                <h3 class="mt-4">Per beheerder</h3>
                <table class="table table-striped table-fixed">
                    <thead>
                        <tr>
                            <th class="col-8">Beheerder</th>
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
