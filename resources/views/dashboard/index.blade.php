@extends('layouts.app')

@push('js')
    <script src="{{ asset('assets/js/charts/chart-sales.js?ver=3.0.3') }}"></script>
    <script src="{{ asset('assets/js/example-chart.js?ver=3.0.3') }}"></script>
    <script>
        var data = @json($patientData);
        var uniqueDates = @json($uniqueDates);

        var tableNumbers = [...new Set(data.map(item => item.table_number))];
        var latestDates = uniqueDates.slice(-7);
        var labels = latestDates

        var colors = ['#dbdfea', '#b7c2d0', '#8091a7', '#3c4d62']
        var datasets = tableNumbers.map((tableNumber, index) => {
            var values = latestDates.map(date => {
                var entry = data.find(item => item.table_number === tableNumber && item.date === date);
                return entry ? entry.total : 0;
            });

            return {
                label: 'Meja ' + tableNumber,
                color: colors[index],
                borderWidth: 1,
                data: values,
            };
        });

        var patientOverview = {
            labels: labels,
            dataUnit: 'pasien',
            datasets: datasets
        };

        function patientOverviewChart(selector, set_data) {
            var $selector = selector ? $(selector) : $('.patient-chart');
            $selector.each(function() {
                var $self = $(this),
                    _self_id = $self.attr('id'),
                    _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data,
                    _d_legend = typeof _get_data.legend === 'undefined' ? false : _get_data.legend;

                var selectCanvas = document.getElementById(_self_id).getContext("2d");
                var chart_data = [];

                for (var i = 0; i < _get_data.datasets.length; i++) {
                    chart_data.push({
                        label: _get_data.datasets[i].label,
                        data: _get_data.datasets[i].data,
                        // Styles
                        backgroundColor: _get_data.datasets[i].color,
                        borderWidth: 2,
                        borderColor: 'transparent',
                        hoverBorderColor: 'transparent',
                        borderSkipped: 'bottom',
                        barPercentage: .8,
                        categoryPercentage: .6
                    });
                }

                var chart = new Chart(selectCanvas, {
                    type: 'bar',
                    data: {
                        labels: _get_data.labels,
                        datasets: chart_data
                    },
                    options: {
                        legend: {
                            display: _get_data.legend ? _get_data.legend : false,
                            rtl: NioApp.State.isRTL,
                            labels: {
                                boxWidth: 30,
                                padding: 20,
                                fontColor: '#6783b8'
                            }
                        },
                        maintainAspectRatio: false,
                        tooltips: {
                            enabled: true,
                            rtl: NioApp.State.isRTL,
                            callbacks: {
                                title: function title(tooltipItem, data) {
                                    return data.datasets[tooltipItem[0].datasetIndex].label;
                                },
                                label: function label(tooltipItem, data) {
                                    return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem[
                                        'index']] + ' ' + _get_data.dataUnit;
                                }
                            },
                            backgroundColor: '#1c2b46',
                            titleFontSize: 13,
                            titleFontColor: '#fff',
                            titleMarginBottom: 6,
                            bodyFontColor: '#fff',
                            bodyFontSize: 12,
                            bodySpacing: 4,
                            yPadding: 10,
                            xPadding: 10,
                            footerMarginTop: 0,
                            displayColors: false
                        },
                        scales: {
                            yAxes: [{
                                display: true,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                position: NioApp.State.isRTL ? "right" : "left",
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 11,
                                    fontColor: '#9eaecf',
                                    padding: 10,
                                    callback: function callback(value, index, values) {
                                        return value;
                                    },
                                    stepSize: 1200
                                },
                                gridLines: {
                                    color: NioApp.hexRGB("#526484", .2),
                                    tickMarkLength: 0,
                                    zeroLineColor: NioApp.hexRGB("#526484", .2)
                                }
                            }],
                            xAxes: [{
                                display: true,
                                stacked: _get_data.stacked ? _get_data.stacked : false,
                                ticks: {
                                    fontSize: 9,
                                    fontColor: '#9eaecf',
                                    source: 'auto',
                                    padding: 10,
                                    reverse: NioApp.State.isRTL
                                },
                                gridLines: {
                                    color: "transparent",
                                    tickMarkLength: 0,
                                    zeroLineColor: 'transparent'
                                }
                            }]
                        }
                    }
                });
            });
        } // init chart


        NioApp.coms.docReady.push(function() {
            patientOverviewChart();
        });
    </script>
