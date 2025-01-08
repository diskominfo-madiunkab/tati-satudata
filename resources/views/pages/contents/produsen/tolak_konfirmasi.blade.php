@extends('pages.main.layout')
<style>
  #myScrollTable tbody{
  clear:both;
  border:0px solid 3FF6600;
  /* height:300px; */
  overflow:auto;
  float:center;
  width:400px;
  /* background:#E2E6E7; */
  }
  </style>
@section('content')

<div class="pagetitle">
  @include('sweetalert::alert')
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
                  {{-- <a href="{{ url('/data_produsen/export-pdf') }}" class="btn btn-md btn-danger mb-3 float-right" target="_blank">Unduh Berita Acara</a> --}}
                  <form id="berita-acara" action="{{ url('/data_produsen/export-pdf') }}" >
                      
                    {{-- <button type="button" class="btn btn-sm btn-success" onclick="confirmBeritacara('berita-acara')"><i class="bi bi-download"></i> Unduh Berita Acara</button> --}}
                  </form>
                  @elseif($draft >= "0")
                  
                  {{-- <a href="" class="btn btn-md btn-danger mb-3 float-right" data-bs-toggle="modal" data-bs-target="#beritaacara">Unduh Berita Acara</a> --}}
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
            <div class="dataTable-container">
            <table id="myScrollTable" class="table datatable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Nama Data</th>
                  <th scope="col">Produsen (PIC)</th>
                  <th scope="col">Jenis</th>
                  <th scope="col">Sumber</th>
                  <th scope="col">Dibuat</th>
                  <th scope="col">Status</th>
                  <th scope="col">Alasan</th>
                  <th scope="col">Opsi</th>
                  {{-- <th scope="col">Opsi</th> --}}
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
                  {{-- <a id="alasan" class="btn btn-primary" data-toggle="modal" data-target="#alasandetail" data-alasan="{{ $dt->alasan }}"><i class="bi bi-download"></i></a>
              {{ $dt->alasan }} --}}
              {{-- <a href="#" value="/data_administrator/edit/{{ $dt->id }}" class="btn btn-xs btn-info modalMd" title="Show Data" data-toggle="modal" data-target="#modalMd"><i class="bi bi-download"></i></a> --}}
              <form id="detail-alasan">

                <button type="button" class="btn btn-sm btn-primary btn-detail" data-alasan="{{ $dt->alasan }}"><i class="bi bi-eye-fill"></i></button>
              </form>
              
              {{-- confirmHTML --}}
            </td>
            <td>
              <a href="{{ route('detail_produsen',['id'=>$dt->id])  }}" class="btn btn-sm btn-warning" style="color: white" data-bs-placement="bottom" title="Detail Data"><i class="bi bi-info-circle"></i></a>
            </td>
                    
                </tr>
                  @endforeach
              </tbody>
            </table>
          </div>
          

          </div>
        </div>

      </div>
    </div>
  </section>
  
  

@endsection


@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
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

    <script>
      $(document).ready(function() {
        var table = $('#tabel-data').DataTable( {
        scrollY: "300px",
        scrollX: true,
        scrollCollapse: true

        } );
      } );
    </script>
    <script type="text/javascript">
      function confirmBeritacara(item_id) {
       swal({
                  title: 'Apakah Anda Yakin Mengunduh Berita Acara?',
                   text: "Anda Akan Mengunduh Berita Acara!",
                   type: 'warning',
                   showCancelButton: true,
                   confirmButtonColor: '#3085d6',
                   cancelButtonColor: '#d33',
                   buttons: true,
                   confirmButtonText: 'Yes, delete it!'
             })
                
       };
    
    </script>
    <script type="text/javascript">
       $('.btn-detail').click(function(){
         let alasan=$(this).data('alasan')
         swal({
                  title: 'Alasan',
                   text: alasan,
                   type: 'warning',
                   showCancelButton: true,
                   confirmButtonColor: '#3085d6',
                   cancelButtonColor: '#d33',
                  //  buttons: true,
                   confirmButtonText: 'Yes, delete it!'
             })
       })
    </script>
    
    
@endpush  