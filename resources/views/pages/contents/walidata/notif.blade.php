@extends('pages.main.layout')
@section('content')

<div class="pagetitle">
    <h1>Notifikasi</h1>
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
            <h5 class="card-title">Log Data</h5>
            
          
            <!-- Table with stripped rows -->
            <table class="table datatable">
              <thead>
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Log Data</th>
                  <th scope="col">Nama Data</th>
                  <th scope="col">Aktor</th>
                  <th scope="col">Waktu</th>
                  
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; ?>
                  @foreach($notif as $dt)
                <tr>
                  <td>{{ $no++ }}</td>
                  <td>{{ $dt->description }}</td>
                  <td>{{ $dt->nama_data }}</td>
                  <td>{{ $dt->name }}</td>
                  <td>{{ Carbon\Carbon::parse($dt->created_at)->diffForHumans() }}</td>
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
<script type="text/javascript">
  function confirmRestore(form_id) {
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
                     $('#'+form_id).submit();
                 } else {
                     
                 }
             });
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
