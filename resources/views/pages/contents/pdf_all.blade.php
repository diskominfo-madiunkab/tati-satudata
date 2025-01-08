<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <style>
    .page-break {
      page-break-after: always;
    }

    .kop {
      text-align: center;
      font-family: "Helvetica Neue", Helvetica, Arial;
    }

    .head-isi {
      align-items: center;
      text-align: center;
      font-family: "Helvetica Neue", Helvetica, Arial;
      padding-left: 130px;
    }

    .menyatakan {
      padding-left: 50px;
    }

    .ttd {
      padding-left: 300px;
    }

    .tbl {
      border: 1px solid black;
      border-collapse: collapse;
      Posisi: Relative,
        Border: Collapse,
        Widht: Max-width,
        Margin: auto (otomatis),
        Padding: 10px,
        Text-Align: center,
    }
  </style>

</head>

<body>
  <h1 style="text-align: center">Daftar Data Pemerintah Kabupaten Madiun</h1>
  <table class="tbl" style="margin-left:auto;margin-right:auto" cellpadding="10" border="1 solid" posisi="Relative">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Nama Data</th>
        <th scope="col">Produsen (PIC)</th>
        <th scope="col">Jenis</th>
        <th scope="col">Sumber</th>
        {{-- <th scope="col">Status</th> --}}
      </tr>
    </thead>
    <tbody>
      <?php $no = 1; ?>
      @foreach($data as $dt)
      <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $dt->nama_data }}</td>
        <td>{{ $dt->opd->nama_opd }}</td>
        <td>{{ $dt->jenis_data }}</td>
        <td>{{ $dt->sumber_data }}</td>
        {{-- <td>{{ $dt->status->status }}</td> --}}
      </tr>
      @endforeach
    </tbody>
  </table>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</body>

</html>