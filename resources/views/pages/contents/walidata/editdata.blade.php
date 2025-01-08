@extends('pages.main.layout')
@section('content')

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

          <!-- General Form Elements -->
          <form @if(Auth::user()->role_id == '1')
            action="{{ url('data_administrator/update', $data->id) }}"
            @elseif(Auth::user()->role_id == '2')
            action="{{ url('data_walidata/update', $data->id) }}"
            @elseif(Auth::user()->role_id == '3')
            action="{{ url('data_produsen/update', $data->id) }}"
            @endif
            method="POST">
            @csrf
            <div class="row mb-3">
              <label for="inputText" class="col-sm-2 col-form-label">Nama Data</label>
              <div class="col-sm-10">
                <input id="nama_data" name="nama_data" type="text" class="form-control" value="{{$data->nama_data}}">
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Jenis Data</label>
              <div class="col-sm-10">
                <select id="jenis_data" name="jenis_data" class="form-select">
                  <option selected value="{{$data->jenis_data}}">{{$data->jenis_data}}</option>
                  <option value="Indikator">Indikator</option>
                  <option value="Variabel">Variabel</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Produsen Data(PIC)</label>
              <div class="col-sm-10">
                <select id="opd_id" name="opd_id" class="form-select">
                  <option selected value="{{$data->opd_id}}">{{$data->opd->nama_opd}}</option>
                  @foreach($opd as $dt)
                  <option value="{{ $dt->id }}">{{ $dt->nama_opd }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Sumber Referensi</label>
              <div class="col-sm-10">
                <select id="sumber_data" name="sumber_data" class="form-select">
                  <option selected value="{{$data->sumber_data}}">{{$data->sumber_data}}</option>
                  @foreach( $sumber as $sd)
                  <option value="{{ $sd->sumber_data }}">{{ $sd->sumber_data }}</option>
                  @endforeach
                  {{-- <option value="RPJMD">RPJMD</option>
                  <option value="SPM">SPM</option>
                  <option value="SDGs">SDGs</option>
                  <option value="Data Provinsi">Data Provinsi</option> --}}
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Tahun</label>
              <div class="col-sm-10">

                <select id="tahun" style="width: 100%" name="tahun" class="form-select select2"
                  aria-label="Default select example" required>
                  <option value="" disabled selected hidden>Pilih</option>
                  @foreach( $tahun as $th)
                  <option value="{{ $th->tahun }}" {{$th->tahun == $data->tahun ? 'selected' : ''}}>{{ $th->tahun }}
                  </option>
                  @endforeach

                  {{-- <option value="RPJMD">RPJMD</option>
                  <option value="SPM">SPM</option>
                  <option value="SDGs">SDGs</option>
                  <option value="Data Provinsi">Data Provinsi</option> --}}
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="jadwal_rilis" class="col-sm-2 col-form-label">Jadwal Rilis</label>
              <div class="col-sm-10">
                <input id="jadwal_rilis" name="jadwal_rilis" type="date" class="form-control" required
                  value="{{$data->jadwal_rilis}}">
              </div>
            </div>
            <div class="row mb-3">
              <label class="col-sm-2 col-form-label">Jadwal Pemutakhiran</label>
              <div class="col-sm-10">

                <select id="jadwal_pemutakhiran" name="jadwal_pemutakhiran" class="form-select select2"
                  aria-label="Default select example" required>
                  <option selected value="{{$data->jadwal_pemutakhiran}}">{{$data->jadwal_pemutakhiran}}</option>
                  <option value="Harian">Harian</option>
                  <option value="Mingguan">Mingguan</option>
                  <option value="Bulanan">Bulanan</option>
                  <option value="Tahunan">Tahunan</option>
                  <option value="Triwulanan">Triwulanan</option>
                  <option value="Semesteran">Semesteran</option>
                  <option value="Empat Tahunan">Empat Tahunan</option>
                  <option value=">Dua Tahunan">>Dua Tahunan</option>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <label for="data_prioritas" class="col-sm-2 col-form-label">Data Prioritas</label>
              <div class="col-sm-10">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="data_prioritas" id="data_prioritas1" value="1"
                    {{old('data_prioritas', $data->data_prioritas) == 1 ? 'checked' : ''}}>
                  <label class="form-check-label" for="data_prioritas">
                    Ya
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="data_prioritas" id="data_prioritas0" value="0"
                    {{old('data_prioritas', $data->data_prioritas) == 0 ? 'checked' : ''}}>
                  <label class="form-check-label" for="data_prioritas0">
                    Tidak
                  </label>
                </div>
              </div>
            </div>


            <div class="row mb-3">
              <label class="col-sm-2 col-form-label"></label>
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">SIMPAN</button>
              </div>
            </div>

            <a href="{{url()->previous('d_' . auth()->user()->role->name)}}" class="btn btn-outline-secondary"><i
                class="bi bi-arrow-left"></i> Kembali</a>


          </form><!-- End General Form Elements -->

        </div>
      </div>

    </div>


  </div>
</section>
@endsection