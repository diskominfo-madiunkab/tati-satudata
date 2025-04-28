<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Berita Acara {{ $opd->nama_opd }}</title>
    <style>
        .page-break {
            page-break-after: always;
        }

        .kop {
            text-align: center;
            font-family: "Arial, sans-serif";

        }

        .head-isi {
            align-items: center;
            text-align: center;
            font-family: "Arial, sans-serif";
            padding-left: 130px;
        }

        .menyatakan {
            font-size: 16px;
            padding-left: 50px;
        }

        .ttd {
            padding-left: 200px;
        }

        .ttd2 {
            width: 200px;
        }

        .tbl {
            border: 1px solid black;
            border-collapse: collapse;
            position: relative,
                Border: collapse,
                width: max-width,
                Margin: auto (otomatis),
                Padding: 10px,
                Text-Align: center,
        }

        .ratakanankiri {
            padding-left: 50px;
            padding-right: 50px;
            text-align: justify;
            text-indent: 0.5in;
        }

        .menyatakan td {
            vertical-align: top;
        }

        .menyatakan td:nth-child(2) {
            width: 10px;
            /* Atur lebar sesuai kebutuhan */
        }
    </style>

</head>

<body>
    <table>
        <tr>
            <td><img src="<?php echo $pict; ?>" width="100px" height="auto" alt=""></td>
            <td class="kop">
                <font size="5"><b>PEMERINTAH KABUPATEN MADIUN</b></font>
                <font size="6"><b>SEKRETARIAT DAERAH</b></font><br>
                <font size="2">Jl. Alun–alun Utara No. 1-3 Telp. ( 0351 ) 448000 - 44870007</font><br>
                {{-- <font size="2">Telepon ( 0351 ) 448000 - 44870007</font><br> --}}
                {{-- <font size="2">Website http://www.madiunkab.go.id</font><br> --}}
                <font size="3">CARUBAN – 63153</font>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td class="head-isi">
                <font size="3"><b>BERITA ACARA</b></font> <br>
                <font size="3"><b>HASIL KESEPAKATAN</b></font><br>
                <font size="3"><b>PENYUSUNAN DAFTAR DATA DAN DATA PRIORITAS</b></font><br>
                <font size="3"><b>PERANGKAT DAERAH TAHUN {{ $request_tahun ?? $tahun }}</b></font><br>
                {{-- {{ $today->year }} --}}
            </td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td class="ratakanankiri">
                <p style="padding-left: 0px; padding-right: 0px;">Pada hari ini, {{ $today->dayName }} tanggal
                    {{ ucwords(\App\Util\Rupiah::terbilang($today->day)) }} bulan {{ $today->monthName }} Tahun
                    {{ ucwords(\App\Util\Rupiah::terbilang($request_tahun ?? $tahun)) }} bertempat di Graha Eka Kapti
                    Kabupaten
                    Madiun telah diselenggarakan
                    Rapat Penetapan Daftar Data Tahun {{ $request_tahun ?? $tahun }} oleh Forum Satu Data Indonesia
                    tingkat
                    Kabupaten Madiun.
                </p>
                <article style="text-indent: 0.0in; padding-top: 5px; padding-bottom: 2px;"><b>MENYEPAKATI</b></article>
            </td>
        </tr>
    </table>
    <table class="menyatakan">
        <tr>
            <td>
                <font size="3">KESATU</font>
            </td>
            <td>
                <font width="100px"> :</font>
            </td>
            <td style="">
                <font width="100px"> Daftar Data {{ $opd->nama_opd }} Tahun {{ $request_tahun ?? $tahun }} sebagaimana
                    tercantum
                    dalam
                    lampiran
                    Berita Acara ini.</font>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>
                <font size="3">KEDUA</font>
            </td>
            <td>
                <font width="100px"> :</font>
            </td>
            <td style="">
                <font width="100px"> Daftar Data {{ $opd->nama_opd }} Tahun {{ $request_tahun ?? $tahun }} memuat Data
                    Statistik
                    Sektoral.
                </font>
            </td>
        </tr>
        <tr>
            <td>
                <font size="3">KETIGA</font>
            </td>
            <td>
                <font width="100px"> :</font>
            </td>
            <td style="">
                <font width="100px"> {{ $opd->nama_opd }} wajib menyediakan data sesuai Daftar Data yang disepakati
                    bersama Forum
                    Satu Data Indonesia tingkat Kabupaten Madiun.</font>
            </td>
        </tr>
        <tr>
            <td>
                <font size="3">KEEMPAT</font>
            </td>
            <td>
                <font width="100px"> :</font>
            </td>
            <td style=";">
                <font width="100px"> Dalam rangka mencukupi Daftar Data sebagaimana dimaksud dalam Diktum KESATU,
                    {{ $opd->nama_opd }} wajib mengajukan rekomendasi kegiatan statistik dan memenuhi Prinsip SDI
                    (Standar Data,
                    Metadata, Interoperabilitas dan
                    Kode Referensi).</font>
            </td>
        </tr>
        <tr>
            <td>
                <font size="3">KELIMA</font>
            </td>
            <td>
                <font width="100px"> :</font>
            </td>
            <td style="">
                <font width="100px"> Manajemen Data dilaksanakan melalui Portal Satu Data Kabupaten Madiun di laman<a
                        href="https://data.madiunkab.go.id"> data.madiunkab.go.id</a></font>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td style="text-indent: 0.5in; width: 100%">
                Demikian berita acara ini dibuat dan disahkan untuk digunakan sebagaimana mestinya
            </td>
        </tr>
    </table>
    <br>
    <br>
    {{-- <table style="width: 100%">
    <thead>
      <tr>
        <th align="center" style="width: 50%;">
          <font size="3">
            {{ $opd->jabatan_penjabat ?? 'Kepala' }} {{$opd->nama_opd}}
          </font>
        </th>
        <th align="center" style="width: 50%;">
          <font size="3">a.n. BUPATI MADIUN</font>
          <center>
            <font size="3">Sekretaris Daerah,
            </font>
          </center>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><br><br><br><br><br><br></td>
        <td><br><br><br><br><br><br></td>
      </tr>
      <tr>
        <td align="center">
          @if (!empty($opd->nama_penjabat) && !empty($opd->nip_penjabat) && !empty($opd->pangkat_penjabat))
          <u>{{$opd->nama_penjabat}}</u>
          <br>
          {{$opd->pangkat_penjabat}}
          <br>
          NIP. {{$opd->nip_penjabat}}
          @else
          .........................
          @endif
        </td>
        <td align="center">
          <font size="3">
            @if (!empty($adminOPD->nama_penjabat) && !empty($adminOPD->nip_penjabat) && !empty($adminOPD->pangkat_penjabat))
            <u>{{$adminOPD->nama_penjabat}}</u>
            <br>
            {{$adminOPD->pangkat_penjabat}}
            <br>
            NIP. {{$adminOPD->nip_penjabat}}
            @else
            .........................
            @endif
          </font> <br>
        </td>
      </tr>
    </tbody>
  </table> --}}
    <table style="width: 100%">
        <thead>
            <tr>
                <th align="center" style="width: 50%;">
                    <font size="3">a.n. BUPATI MADIUN</font>
                    <center>
                        <font size="3">Sekretaris Daerah,
                        </font>
                    </center>
                </th>
                <th align="center" style="width: 50%;">
                    <font size="3">
                        {{ $opd->jabatan_penjabat ?? 'Kepala' }} {{ $opd->nama_opd }}
                    </font>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><br><br><br><br><br><br></td>
                <td><br><br><br><br><br><br></td>
            </tr>
            <tr>
                <td align="center">
                    <font size="3">
                        @if (!empty($adminOPD->nama_penjabat) && !empty($adminOPD->nip_penjabat) && !empty($adminOPD->pangkat_penjabat))
                            <u>{{ $adminOPD->nama_penjabat }}</u>
                            <br>
                            {{ $adminOPD->pangkat_penjabat }}
                            <br>
                            NIP. {{ $adminOPD->nip_penjabat }}
                        @else
                            .........................
                        @endif
                    </font> <br>
                </td>
                <td align="center">
                    @if (!empty($opd->nama_penjabat) && !empty($opd->nip_penjabat) && !empty($opd->pangkat_penjabat))
                        <u>{{ $opd->nama_penjabat }}</u>
                        <br>
                        {{ $opd->pangkat_penjabat }}
                        <br>
                        NIP. {{ $opd->nip_penjabat }}
                    @else
                        .........................
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <div class="page-break"></div>
    <h1 style="text-align: center">LAMPIRAN</h1>
    <table class="tbl" style="margin-left:auto;margin-right:auto" cellpadding="10" border="1 solid"
        posisi="Relative">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Data</th>
                <th scope="col">Produsen (PIC)</th>
                <th scope="col">Tahun</th>
                <th scope="col">Jenis</th>
                <th scope="col">Sumber Referensi</th>
                <th scope="col">Jadwal Rilis</th>
                <th scope="col">Jadwal Pemutakhiran</th>
                {{-- <th scope="col">Status</th> --}}
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @php
                use Carbon\Carbon;
            @endphp
            @foreach ($data as $dt)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $dt->nama_data }}</td>
                    <td>{{ $dt->opd->nama_opd }}</td>
                    <td>{{ $dt->tahun }}</td>
                    <td>{{ $dt->jenis_data }}</td>
                    <td>{{ $dt->sumber_data }}</td>
                    <td>{{ $dt->jadwal_rilis ? Carbon::parse($dt->jadwal_rilis)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $dt->jadwal_pemutakhiran }}</td>
                    {{-- <td>{{ $dt->status->status }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</body>

</html>
