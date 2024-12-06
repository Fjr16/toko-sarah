<div class="modal fade" id="modalDelete" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body pb-0">
                <h6 class="fw-bold text-center text-capitalized mb-1 text-warning" id="data_aksi"></h6>
                <h5 class="text-center">
                    Apakah anda yakin ?
                </h5>
            </div>
            <div class="modal-footer justify-content-center">
                <button class="btn btn-sm btn-danger" data-bs-dismiss="modal">Batal</button>
                <form action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-primary" value="" name="" type="submit" data-bs-dismiss="modal">Yakin</button>
                </form>
            </div>
        </div>
    </div>
</div>