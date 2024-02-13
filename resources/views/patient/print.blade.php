<!DOCTYPE html>
<html lang="en">

<head>
    <title>Document</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
</head>
<style>
    * {
        font-size: 12px;
        font-family: Arial, Helvetica, sans-serif;
        text-transform: uppercase;
    }

    body{
        max-width: 300px;
    }

    img{
        display: block;
        margin: auto;
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
    }
    .centered{
        text-align: center;
        font-size: 12px;
    }

    .result{
        margin-top: 10px;
    }
</style>

<body onload="printPromot()">
    <img src="{{ asset('assets/images/TBMS.png') }}" width="100px" alt="Logo">
    <p class="centered">LAYANAN KESEHATAN FAKULTAS KEDOKTERAN
        <br>JALAN AMPERA SUMATERA SELATAN
        <br>PALEMBANG SUMATERA SELATAN
    </p>
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
                <td>Gender</td>
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
                <td>Tkn Drh</td>
                <td>:</td>
                <td>{{ $patient->blood_pressure }}</td>
            </tr>
            <tr>
                <td>Gula Drh</td>
                <td>:</td>
                <td>{{ $patient->blood_glucose }}</td>
            </tr>
            <tr>
                <td>Asam Urat</td>
                <td>:</td>
                <td class="description">{{ $patient->uric_acid }}</td>
            </tr>
            <tr>
                <td>Kolesterol</td>
                <td>:</td>
                <td>
                    {{ $patient->cholesterol }}
                </td>
            </tr>
        </tbody>
    </table>


    <script>
        function printPromot() {
            window.print();
        }
    </script>



</body>

</html>
