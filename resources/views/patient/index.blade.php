@extends('layouts.app')

@push('js')
    <script src="{{ asset('assets/js/libs/datatable-btns.js?ver=3.0.3') }}"></script>
    <script src="{{ asset('assets/js/example-toastr.js?ver=3.0.3') }}"></script>
    <script>
        $(document).ready(function() {
            // Fill input fields with patient data on edit modal
            $(document).on('show.bs.modal', '#editPatientModal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const modal = $(this);
                const form = modal.find('#editForm');

                $.getJSON('{{ route('patient.get', ':id') }}'.replace(':id', id), function(data) {
                    form.find('#name').val(data.name);
                    form.find('#age').val(data.age);
                    form.find('input[name="gender"][value="' + data.gender + '"]').prop('checked',
                        true);
                    form.find('#add_address').val(data.address);
                    form.find('#systolic_blood_pressure').val(data.systolic_blood_pressure !==
                        null ? data.systolic_blood_pressure : '-');
                    form.find('#diastolic_blood_pressure').val(data.diastolic_blood_pressure !==
                        null ? data.diastolic_blood_pressure : '-');
                    form.find('input[name="blood_glucose_type"][value="' + data.blood_glucose_type +
                        '"]').prop('checked',
                        true);
                    form.find('#add_blood_glucose').val(data.blood_glucose !== null ? data
                        .blood_glucose : '-');
                    form.find('#add_uric_acid').val(data.uric_acid !== null ? data.uric_acid : '-');
                    form.find('#add_cholesterol').val(data.cholesterol !== null ? data.cholesterol :
                        '-');


                });

                form.attr('action', '{{ route('patient.update', ':id') }}'.replace(':id', id));
            });

            // Fill input fields with patient data on show modal
            $(document).on('show.bs.modal', '#showPatientModal', function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const modal = $(this);
                const form = modal.find('#editForm');
                const printLink = modal.find('.print');

                printLink.attr('href', '{{ route('patient.print', ':id') }}'.replace(':id', id));

                $.getJSON('{{ route('patient.get', ':id') }}'.replace(':id', id), function(data) {
                    form.find('#name').val(data.name);
                    form.find('#age').val(data.age);
                    form.find('input[name="gender"][value="' + data.gender + '"]').prop('checked',
                        true);
                    form.find('#add_address').val(data.address);
                    form.find('#systolic_blood_pressure').val(data.systolic_blood_pressure !==
                        null ? data.systolic_blood_pressure : '-');
                    form.find('#diastolic_blood_pressure').val(data.diastolic_blood_pressure !==
                        null ? data.diastolic_blood_pressure : '-');
                    form.find('input[name="blood_glucose_type"][value="' + data.blood_glucose_type +
                        '"]').prop('checked',
                        true);
                    form.find('#add_blood_glucose').val(data.blood_glucose !== null ? data
                        .blood_glucose : '-');
                    form.find('#add_uric_acid').val(data.uric_acid !== null ? data.uric_acid : '-');
                    form.find('#add_cholesterol').val(data.cholesterol !== null ? data.cholesterol :
                        '-');
                });
            });

            // Remove dashes from input fields after submit
            $('#editForm').submit(function() {
                $('.remove-dash').each(function() {
                    var inputValue = $(this).val();
                    $(this).val(inputValue.replace(/-/g, ''));
                });
            });

            // Fill input fields with patient data on delete modal
            $(document).on('show.bs.modal', '#deletePatientModal', async function(event) {
                const button = $(event.relatedTarget);
                const id = button.data('id');
                const modal = $(this);
                const form = modal.find('#deleteForm');

                $.ajax({
                    url: '{{ route('patient.get', ':id') }}'.replace(':id', id),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        form.find('#deleteMessage').html(
                            `Apakah anda yakin ingin menghapus <strong>${data.name}</strong> sebagai <strong>pasien</strong>?`
                        );
                    },
                    error: function(xhr, status, error) {
                        alert('Data tiak ditemukan');
                    }
                });

                form.attr('action', '{{ route('patient.delete', ':id') }}'.replace(':id', id));
            });

            // Make datatable scrollable
            $('.datatable-wrap').each(function(index) {
                const id = 'datatable-' + index;
                $(this).attr('id', id);
                const datatableWrap = $("#" + id);
                const wrappingDiv = $("<div>").addClass("w-100").css("overflow-x",
                    "scroll");
                datatableWrap.children().appendTo(wrappingDiv);
                datatableWrap.append(wrappingDiv);
            });

            // Toastr
            @if (session()->has('success'))
                let message = @json(session('success'));
                NioApp.Toast(`<h5>Berhasil</h5><p>${message}</p>`, 'success', {
                    position: 'top-right',
                });
            @endif
        });

        // Print patient data
        function printAndPreview(id) {
            fetch(`{{ route('patient.print', ':id') }}`.replace(':id', id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.text();
                    }
                    throw new Error('Network response was not ok.');
                })
                .then(data => {
                    const previewWindow = window.open('', '_blank');
                    previewWindow.document.write(data);
                    previewWindow.document.close();
                })
                .catch(error => {
                    console.error('There was an error with the fetch operation:', error);
                });
        }
    </script>
    {{-- Pusher for realtime data --}}
    @can('admin-monitoring-all')
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            var pusher = new Pusher('10d74ebf78b84195e080', {
                cluster: 'ap1'
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(response) {
                NioApp.Toast(response.message, 'info', {
                    position: 'top-right',
                    timeOut: 0,
                });

                let tableNumber = response.data.table_number;
                let dataTable = $(`#patient-table-${tableNumber}`).DataTable();

                $(`#patient-table-${tableNumber} tbody tr.dataTables_empty`).remove();

                dataTable.row.add([
                    `<td class="text-center">${dataTable.rows().count() + 1}</td>`,
                    `<td>${response.data.name}</td>`,
                    `<td class="text-center">${response.data.age}</td>`,
                    `<td class="text-center">
                        <button class="btn btn-primary btn-xs rounded-pill btn-dim"
                            data-bs-toggle="modal" data-bs-target="#showPatientModal"
                            data-id="${response.data.id}">
                            <em class="icon ni ni-eye-fill"></em>
                        </button>
                        <button onclick="printAndPreview('${response.data.id}')"
                            class="btn ${response.data.is_printed ? 'btn-dark' : 'btn-danger'} btn-primary btn-xs rounded-pill btn-dim">
                            <em class="icon ni ni-printer-fill"></em>
                        </button>
                    </td>`
                ]).draw();

                dataTable.column(0).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });

                dataTable.draw().columns().every(function(index) {
                    if (index !== 1) {
                        this.nodes().to$().addClass('text-center');
                    }
                });
            });
        </script>
    @endcan
