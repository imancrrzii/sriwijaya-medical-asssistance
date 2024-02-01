<!-- resources/views/article/print.blade.php -->
@extends('layouts.app')

@section('content')
    <style>
        @media print {
            .nk-header, .nk-footer, .nk-sidebar {
                display: none !important;
            }
        }
    </style>

    <div class="container">
        <h2>Data Pasien</h2>
        <h4>{{ $patient->name }}</h4>
    </div>
@endsection
