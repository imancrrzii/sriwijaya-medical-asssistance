@extends('layouts.app')

@section('content')
    <div class="col-md-12">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Dashboard</h4>
                </div>
            </div>
        </div>
        <div class="row">
            @canany(['admin-table-1-monitoring-all'])
                <div class="col-md-3 my-4">
                    <a href="{{ route('patient.index', ['tableNumber' => 1]) }}">
                        <div class="card card-bordered card-preview shadow-sm">
                            <div class="card-inner">
                                <div class="team">
                                    <div class="user-card user-card-s2">
                                        <div class="user-avatar md bg-primary">
                                            <em class="icon ni ni-user"></em>
                                            <div class="status dot dot-lg dot-success"></div>
                                        </div>
                                        <div class="user-info">
                                            <h6>Admin 1</h6>
                                        </div>
                                    </div>
                                    <ul class="team-statistics">
                                        <li><span >{{ $dataTable1s }}</span><span>Pasien</span></li>
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
                                    <div class="user-avatar md bg-secondary">
                                        <em class="icon ni ni-user"></em>
                                        <div class="status dot dot-lg dot-success"></div>
                                    </div>
                                    <div class="user-info">
                                        <h6>Admin 2</h6>
                                    </div>
                                </div>
                                <ul class="team-statistics">
                                    <li><span >{{ $dataTable2s }}</span><span>Pasien</span></li>
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
                                    <div class="user-avatar md bg-warning text-black">
                                        <em class="icon ni ni-user"></em>
                                        <div class="status dot dot-lg dot-success"></div>
                                    </div>
                                    <div class="user-info">
                                        <h6>Admin 3</h6>
                                    </div>
                                </div>
                                <ul class="team-statistics">
                                    <li><span >{{ $dataTable3s }}</span><span>Pasien</span></li>
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
                                    <div class="user-avatar md bg-danger">
                                        <em class="icon ni ni-user"></em>
                                        <div class="status dot dot-lg dot-success"></div>
                                    </div>
                                    <div class="user-info">
                                        <h6>Admin 4</h6>
                                    </div>
                                </div>
                                <ul class="team-statistics">
                                    <li><span >{{ $dataTable4s }}</span><span>Pasien</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endcanany
        </div>
    </div>
@endsection