@endpush

@section('content')

    @can('admin-monitoring-all')
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="components-preview wide-xl mx-auto mb-4">
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head">
                            </div>
                            <div class="card card-bordered card-preview">
                                <div class="card-inner">
                                    <table
                                        class="datatable-init-export nk-tb-list nk-tb-ulist table table-hover table-bordered table-responsive-md"
                                        data-export-title="Export" data-auto-responsive="false" id="patient-table-1">
                                        <thead>
                                            <tr class="table-light">
                                                <th class="text-center" colspan="4">Meja 1</th>
                                            </tr>
                                            <tr class="table-white">
                                                <th class="text-center col-1">No</th>
                                                <th class="text-center col-1">Nama</th>
                                                <th class="text-center col-1">Usia</th>
                                                <th class="text-center no-export col-1">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-1">
                                            @foreach ($patient1 as $index => $patient)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $patient->name }}</td>
                                                    <td class="text-center">{{ $patient->age }}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary btn-xs rounded-pill btn-dim"
                                                            data-bs-toggle="modal" data-bs-target="#showPatientModal"
                                                            data-id="{{ $patient->id }}">
                                                            <em class="icon ni ni-eye-fill"></em>
                                                        </button>
                                                        <button onclick="printAndPreview('{{ $patient->id }}')"
                                                            class="btn {{ $patient->is_printed ? 'btn-dark' : 'btn-danger' }} btn-primary btn-xs rounded-pill btn-dim">
                                                            <em class="icon ni ni-printer-fill"></em>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="components-preview wide-xl mx-auto mb-4">
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head">
                            </div>
                            <div class="card card-bordered card-preview">
                                <div class="card-inner">
                                    <table
                                        class="datatable-init-export nk-tb-list nk-tb-ulist table table-hover table-bordered table-responsive-md"
                                        data-export-title="Export" data-auto-responsive="false" id="patient-table-2">
                                        <thead>
                                            <tr class="table-light">
                                                <th class="text-center" colspan="4">Meja 2</th>
                                            </tr>
                                            <tr class="table-white">
                                                <th class="text-center col-1">No</th>
                                                <th class="text-center col-1">Nama</th>
                                                <th class="text-center col-1">Usia</th>
                                                <th class="text-center no-export col-1">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-2">
                                            @foreach ($patient2 as $index => $patient)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $patient->name }}</td>
                                                    <td class="text-center">{{ $patient->age }}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary btn-xs rounded-pill btn-dim"
                                                            data-bs-toggle="modal" data-bs-target="#showPatientModal"
                                                            data-id="{{ $patient->id }}">
                                                            <em class="icon ni ni-eye-fill"></em>
                                                        </button>
                                                        <button onclick="printAndPreview('{{ $patient->id }}')"
                                                            class="btn {{ $patient->is_printed ? 'btn-dark' : 'btn-danger' }} btn-primary btn-xs rounded-pill btn-dim">
                                                            <em class="icon ni ni-printer-fill"></em>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="components-preview wide-xl mx-auto mb-4">
                        <div class="nk-block nk-block-lg">
                            <div class="nk-block-head">
                            </div>
                            <div class="card card-bordered card-preview">
                                <div class="card-inner">
                                    <table
                                        class="datatable-init-export nk-tb-list nk-tb-ulist table table-hover table-bordered table-responsive-md"
                                        data-export-title="Export" data-auto-responsive="false" id="patient-table-3">
                                        <thead>
                                            <tr class="table-light">
                                                <th class="text-center" colspan="4">Meja 3</th>
                                            </tr>
                                            <tr class="table-white">
                                                <th class="text-center col-1">No</th>
                                                <th class="text-center col-1">Nama</th>
                                                <th class="text-center col-1">Usia</th>
                                                <th class="text-center no-export col-1">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-3">
                                            @foreach ($patient3 as $index => $patient)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $patient->name }}</td>
                                                    <td class="text-center">{{ $patient->age }}</td>
                                                    <td class="text-center">
                                                        <button class="btn btn-primary btn-xs rounded-pill btn-dim"
                                                            data-bs-toggle="modal" data-bs-target="#showPatientModal"
                                                            data-id="{{ $patient->id }}">
                                                            <em class="icon ni ni-eye-fill"></em>
                                                        </button>
                                                        <button onclick="printAndPreview('{{ $patient->id }}')"
                                                            class="btn {{ $patient->is_printed ? 'btn-dark' : 'btn-danger' }} btn-primary btn-xs rounded-pill btn-dim">
                                                            <em class="icon ni ni-printer-fill"></em>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @can('admin-table')
        <div class="components-preview wide-xl mx-auto">
            <div class="nk-block nk-block-lg">
                <div class="nk-block-head">
                </div>
                <div class="card card-bordered card-preview">
                    <div class="card-inner">
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                            data-bs-target="#addPatientModal" data-modal-title="Tambah Pasien">
                            <span class="ni ni-plus"></span>
                            <span class="ms-1">Tambah pasien</span>
                        </button>
                        <table
                            class="datatable-init-export nk-tb-list nk-tb-ulist table table-hover table-bordered table-responsive-md"
                            data-export-title="Export" data-auto-responsive="false">
                            <thead>
                                <tr class="table-light">
                                    <th class="text-center col-1">No</th>
                                    <th class="text-center col-6">Nama</th>
                                    <th class="text-center col-2">Usia</th>
                                    <th class="text-center no-export col-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patients as $index => $patient)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $patient->name }}</td>
                                        <td class="text-center">{{ $patient->age }}</td>
                                        <td class="text-center text-nowrap">
                                            <button class="btn btn-primary btn-xs rounded-pill btn-dim" data-bs-toggle="modal"
                                                data-bs-target="#showPatientModal" data-id="{{ $patient->id }}">
                                                <em class="icon ni ni-eye-fill"></em>
                                            </button>
                                            <button class="btn btn-warning btn-xs rounded-pill btn-dim" data-bs-toggle="modal"
                                                data-bs-target="#editPatientModal" data-modal-title="Edit Konseptor"
                                                data-id="{{ $patient->id }}">
                                                <em class="icon ni ni-edit-fill"></em>
                                            </button>
                                            <button class="btn btn-danger btn-xs rounded-pill btn-dim" data-bs-toggle="modal"
                                                data-bs-target="#deletePatientModal" data-id="{{ $patient->id }}">
                                                <em class="icon ni ni-trash-fill"></em>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    {{-- Add Modal --}}
    <div class="modal fade" id="addPatientModal">
        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Pasien</h5>
                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{ route('patient.store') }}" method="POST" class="form-validate is-alter">
                        @csrf
                        <h5 class="mb-3">Identitas</h5>
                        <div class="form-group row">
                            <label for="add_name" class="col-md-3 col-form-label">Nama Pasien
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="add_name" name="name"
                                        placeholder="Masukkan nama pasien" autocomplete="off" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_age" class="col-md-3 col-form-label">Umur Pasien
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <input type="number" class="form-control" id="add_age" name="age"
                                        placeholder="Masukkan umur pasien" autocomplete="off" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row gy-4">
                            <label class="col-md-3 col-form-label">Jenis Kelamin :</label>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="customRadio1" name="gender" value="Laki-laki"
                                            autocomplete="off" required class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio1">Laki-laki</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="customRadio2" name="gender" value="Perempuan"
                                            autocomplete="off" required class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio2">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <label for="add_address" class="col-md-3 col-form-label">Alamat Pasien
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <textarea type="text" class="form-control" id="add_address" name="address" placeholder="Masukkan alamat pasien" autocomplete="off"
                                        autocomplete="off" required></textarea>
                                </div>
                            </div>
                        </div>
                        <h5 class="my-3">Hasil Pemeriksaan</h5>
                        <div class="form-group row">
                            <label for="systolic_blood_pressure" class="col-md-3 col-form-label">Tekanan darah :</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="number" class="form-control" name="systolic_blood_pressure"
                                        id="systolic_blood_pressure" placeholder="Sistolik" autocomplete="off">
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text">/</span>
                                    </div>
                                    <input type="number" class="form-control" name="diastolic_blood_pressure"
                                        id="diastolic_blood_pressure" placeholder="Diastolik" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row gy-4">
                            <label class="col-md-3 col-form-label">Jenis gula darah :</label>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="blood_glucose_type1" name="blood_glucose_type" autocomplete="off" required
                                            value="GDS" class="custom-control-input">
                                        <label class="custom-control-label" for="blood_glucose_type1">GDS</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="blood_glucose_type2" name="blood_glucose_type" autocomplete="off" required
                                            value="GDP" class="custom-control-input">
                                        <label class="custom-control-label" for="blood_glucose_type2">GDP</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="add_blood_glucose" class="col-md-3 col-form-label">Gula
                                darah :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="add_blood_glucose"
                                            name="blood_glucose" placeholder="Masukkan gula darah pasien" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mg/dL</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_uric_acid" class="col-md-3 col-form-label">Asam urat
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="add_uric_acid" name="uric_acid"
                                            placeholder="Masukkan asam urat pasien" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mg/dL</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_cholesterol" class="col-md-3 col-form-label">Kolesterol :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="add_cholesterol"
                                            name="cholesterol" placeholder="Masukkan kolesterol pasien" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mg/dL</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary"><em class="ni ni-save me-1"></em>
                                Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editPatientModal">
        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Pasien</h5>
                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" class="form-validate is-alter" id="editForm">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label">Nama Pasien
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Masukkan nama pasien" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="age" class="col-md-3 col-form-label">Umur Pasien
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <input type="number" class="form-control" id="age" name="age"
                                        placeholder="Masukkan umur pasien" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row gy-4">
                            <label class="col-md-3 col-form-label">Jenis Kelamin :</label>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="editCustomRadio1" name="gender" value="Laki-laki"
                                            autocomplete="off" required class="custom-control-input">
                                        <label class="custom-control-label" for="editCustomRadio1">Laki-laki</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="editCustomRadio2" name="gender" value="Perempuan"
                                            autocomplete="off" required class="custom-control-input">
                                        <label class="custom-control-label" for="editCustomRadio2">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-3">
                            <label for="add_address" class="col-md-3 col-form-label">Alamat Pasien
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <textarea type="text" class="form-control" id="add_address" name="address" placeholder="Masukkan alamat pasien"
                                        autocomplete="off" required></textarea>
                                </div>
                            </div>
                        </div>
                        <h5 class="my-3">Hasil Pemeriksaan</h5>
                        <div class="form-group row">
                            <label for="systolic_blood_pressure" class="col-md-3 col-form-label">Tekanan darah :</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control remove-dash" name="systolic_blood_pressure"
                                        id="systolic_blood_pressure" placeholder="Sistolik" autocomplete="off">
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text">/</span>
                                    </div>
                                    <input type="text" class="form-control remove-dash"
                                        name="diastolic_blood_pressure" id="diastolic_blood_pressure"
                                        placeholder="Diastolik" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row gy-4">
                            <label class="col-md-3 col-form-label">Jenis gula darah :</label>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="blood_glucose_type1" name="blood_glucose_type" autocomplete="off" required
                                            value="GDS" class="custom-control-input">
                                        <label class="custom-control-label" for="blood_glucose_type1">GDS</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="blood_glucose_type2" name="blood_glucose_type" autocomplete="off" required
                                            value="GDP" class="custom-control-input">
                                        <label class="custom-control-label" for="blood_glucose_type2">GDP</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="add_blood_glucose" class="col-md-3 col-form-label">Gula
                                darah :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <div class="input-group">
                                        <input type="text" class="form-control remove-dash" id="add_blood_glucose"
                                            name="blood_glucose" placeholder="Masukkan gula darah pasien" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mg/dL</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_uric_acid" class="col-md-3 col-form-label">Asam urat
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <div class="input-group">
                                        <input type="text" class="form-control remove-dash" id="add_uric_acid"
                                            name="uric_acid" placeholder="Masukkan asam urat pasien" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mg/dL</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_cholesterol" class="col-md-3 col-form-label">Kolesterol :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <div class="input-group">
                                        <input type="text" class="form-control remove-dash" id="add_cholesterol"
                                            name="cholesterol" placeholder="Masukkan kolesterol pasien" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mg/dL</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary"><em class="ni ni-save me-1"></em>
                                Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Show Modal --}}
    <div class="modal fade" id="showPatientModal">
        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pasien</h5>
                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" class="form-validate is-alter" id="editForm">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label">Nama Pasien
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Masukkan nama pasien" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="age" class="col-md-3 col-form-label">Umur Pasien
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="age" name="age"
                                        placeholder="Masukkan umur pasien" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row gy-4">
                            <label class="col-md-3 col-form-label">Jenis Kelamin :</label>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="editCustomRadio1" name="gender" value="Laki-laki"
                                            class="custom-control-input" autocomplete="off">
                                        <label class="custom-control-label" for="editCustomRadio1">Laki-laki</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="editCustomRadio2" name="gender" value="Perempuan"
                                            class="custom-control-input" autocomplete="off">
                                        <label class="custom-control-label" for="editCustomRadio2">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-3">
                            <label for="add_address" class="col-md-3 col-form-label">Alamat Pasien
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <textarea type="text" class="form-control" id="add_address" name="address" placeholder="Masukkan alamat pasien"
                                        autocomplete="off" required></textarea>
                                </div>
                            </div>
                        </div>
                        <h5 class="my-3">Hasil Pemeriksaan</h5>
                        <div class="form-group row">
                            <label for="systolic_blood_pressure" class="col-md-3 col-form-label">Tekanan darah :</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="systolic_blood_pressure"
                                        id="systolic_blood_pressure" placeholder="Sistolik">
                                    <div class="input-group-prepend input-group-append" autocomplete="off">
                                        <span class="input-group-text">/</span>
                                    </div>
                                    <input type="text" class="form-control" name="diastolic_blood_pressure"
                                        id="diastolic_blood_pressure" placeholder="Diastolik" autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">mmHg</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row gy-4">
                            <label class="col-md-3 col-form-label">Jenis gula darah :</label>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="blood_glucose_type1" name="blood_glucose_type"
                                            value="GDS" class="custom-control-input" autocomplete="off">
                                        <label class="custom-control-label" for="blood_glucose_type1">GDS</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="blood_glucose_type2" name="blood_glucose_type"
                                            value="GDP" class="custom-control-input" autocomplete="off">
                                        <label class="custom-control-label" for="blood_glucose_type2">GDP</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="add_blood_glucose" class="col-md-3 col-form-label">Gula
                                darah :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="add_blood_glucose"
                                            name="blood_glucose" placeholder="Masukkan gula darah pasien" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mg/dL</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_uric_acid" class="col-md-3 col-form-label">Asam urat
                                :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="add_uric_acid" name="uric_acid"
                                            placeholder="Masukkan asam urat pasien" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mg/dL</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_cholesterol" class="col-md-3 col-form-label">Kolesterol :</label>
                            <div class="col-md-9">
                                <div class="form-control-wrap">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="add_cholesterol"
                                            name="cholesterol" placeholder="Masukkan kolesterol pasien" autocomplete="off">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mg/dL</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deletePatientModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Pasien</h5>
                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" class="form-validate is-alter" id="deleteForm">
                        @csrf
                        @method('delete')
                        <div id="deleteMessage"></div>
                        <div class="form-group text-end mt-3">
                            <button type="submit" class="btn btn-lg btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="printPatientModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cetak Pasien</h5>
                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form class="form-validate is-alter" id="printForm">
                        @csrf
                        <div id="printMessage"></div>
                        <div class="form-group text-end mt-3">
                            <button type="submit" class="btn btn-lg btn-danger"><em
                                    class="icon ni ni-printer-fill me-1"></em>Cetak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
