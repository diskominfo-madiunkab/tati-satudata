<!-- Modal -->
<div class="modal fade" id="modal-upload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Berkas</h5>
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
            <form action="{{route('visual.data.upload')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_data" id="id_data" value="{{$data->id}}">
                <input type="hidden" name="tahun" id="tahun">
                <input type="hidden" name="visual_id" id="visual_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tahun" class="control-label">Berkas</label>
                        <input id="berkas" name="berkas" type="file" class="form-control">
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-title"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary" id="store">Upload</button>
                </div>
            </form>

        </div>
    </div>

</div>