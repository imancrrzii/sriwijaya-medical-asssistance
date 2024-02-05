@extends('layouts.app')

@push('js')
    <script src="{{ asset('assets/js/example-toastr.js?ver=3.0.3') }}"></script>
    <script>
        $(document).ready(function() {
            $("#btnSubmit").on("click", function() {
                var $btn = $(this);
                var $form = $btn.closest("form");
                if ($form[0].checkValidity()) {
                    $btn.addClass("disabled");

                    var spinner = $("<span/>", {
                        "class": "spinner-border spinner-border-sm",
                        "role": "status",
                        "aria-hidden": "true"
                    });

                    $btn.prepend(spinner);
                }
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
    @if (session()->has('error'))
        <script>
            let message = @json(session('error'));
            NioApp.Toast(`<h5>Error</h5><p>${message}</p>`, 'error', {
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
                    <h4 class="nk-block-title">Ubah Password</h4>
                </div>

            </div>
            <div class="card card-bordered card-preview">
                <div class="card-inner">
                    <div class="card-head">
                        <p>Silahkan masukkan password baru anda</p>
                    </div>
                    <form action="{{ route('update.password') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="current_password">Password Saat
                                    Ini</label>
                            </div>
                            <div class="form-control-wrap">
                                <a href="#" class="form-icon form-icon-right passcode-switch lg"
                                    data-target="current_password">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password"
                                    class="form-control form-control-lg @error('current_password')
                                                                error
                                                            @enderror"
                                    id="current_password" name="current_password" placeholder="Masukkan password saat ini">
                                @error('current_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password">Password baru</label>
                            </div>
                            <div class="form-control-wrap">
                                <a href="#" class="form-icon form-icon-right passcode-switch lg"
                                    data-target="password">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password"
                                    class="form-control form-control-lg @error('password')
                                                                error
                                                            @enderror"
                                    id="password" name="password" placeholder="Masukkan password baru">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-label-group">
                                <label class="form-label" for="password_confirmation">Konfirmasi
                                    Password</label>
                            </div>
                            <div class="form-control-wrap">
                                <a href="#" class="form-icon form-icon-right passcode-switch lg"
                                    data-target="password_confirmation">
                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                </a>
                                <input type="password"
                                    class="form-control form-control-lg @error('password_confirmation')
                                                                error
                                                            @enderror"
                                    id="password_confirmation" name="password_confirmation"
                                    placeholder="Masukkan konfirmasi password">
                                @error('password_confirmation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary btn-block" id="btnSubmit">
                                <span>Ubah Password</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
