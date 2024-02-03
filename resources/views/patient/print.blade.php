<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <title>Detail Pasien</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=3.0.3') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css?ver=3.0.3') }}">
    <style>
        .borderless-input {
            border: none;
            border-radius: 0;
        }

        .form-control {
            border: 0.5px dashed #dbdfea;
        }
            .header-separator {
                border-bottom: 0.5px dashed #1f2b3a;
            }
    </style>
</head>

<body class="bg-white " onload="printPromot()">
    <div class="nk-block">
        <div class="invoice invoice-print">
            <div class="invoice-wrap">
                <div class="invoice-brand d-flex justify-content-center align-items-center header-separator ">
                    <div class="m-4">
                        <img src="{{ asset('assets/images/TBMS.png') }}" srcset="./images/logo-dark2x.png 2x"
                            alt="">
                    </div>
                    <div class="m-4 text-center">
                        <h4 class="title text-dark">TIM BANTUAN MEDIS SRIWIJAYA</h4>
                        <h4 class="title text-dark">FAKULTAS KEDOKTERAN</h4>
                        <h4 class="title text-dark">UNIVERSITAS SRIWIJAYA</h4>
                    </div>
                </div>
                <hr class="line">
                <div>
                    <div class="modal-body">
                        <h5 class="mb-3 text-dark overline-title"><em class="icon ni ni-notes-alt fs-18 me-1"></em>Identitas</h5>
                        <div class="form-group d-flex justify-content-center align-items-center">
                            <table class="table table-borderless">
                                <tr class="text-dark align-middle">
                                    <td>Nama</td>
                                    <td>
                                        <input type="text" class="form-control w-100 text-dark"
                                            value="{{ $patient->name }}">
                                    </td>
                                </tr>
                                <tr class="text-dark align-middle">
                                    <td>Usia</td>
                                    <td>
                                        <input type="text" class="form-control w-100 text-dark"
                                            value="{{ $patient->age }} tahun">
                                    </td>
                                </tr>
                                <tr class="text-dark align-middle">
                                    <td>Jenis Kelamin</td>
                                    <td>
                                        <input type="text" class="form-control w-100 text-dark"
                                            value="{{ $patient->gender }}">
                                    </td>
                                </tr>
                                <tr class="text-dark align-middle">
                                    <td>Alamat</td>
                                    <td>
                                        <textarea type="text" class="form-control w-100 text-dark">{{ $patient->address }}</textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <h5 class="my-4 text-dark overline-title"><em class="icon ni ni-reports-alt fs-18 me-1"></em>Hasil Pemeriksaan</h5>
                        <div class="invoice-bills">
                            <div class="">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Tekanan darah</th>
                                            <th>Gula darah</th>
                                            <th>Asam urat</th>
                                            <th class="text-center">Kolesterol</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td>{{ $patient->blood_pressure }}</td>
                                            <td>{{ $patient->blood_glucose }}</td>
                                            <td>{{ $patient->uric_acid }}</td>
                                            <td class="text-center">{{ $patient->cholesterol }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .invoice-bills -->
                    </div>
                </div>

            </div><!-- .invoice-wrap -->
        </div><!-- .invoice -->
    </div><!-- .nk-block -->
    <script>
        function printPromot() {
            window.print();
        }
    </script>
</body>

</html>
{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            max-width: 80mm;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            font-size: 10px;
            border: 1px solid #dbdfea;
            padding: 2mm;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-style: italic;
            color: #666;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-left: 10mm;
            padding-right: 10mm;
        }

        header img {
            max-width: 60px;
            max-height: 60px;
        }

        .sub-header {
            text-align: center;
            flex: 1;
        }

        .hospital-name {
            font-size: 14px;
            font-weight: bold;
        }

        .separator {
            border-bottom: 0.5px dashed #1f2b3a;
            margin-bottom: 10px;
        }

        .patient-info {
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .patient-info label,
        .patient-info div {
            flex-basis: calc(50% - 5px);
            margin-bottom: 5px;
        }

        .patient-info label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #1f2b3a;
        }

        th,
        td {
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body onload="printPromot()">
    <header>
        <img src="{{ asset('assets/images/TBMS.png') }}" style="max-width: 30px" alt="Hospital Logo">
        <div class="sub-header">
            <div class="hospital-name">TIM BANTUAN MEDIS SRIWIJAYA</div>
        </div>
    </header>

    <div class="separator"></div>

    <div class="patient-info">
        <label>Nama</label>
        <div>: {{ $patient->name }}</div>

        <label>Umur</label>
        <div>: {{ $patient->age }}</div>

        <label>Jenis Kelamin</label>
        <div>: {{ $patient->gender}}</div>

        <label>Alamat</label>
        <div>: {{ $patient->address }}</div>
    </div>

    <div class="separator"></div>

    <h3>Hasil Pemeriksaan</h3>

    <table>
        <tr>
            <th>Tekanan Darah</th>
            <th>Gula Darah</th>
            <th>Kolesterol</th>
            <th>Asam Urat</th>
        </tr>
        <tr>
            <td>{{ $patient->blood_pressure }}</td>
            <td>{{ $patient->blood_glucose }}</td>
            <td>{{ $patient->cholesterol }}</td>
            <td>{{ $patient->uric_acid }}</td>
        </tr>
    </table>

    <div class="footer">
        Terima kasih telah menggunakan layanan kami.
    </div>

    <script>
        function printPromot() {
            window.print();
        }
    </script>
</body>

</html> --}}

