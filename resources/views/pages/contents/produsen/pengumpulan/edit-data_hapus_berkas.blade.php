<!-- Modal -->
<div class="modal fade" id="modal-hapus-berkas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div style="background-color: red" class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="color: white">Hapus Berkas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="color: white"
                    aria-label="Close"></button>
            </div>
            {{-- <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Berkas Data</div>
                        <form class="form-control dropzone" id="berkas">
                            @csrf
                        </form>
                    </div>
                </div>
            </div> --}}
            <form action="{{route('visual.berkas.delete')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_hapus_berkas" id="id_hapus_berkas">
                <input type="hidden" name="tahun_hps_berkas" id="tahun_hps_berkas">
                <input type="hidden" name="id_visual_berkas" id="id_visual_berkas">
                <input type="hidden" name="id_data" id="id_data" value="{{$data->id}}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tahun" class="control-label">Apakah anda yakin menghapus berkas visualisasi ini?

                        </label>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tidak</span>
                    </button>
                    <button type="submit" class="btn btn-primary" id="store">Ya</button>
                </div>
            </form>

        </div>
    </div>

</div>

{{-- @push('js')
<script>
    //button create post event
    var id = '{{ $vs->id }}';;
    $('body').on('click', '#btn-hapus'+id, function () {
        //open modal
        $('#modal-hapus'+id).modal('show');
    });

</script>
@endpush --}}