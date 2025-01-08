@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Daftar Data</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item">Daftar Data</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Daftar Data</h5>
            
            {{-- <a 
            @if(Auth::user()->role_id == '1')
            href="/data_administrator/create"
            @elseif(Auth::user()->role_id == '2')
            href="/data_walidata/create"
            @elseif(Auth::user()->role_id == '3')
            href="/data_produsen/create"
            @endif
            class="btn btn-md btn-primary mb-3 float-right">Tambah
                Data</a> --}}

                {{-- <a href="" class="btn btn-md btn-warning mb-3 float-right" data-bs-toggle="modal" data-bs-target="#basicModal">Import Excel</a> --}}
                <!-- Table with stripped rows -->
                  <div class="modal fade" id="basicModal" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Import Excel</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/data_produsen/import" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="file" name="file" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                                    <button class="btn btn-primary" type="submit" id="button-addon2">Import</button>
                                </div>
                            </form>                    
                        </div>
                      </div>
                    </div>
                  </div>
                  @if(Auth::user()->role_id == '3')
                  <!-- Table with stripped rows -->
                  
                  @if($draft == "0")
                  <a href="{{ url('/data_produsen/export-pdf') }}" class="btn btn-md btn-danger mb-3 float-right" target="_blank">Unduh Berita Acara</a>

                  @elseif($draft >= "0")
                  
                  <a href="" class="btn btn-md btn-danger mb-3 float-right" data-bs-toggle="modal" data-bs-target="#beritaacara">Unduh Berita Acara</a>
                  <div class="modal fade" id="beritaacara" tabindex="-1">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Unduh Berita Acara</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          Anda belum bisa mengunduh berita acara dikarenakan masih ada DATA yang berstatus DRAFT
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
         
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif 
                  @endif
            <!-- Table with stripped rows -->
            <table class="table datatable">
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Data</th>
                  <th scope="col">Produsen (PIC)</th>
                  <th scope="col">Jenis</th>
                  <th scope="col">Sumber</th>
                  <th scope="col">Dibuat</th>
                  <th scope="col">Status</th>
                  <th scope="col">Opsi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; ?>
                  @foreach($data as $dt)
                <tr>
                  <td>{{ $no++ }}</td>
                  <td>{{ $dt->nama_data }}</td>
                  <td>{{ $dt->nama_opd }}</td>
                  <td>{{ $dt->jenis_data }}</td>
                  <td>{{ $dt->sumber_data }}</td>
                  <td>{{ $dt->name }}</td>
                  <td>
                    @if($dt->status_id == 3)
                    <span class="badge bg-secondary"><i class="bi bi-collection me-1"></i>{{ $dt->status }}</span>
                    @elseif($dt->status_id == 1)
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>{{ $dt->status }}</span>
                    @elseif($dt->status_id == 2)
                    <span class="badge bg-danger"><i class="bi bi-exclamation-octagon me-1"></i>{{ $dt->status }}</span>
                    @endif
                  </td>
                  <td>
                    @if(Auth::user()->role_id == '1')
                    {{-- <div class="btnConfirm" style="margin-bottom: 0;"> --}}
                      {{-- <a href="/data_administrator/edit/{{ $dt->id }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i>Edit</a>
                      <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ url('/data_administrator/destroy/'.$dt->id) }}">
                                
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-square"></i>HAPUS</button>
                      </form>
                    </div>
                    @elseif(Auth::user()->role_id == '2')
                    <div class="btnConfirm" style="margin-bottom: 0;">
                      <a href="/data_walidata/edit/{{ $dt->id }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i>Edit</a>
                      <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ url('/data_walidata/destroy/'.$dt->id) }}">
                                
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-square"></i>HAPUS</button>
                    </form> --}}
                    <a href="/data_administrator/edit/{{ $dt->id }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></a>
                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ url('/data_administrator/destroy/'.$dt->id) }}">
                              
                      
                      <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-square"></i></button>
                    </form>
                  {{-- </div> --}}
                  @elseif(Auth::user()->role_id == '2')
                  <div class="btnConfirm" style="margin-bottom: 0;">
                    {{-- <a href="/data_walidata/edit/{{ $dt->id }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></a> --}}
                    {{-- <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ url('/data_walidata/destroy/'.$dt->id) }}">
                              
                      
                      <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-square"></i></button>
                  </form>
                  <form onsubmit="return confirm('Apakah anda yakin merestore data : {{ $dt->nama_data }} ?');" action="{{ url('/data_walidata/restore/'.$dt->id) }}">
                             
                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-arrow-repeat"></i></button>
                  </form> --}}
                  <table>
                    <tr>
                      <td>
                        <a href="{{ route('detail_walidata',['id'=>$dt->id])  }}" class="btn btn-sm btn-warning" style="color: white" data-bs-placement="bottom" title="Detail Data"><i class="bi bi-info-circle"></i></a>
                      </td>
                      <td>
                        <form id="restore-data-{{ $dt->id }}" action="{{ url('/data_walidata/restore/'.$dt->id) }}" >
                          <button type="button" class="btn btn-sm btn-success" onclick="confirmRestore('restore-data-{{ $dt->id }}')" data-bs-placement="bottom" title="Restore Data"><i class="bi bi-arrow-repeat"></i></button>
                        </form>
                      </td>
                    </tr>
                  </table>
                  {{-- <td> --}}
                  {{-- </td> --}}
                  
                    </div>
                    @elseif(Auth::user()->role_id == '3')
                    {{-- <div class="btnConfirm" style="margin-bottom: 0;">
                      <a href="/data_produsen/edit/{{ $dt->id }}" class="btn btn-sm btn-primary">Edit</a>
                      <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ url('/data_produsen/destroy/'.$dt->id) }}">
                                
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                      </form>
                    <a href="/data_produsen/setuju/{{ $dt->id }}" class="btn btn-sm btn-success">Setujui</a>
                    <a href="/data_produsen/tolak/{{ $dt->id }}" class="btn btn-sm btn-warning">Tolak</a>
                    </div> --}}
                    {{-- <div class="dropdown">
                      <button class="dropbtn">Opsi</button>
                      <div class="dropdown-content"> --}}
                        <div class="btnConfirm" style="margin-bottom: 0;">
                        <form  action="{{ url('/data_walidata/edit/'.($dt->id)) }}">
                          
                          <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-pencil-fill"></i></button>
                        </form>
                        <form onsubmit="return confirm('Apakah anda Menghapus data : {{ $dt->nama_data }} ?');" action="{{ url('/data_produsen/destroy/'. ($dt->id) ) }}">
                          
                          <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        @if($dt->user_id != Auth::user()->id)
                        <form onsubmit="return confirm('Apakah anda Menyetujui data : {{ $dt->nama_data }} ?');" action="{{ url('/data_produsen/setuju/'. ($dt->id)) }}">
                             
                          <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i></button>
                        </form>
                        <form onsubmit="return confirm('Apakah anda Menolak data : {{ $dt->nama_data }} ?');" action="{{ url('/data_produsen/tolak/'. ($dt->id)) }}">
                          
                          <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i></button>
                        </form>
                        @endif
                      </div>
                      {{-- </div>               --}}
                    {{-- </div>               --}}
                    
                        
                               
                    @endif
                    
                  
                    </td>
                    
                </tr>
                  @endforeach
              </tbody>
            </table>
            <!-- End Table with stripped rows -->

          </div>
        </div>

      </div>
    </div>
  </section>
  
  <script>
   /* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

function filterFunction() {
  var input, filter, ul, li, a, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  div = document.getElementById("myDropdown");
  a = div.getElementsByTagName("a");
  for (i = 0; i < a.length; i++) {
    txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}
    </script>
    <script type="text/javascript">
      function confirmRestore(item_id) {
       swal({
                  title: 'Apakah Anda Yakin Mengembalikan Data Menjadi DRAFT?',
                   text: "Anda akan mengembalikan data menjadi status Draft!",
                   type: 'warning',
                   showCancelButton: true,
                   confirmButtonColor: '#3085d6',
                   cancelButtonColor: '#d33',
                   buttons: true,
                   confirmButtonText: 'Yes, delete it!'
             })
                 .then((willDelete) => {
                     if (willDelete) {
                         $('#'+item_id).submit();
                     } else {
                        //  swal("Cancelled Successfully");
                     }
                 });
       };
    
    </script>

@endsection