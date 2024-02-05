<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <!-- Page Title  -->
    <title>Sriwijaya Medical Assistance | {{ $title }}</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=3.0.3') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css?ver=3.0.3') }} ">
</head>

<body class="nk-body bg-white npc-default pg-auth">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
                        <div class="card">
                            <div class="card-inner card-inner-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h4 class="nk-block-title">Reset password</h4>
                                        <div class="nk-block-des">
                                            <p>Jika anda lupa password, kami akan mengirimkan link untuk mengatur ulang
                                                password ke email anda.</p>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('forgot.send') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email">Email</label>
                                        </div>
                                        <div class="form-control-wrap">
                                            <input type="email"
                                                class="form-control form-control-lg @error('email')
                                                is-invalid
                                            @enderror"
                                                id="email" name="email" value="{{ old('email') }}"
                                                placeholder="Masukkan email anda">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary btn-block" id="btnSubmit">
                                            <span>Kirim Link Ke Email</span>
                                        </button>
                                    </div>
                                </form>
                                <div class="form-note-s2 text-center pt-4">
                                    <a href="{{ route('login') }}" class="link link-primary link-sm"><strong>Kembali ke halaman login</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/bundle.js?ver=3.0.3') }}"></script>
    <script src="{{ asset('assets/js/scripts.js?ver=3.0.3') }}"></script>
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
    <script src="{{ asset('assets/js/example-toastr.js?ver=3.0.3') }}"></script>
    @error('email')
        <script>
            let message = "{{ $message }}"
            NioApp.Toast(`<h5>Error</h5><p>${message}</p>`, 'error', {
                position: 'top-right',
            });
        </script>
    @enderror
    @if (session()->has('success'))
        <script>
            let message = @json(session('success'));
            NioApp.Toast(`<h5>Berhasil</h5><p>${message}</p>`, 'success', {
                position: 'top-right',
            });
        </script>
    @endif

</html>
