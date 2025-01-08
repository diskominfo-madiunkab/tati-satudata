<!-- Modal -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
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
            <form action="{{route('visual.data.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tahun" class="control-label">Tahun</label>
                        <select id="tahun_edit" name="tahun" class="form-select select2 filter"
                            aria-label="Default select example" readonly>
                            @foreach( $tahun as $th)
                            <option value="{{ $th->tahun }}">{{
                                $th->tahun }}</option>
                            @endforeach
                        </select>
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-title"></div>
                    </div>

                    <div class="form-group">
                        <label for="nilai" class="control-label">Nilai</label>
                        <input type="number" id="nilai" name="nilai" pattern="[0-9]+([\.,][0-9]+)?" step="any"
                            class="form-control">
                        <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-content"></div>
                    </div>

                    {{-- <div class="form-group">
                        <label for="berkas" class="control-label">Berkas</label>
                        <input type="file" name="berkas" class="form-control">
                    </div> --}}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-primary" id="store">Perbarui</button>
                </div>
            </form>

        </div>
    </div>

</div>