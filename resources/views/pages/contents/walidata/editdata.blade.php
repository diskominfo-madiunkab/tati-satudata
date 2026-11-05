@extends('pages.main.layout')
@section('content')

@php
  $isProdusen = (Auth::user()->role_id == '3' || auth()->user()->hasRole('produsen'));
@endphp

<div class="pagetitle">
  <h1>Daftar Data</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
      <li class="breadcrumb-item">Daftar Data</li>
      <li class="breadcrumb-item active">Edit Data</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Edit Data</h5>

          @if($isProdusen)
            <div class="alert alert-info py-2 small">
              <i class="bi bi-info-circle me-1"></i> Sebagai Produsen Data, Anda hanya diperbolehkan mengubah <strong>Jadwal Rilis</strong> dan <strong>Jadwal Pemutakhiran</strong>.
            </div>
          @endif

          <!-- General Form Elements -->
          <form @if(Auth::user()->role_id == '1')
            action="{{ url('data_administrator/update', $data->id) }}"
            @elseif(Auth::user()->role_id == '2' || auth()->user()->hasRole('walidata') || auth()->user()->hasRole('walidatapendukung') || auth()->user()->hasRole('pembina'))
            action="{{ url('data_walidata/update', $data->id) }}"
            @elseif($isProdusen)
            action="{{ url('data_produsen/update', $data->id) }}"
            @endif
            method="POST">
            @csrf
            <div class="row mb-3">
              <label for="kode_ddd" class="col-sm-2 col-form-label">Kode DDD</label>
              <div class="col-sm-10">
                <input id="kode_ddd" name="kode_ddd" type="text" class="form-control" value="{{ old('kode_ddd', $data->kode_ddd) }}" placeholder="Contoh: 3519.01.001" {{ $isProdusen ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="row mb-3">
              <label for="kode_ddp" class="col-sm-2 col-form-label">Kode DDP</label>
              <div class="col-sm-10">
                <input id="kode_ddp" name="kode_ddp" type="text" class="form-control" value="{{ old('kode_ddp', $data->kode_ddp) }}" placeholder="Contoh: 06.01.001" {{ $isProdusen ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="row mb-3">
              <label for="inputText" class="col-sm-2 col-form-label">Nama Data</label>
              <div class="col-sm-10">
                <input id="nama_data" name="nama_data" type="text" class="form-control" value="{{$data->nama_data}}" {{ $isProdusen ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Jenis Data</label>
              <div class="col-sm-10">
                @if($isProdusen)
                  <input type="text" class="form-control" value="{{$data->jenis_data}}" readonly>
                  <input type="hidden" name="jenis_data" value="{{$data->jenis_data}}">
                @else
                  <select id="jenis_data" name="jenis_data" class="form-select">
                    <option selected value="{{$data->jenis_data}}">{{$data->jenis_data}}</option>
                    <option value="Indikator">Indikator</option>
                    <option value="Variabel">Variabel</option>
                  </select>
                @endif
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Produsen Data (PIC)</label>
              <div class="col-sm-10">
                @if($isProdusen)
                  <input type="text" class="form-control" value="{{$data->opd ? $data->opd->nama_opd : '-'}}" readonly>
                  <input type="hidden" name="opd_id" value="{{$data->opd_id}}">
                @else
                  <select id="opd_id" name="opd_id" class="form-select">
                    <option selected value="{{$data->opd_id}}">{{$data->opd ? $data->opd->nama_opd : '-'}}</option>
                    @foreach($opd as $dt)
                    <option value="{{ $dt->id }}">{{ $dt->nama_opd }}</option>
                    @endforeach
                  </select>
                @endif
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Sumber Referensi</label>
              <div class="col-sm-10">
                @if($isProdusen)
                  <input type="text" class="form-control" value="{{$data->sumber_referensi ?: $data->sumber_data}}" readonly>
                  <input type="hidden" name="sumber_data" value="{{$data->sumber_data}}">
                @else
                  <select id="sumber_data" name="sumber_data" class="form-select">
                    <option selected value="{{$data->sumber_data}}">{{$data->sumber_data}}</option>
                    @foreach( $sumber as $sd)
                    <option value="{{ $sd->sumber_data }}">{{ $sd->sumber_data }}</option>
                    @endforeach
                  </select>
                @endif
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Tahun</label>
              <div class="col-sm-10">
                @if($isProdusen)
                  <input type="text" class="form-control" value="{{$data->tahun}}" readonly>
                  <input type="hidden" name="tahun" value="{{$data->tahun}}">
                @else
                  <select id="tahun" style="width: 100%" name="tahun" class="form-select select2" aria-label="Default select example" required>
                    <option value="" disabled selected hidden>Pilih</option>
                    @foreach( $tahun as $th)
                    <option value="{{ $th->tahun }}" {{$th->tahun == $data->tahun ? 'selected' : ''}}>{{ $th->tahun }}</option>
                    @endforeach
                  </select>
                @endif
              </div>
            </div>
            <div class="row mb-3">
              <label for="jadwal_rilis" class="col-sm-2 col-form-label">Jadwal Rilis</label>
              <div class="col-sm-10">
                <input id="jadwal_rilis" name="jadwal_rilis" type="date" class="form-control" required value="{{$data->jadwal_rilis}}">
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Level Data</label>
              <div class="col-sm-10">
                <select id="level_data" name="level_data" class="form-select">
                  <option value="Kabupaten" {{ $data->level_data == 'Kabupaten' ? 'selected' : '' }}>Kabupaten</option>
                  <option value="Kecamatan" {{ $data->level_data == 'Kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                  <option value="Desa/Kelurahan" {{ $data->level_data == 'Desa/Kelurahan' ? 'selected' : '' }}>Desa / Kelurahan</option>
                  <option value="Puskesmas / Faskes" {{ $data->level_data == 'Puskesmas / Faskes' ? 'selected' : '' }}>Puskesmas / Faskes</option>
                  <option value="Individu" {{ $data->level_data == 'Individu' ? 'selected' : '' }}>Individu</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Periode Data</label>
              <div class="col-sm-10">
                <select id="periode_data" name="periode_data" class="form-select">
                  <option value="Tahunan" {{ $data->periode_data == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                  <option value="Semesteran" {{ $data->periode_data == 'Semesteran' ? 'selected' : '' }}>Semesteran</option>
                  <option value="Triwulanan" {{ $data->periode_data == 'Triwulanan' ? 'selected' : '' }}>Triwulanan</option>
                  <option value="Bulanan" {{ $data->periode_data == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                  <option value="Mingguan" {{ $data->periode_data == 'Mingguan' ? 'selected' : '' }}>Mingguan</option>
                  <option value="Harian" {{ $data->periode_data == 'Harian' ? 'selected' : '' }}>Harian</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Jadwal Pemutakhiran</label>
              <div class="col-sm-10">
                <select id="jadwal_pemutakhiran" name="jadwal_pemutakhiran" class="form-select select2" aria-label="Default select example" required>
                  <option selected value="{{$data->jadwal_pemutakhiran}}">{{$data->jadwal_pemutakhiran}}</option>
                  <option value="Harian">Harian</option>
                  <option value="Mingguan">Mingguan</option>
                  <option value="Bulanan">Bulanan</option>
                  <option value="Tahunan">Tahunan</option>
                  <option value="Triwulanan">Triwulanan</option>
                  <option value="Semesteran">Semesteran</option>
                  <option value="Empat Tahunan">Empat Tahunan</option>
                  <option value=">Dua Tahunan">>Dua Tahunan</option>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <label for="data_prioritas" class="col-sm-2 col-form-label">Data Prioritas</label>
              <div class="col-sm-10">
                @if($isProdusen)
                  <span class="badge {{ $data->data_prioritas == 1 ? 'bg-success' : 'bg-secondary' }}">
                    {{ $data->data_prioritas == 1 ? 'Ya (Prioritas)' : 'Tidak' }}
                  </span>
                  <input type="hidden" name="data_prioritas" value="{{$data->data_prioritas}}">
                @else
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="data_prioritas" id="data_prioritas1" value="1" {{old('data_prioritas', $data->data_prioritas) == 1 ? 'checked' : ''}}>
                    <label class="form-check-label" for="data_prioritas1">Ya</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="data_prioritas" id="data_prioritas0" value="0" {{old('data_prioritas', $data->data_prioritas) == 0 ? 'checked' : ''}}>
                    <label class="form-check-label" for="data_prioritas0">Tidak</label>
                  </div>
                @endif
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-sm-10 offset-sm-2">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
                <a href="{{url()->previous()}}" class="btn btn-outline-secondary ms-2"><i class="bi bi-arrow-left"></i> Kembali</a>
              </div>
            </div>
          </form><!-- End General Form Elements -->

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
