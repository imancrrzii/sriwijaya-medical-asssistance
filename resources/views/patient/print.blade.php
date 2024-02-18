<!DOCTYPE html>
<html lang="en">

<head>
    <title>Document</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
    <title>Document</title>
</head>
<style>
    * {
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
    }

    body {
        max-width: 300px;
    }

    img {
        display: block;
        margin: auto;
    }

    .content {
        display: flex;
        justify-content: center;
    }

    table {
        max-width: 100%;
    }

    td {
        vertical-align: top;
    }

    .title {
        text-align: center;
        font-size: 14px;
        border-top: 1px dashed;
        border-bottom: 1px dashed;
        padding: 5px 0;
        margin: 5px 0;
        text-transform: uppercase;
    }

    .centered {
        text-align: center;
        font-size: 10px;
    }

    .result {
        margin-top: 10px;
    }

    .instagram-icon {
        font-size: 12px;
        margin-right: 5px;
    }

    /* Footer styling */
    .footer {
        margin-top: 10px;
        text-align: center;
    }
</style>

<body onload="printPromot()">
    <img src="{{ asset('assets/images/TBMS.png') }}" width="100px" alt="Logo">
    <p class="centered">PEMERIKSAAN KESEHATAN
        <br>TIM BANTUAN MEDIS SRIWIJAYA
        <br>FAKULTAS KEDOKTERAN
        <br>UNIVERSITAS SRIWIJAYA
    </p>
    <p class="centered"><em class="instagram-icon bi bi-instagram"></em>tmbsriwijaya</p>
    <!-- Date and Time -->
    <div style="display: flex; justify-content: space-between;">
        <span style="text-align: right; font-size: 10px; text-transform: uppercase">DATE: {{ now()->format('d M Y') }}</span>
        <span style="text-align: right; font-size: 10px;">TIME: {{ now()->format('h:i A') }}</span>
    </div>

    <div class="content">
        <table>
            <tbody>
                <tr>
                    <td colspan="3">
                        <h5 class="title">Identitas</h5>
                    </td>

                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $patient->name }}</td>
                </tr>
                <tr>
                    <td>Usia</td>
                    <td>:</td>
                    <td>{{ $patient->age }}</td>
                </tr>
                <tr>
                    <td>Jenis kelamin</td>
                    <td>:</td>
                    <td>{{ $patient->gender }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>
                        {{ $patient->address }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h5 class="title result">Hasil Pemeriksaan</h5>
                    </td>

                </tr>
                <tr>
                    <td>TD</td>
                    <td>:</td>
                    <td>
                        {{ $patient->systolic_blood_pressure ? $patient->systolic_blood_pressure . '/' . $patient->diastolic_blood_pressure . ' mmHg' : '-' }}
                        ({{ $patient->blood_pressure_level }})
                    </td>
                </tr>
                <tr>
                    <td>GD</td>
                    <td>:</td>
                    <td>{{ $patient->blood_glucose ? $patient->blood_glucose . ' mg/dL' : '-' }}
                        ({{ $patient->blood_glucose_level }})</td>
                </tr>
                <tr>
                    <td>Asam Urat</td>
                    <td>:</td>
                    <td>{{ $patient->uric_acid ? $patient->uric_acid . ' mg/dL' : '-' }}
                        ({{ $patient->uric_acid_level }})</td>
                </tr>
                <tr>
                    <td>Kolesterol</td>
                    <td>:</td>
                    <td>
                        {{ $patient->cholesterol ? $patient->cholesterol . ' mg/dL' : '-' }}
                        ({{ $patient->cholesterol_level }})
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <h5 class="title result">Nilai Normal</h5>
                    </td>
                </tr>
                <tr>
                    <td>TD</td>
                    <td>:</td>
                    <td>&le;120/80 mmHg</td>
                </tr>
                <tr>
                    <td>GD</td>
                    <td>:</td>
                    <td>
                        <ul style="list-style-type: none; padding: 0; margin: 0;">
                            <li>GDP: &lt;126 mg/dL</li>
                            <li>GDS: &lt;200 mg/dL</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Asam Urat</td>
                    <td>:</td>
                    <td>
                        <ul style="list-style-type: none; padding: 0; margin: 0;">
                            <li>Laki – laki: 2.5 – 7.2 mg/dL</li>
                            <li>Perempuan: 1.9 – 6 mg/dL</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Kolesterol</td>
                    <td>:</td>
                    <td>&lt;200 mg/dL</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        Terima kasih telah melakukan pemeriksaan kesehatan bersama kami.
        <br>Semoga sehat selalu
    </div>

    <script>
        function printPromot() {
            window.print();
        }
    </script>
</body>

</html>
