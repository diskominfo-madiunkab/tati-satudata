@foreach($publikasi as $pub)
<div class="modal fade" id="basicModal{{$pub->id}}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus data publikasi </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('publikasi.delete', $pub->id) }}">
                @method('DELETE')
                <div class="modal-body">
                    Apakah anda yakin menghapus data publikasi {{$pub->title}}?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- End Basic Modal-->
@endforeach