@extends('layouts.app')

@push('js')
    <script src="{{ asset('assets/js/libs/datatable-btns.js?ver=3.0.3') }}"></script>
    <script src="{{ asset('assets/js/example-toastr.js?ver=3.0.3') }}"></script>
    <script>
        function printPatient(patientId) {
            $.ajax({
                url: "{{ route('patient.print', '') }}/" + patientId,
                method: 'GET',
                success: function(data) {
                    var printContent = document.createElement('div');
                    printContent.innerHTML = data;

                    var headerFooter = printContent.querySelectorAll('.nk-header, .nk-footer, .nk-sidebar');
                    headerFooter.forEach(function(element) {
                        element.style.display = 'none';
                    });

                    var printWindow = window.open('', '_blank');
                    printWindow.document.write(printContent.innerHTML);
                    printWindow.document.close();
                    printWindow.print();
                },
                error: function(error) {
                    console.error('Error loading print view:', error);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
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
                    form.find('#add_blood_pressure').val(data.blood_pressure);
                    form.find('#add_blood_glucose').val(data.blood_glucose);
                    form.find('#add_uric_acid').val(data.uric_acid);
                    form.find('#add_cholesterol').val(data.cholesterol);

                });

                form.attr('action', '{{ route('patient.update', ':id') }}'.replace(':id', id));
            });

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
        });
    </script>
    @if (session()->has('success'))
        <script>
            let message = @json(session('success'));
            NioApp.Toast(`<h5>Berhasil</h5><p>${message}</p>`, 'success', {
                position: 'top-right',
            });
        </script>
    @endif
@endpush

@section('content')
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Data pasien
                    </h4>
                </div>
            </div>
            <div class="card card-bordered card-preview">
                <div class="card-inner">
                    @can('admin-table')
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                            data-bs-target="#addPatientModal" data-modal-title="Tambah Pasien">
                            <span class="ni ni-plus"></span>
                            <span class="ms-1">Tambah pasien</span>
                        </button>
                    @endcan
                    <table class="datatable-init-export table-responsive table-bordered nowrap table"
                        data-export-title="Export">
                        <thead>
                            <tr class="table-light">
                                <th class="text-center">No</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center no-export">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $index => $patient)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $patient->name }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-xs rounded-pill btn-dim" data-bs-toggle="modal"
                                            data-bs-target="#editPatientModal" data-modal-title="Edit Konseptor"
                                            data-id="{{ $patient->id }}">
                                            <em class="icon ni ni-edit-fill"></em>
                                        </button>
                                        <button class="btn btn-danger btn-xs rounded-pill btn-dim" data-bs-toggle="modal"
                                            data-bs-target="#deletePatientModal" data-id="{{ $patient->id }}">
                                            <em class="icon ni ni-trash-fill"></em>
                                        </button>
                                        @can('admin-monitoring-all')
                                        <a href="#" onclick="printPatient('{{ $patient->id }}')"
                                            class="btn btn-success btn-xs rounded-pill">
                                            <em class="ni ni-printer"></em>
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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
                            <label for="add_name" class="col-md-4 col-form-label">Nama Pasien :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="add_name" name="name"
                                        placeholder="Masukkan nama pasien" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_age" class="col-md-4 col-form-label">Umur Pasien :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="number" class="form-control" id="add_age" name="age"
                                        placeholder="Masukkan umur pasien" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row gy-4">
                            <label class="col-md-4 col-form-label">Jenis Kelamin :</label>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="customRadio1" name="gender" value="laki-laki"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio1">Laki-laki</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="customRadio2" name="gender" value="perempuan"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio2">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <label for="add_address" class="col-md-4 col-form-label">Alamat Pasien :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <textarea type="text" class="form-control" id="add_address" name="address" placeholder="Masukkan alamat pasien"
                                        required></textarea>
                                </div>
                            </div>
                        </div>
                        <h5 class="my-3">Hasil Pemeriksaan</h5>
                        <div class="form-group row">
                            <label for="add_blood_pressure" class="col-md-4 col-form-label">Tekanan darah :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="add_blood_pressure"
                                        name="blood_pressure" placeholder="Masukkan tekanan darah pasien" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_blood_glucose" class="col-md-4 col-form-label">Gula darah :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="add_blood_glucose"
                                        name="blood_glucose" placeholder="Masukkan gula darah pasien" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_uric_acid" class="col-md-4 col-form-label">Asam urat :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="add_uric_acid" name="uric_acid"
                                        placeholder="Masukkan asam urat pasien" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_cholesterol" class="col-md-4 col-form-label">Kolesterol :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="add_cholesterol" name="cholesterol"
                                        placeholder="Masukkan kolesterol pasien" required>
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
        <div class="modal-dialog" role="document">
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
                            <label for="name" class="col-md-4 col-form-label">Nama Pasien :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Masukkan nama pasien" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="age" class="col-md-4 col-form-label">Umur Pasien :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="number" class="form-control" id="age" name="age"
                                        placeholder="Masukkan umur pasien" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row gy-4">
                            <label class="col-md-4 col-form-label">Jenis Kelamin :</label>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="editCustomRadio1" name="gender" value="laki-laki"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="editCustomRadio1">Laki-laki</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="preview-block">
                                    <div class="custom-control custom-control-sm custom-radio">
                                        <input type="radio" id="editCustomRadio2" name="gender" value="perempuan"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="editCustomRadio2">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mt-3">
                            <label for="add_address" class="col-md-4 col-form-label">Alamat Pasien :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <textarea type="text" class="form-control" id="add_address" name="address" placeholder="Masukkan alamat pasien"
                                        required></textarea>
                                </div>
                            </div>
                        </div>
                        <h5 class="my-3">Hasil Pemeriksaan</h5>
                        <div class="form-group row">
                            <label for="add_blood_pressure" class="col-md-4 col-form-label">Tekanan darah :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="add_blood_pressure"
                                        name="blood_pressure" placeholder="Masukkan tekanan darah pasien" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_blood_glucose" class="col-md-4 col-form-label">Gula darah :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="add_blood_glucose"
                                        name="blood_glucose" placeholder="Masukkan gula darah pasien" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_uric_acid" class="col-md-4 col-form-label">Asam urat :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="add_uric_acid" name="uric_acid"
                                        placeholder="Masukkan asam urat pasien" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="add_cholesterol" class="col-md-4 col-form-label">Kolesterol :</label>
                            <div class="col-md-8">
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" id="add_cholesterol" name="cholesterol"
                                        placeholder="Masukkan kolesterol pasien" required>
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
@endsection
