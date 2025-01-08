<!-- Modal -->
<div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
            <form action="{{route('visual.data.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="number" value="{{$data->id}}" name="id_data" id="id_data" hidden>
                <div class="modal-body">
                    {{-- <div class="form-group">
                        <label for="tahun" class="control-label">Tahun</label>
                        <select id="tahun" name="tahun" class="form-select select2 filter"
                            aria-label="Default select example" required>
                            <option value="" disabled selected hidden>Pilih Tahun
                            </option>
                            @foreach( $tahun as $th)
                            <option value="{{ $th->tahun }}">{{ $th->tahun }}</option>
                            @endforeach
                        </select>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-title"></div>
                    </div>

                    <div class="form-group">
                        <label for="nilai" class="control-label">Nilai</label>
                        <input type="number" id="nilai" name="nilai" pattern="[0-9]+([\.,][0-9]+)?" step="any"
                            class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-content"></div>
                    </div> --}}

                    <div class="form-group">
                        <label for="berkas" class="control-label">Berkas</label>
                        <input type="file" name="berkas" id="berkas" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary" id="store">Tambah</button>
                </div>
            </form>

        </div>
    </div>

</div>
@push('js')
<script>
    //button create post event
    $('body').on('click', '#btn-create-post', function () {

        //open modal
        $('#modal-create').modal('show');
    });

</script>
@endpush