@endpush

@section('content')
    <div class="col-md-12">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Dashboard</h4>
                </div>
            </div>
        </div>
        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <div class="row">
                    @canany(['admin-table-1-monitoring-all'])
                        <div class="col-md-3 my-4">
                            <a href="{{ route('patient.index', ['tableNumber' => 1]) }}">
                                <div class="card card-bordered card-preview shadow-sm">
                                    <div class="card-inner">
                                        <div class="team">
                                            <div class="user-card user-card-s2">
                                                <div class="user-avatar md bg-gray-300 text-black">
                                                    <em class="icon ni ni-user"></em>
                                                    <div class="status dot dot-lg dot-success"></div>
                                                </div>
                                                <div class="user-info">
                                                    <h6>Table 1</h6>
                                                </div>
                                            </div>
                                            <ul class="team-statistics">
                                                <li><span>{{ $dataTable1s }}</span><span>Pasien</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcanany


                    @canany(['admin-table-2-monitoring-all'])
                        <div class="col-md-3 my-4">
                            <a href="{{ route('patient.index', ['tableNumber' => 2]) }}">
                                <div class="card card-bordered card-preview shadow-sm">
                                    <div class="card-inner">
                                        <div class="team">
                                            <div class="user-card user-card-s2">
                                                <div class="user-avatar md bg-gray-400 text-black">
                                                    <em class="icon ni ni-user"></em>
                                                    <div class="status dot dot-lg dot-success"></div>
                                                </div>
                                                <div class="user-info">
                                                    <h6>Table 2</h6>
                                                </div>
                                            </div>
                                            <ul class="team-statistics">
                                                <li><span>{{ $dataTable2s }}</span><span>Pasien</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcanany

                    @canany(['admin-table-3-monitoring-all'])
                        <div class="col-md-3 my-4">
                            <a href="{{ route('patient.index', ['tableNumber' => 3]) }}">
                                <div class="card card-bordered card-preview shadow-sm">
                                    <div class="card-inner">
                                        <div class="team">
                                            <div class="user-card user-card-s2">
                                                <div class="user-avatar md bg-gray-500">
                                                    <em class="icon ni ni-user"></em>
                                                    <div class="status dot dot-lg dot-success"></div>
                                                </div>
                                                <div class="user-info">
                                                    <h6>Table 3</h6>
                                                </div>
                                            </div>
                                            <ul class="team-statistics">
                                                <li><span>{{ $dataTable3s }}</span><span>Pasien</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcanany

                    @canany(['admin-table-4-monitoring-all'])
                        <div class="col-md-3 my-4">
                            <a href="{{ route('patient.index', ['tableNumber' => 4]) }}">
                                <div class="card card-bordered card-preview shadow-sm">
                                    <div class="card-inner">
                                        <div class="team">
                                            <div class="user-card user-card-s2">
                                                <div class="user-avatar md bg-gray-600">
                                                    <em class="icon ni ni-user"></em>
                                                    <div class="status dot dot-lg dot-success"></div>
                                                </div>
                                                <div class="user-info">
                                                    <h6>Admin 4</h6>
                                                </div>
                                            </div>
                                            <ul class="team-statistics">
                                                <li><span>{{ $dataTable4s }}</span><span>Pasien</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcanany
                    @can('admin-monitoring-all')
                    <div class="col-lg-12">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <div class="card-title-group align-start mb-3">
                                    <div class="card-title">
                                        <h6 class="title">Grafik pasien</h6>
                                        <p>7 hari terakhir</p>
                                    </div>
                                </div><!-- .card-title-group -->
                                <div class="nk-order-ovwg">
                                    <div class="row g-4 align-end">
                                        <div class="col-xxl-8">
                                            <div class="nk-order-ovwg-ck">
                                                <canvas class="patient-chart" id="patientOverview"></canvas>
                                            </div>
                                        </div><!-- .col -->
                                    </div>
                                </div><!-- .nk-order-ovwg -->
                            </div><!-- .card-inner -->
                        </div><!-- .card -->
                    </div><!-- .col -->
                        
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
