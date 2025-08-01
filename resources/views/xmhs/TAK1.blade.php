@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Form Pengajuan Topik Proposal</span>
        <hr />

        <div class="row">
            <div class="col-12">
                <p class="p-2 border border-secondary rounded-3 fst-italic">
                    <span class="fw-semibold fst-normal" style="color:darkblue">Penting!</span><br>Pastikan untuk memilih
                    topik terlebih dahulu dari
                    halaman "List Topik TA" dan melakukan pengajuan.
                </p>
            </div>
        </div>

        <div class="row">
            <form id="formProposal" action="{{ route('x2.TugasAkhir1-form1') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf

                <!-- HIDDEN INPUT -->
                <input type="hidden" name="id_topik" id="id_topik" value="{{ $idTopik }}">
                <input type="hidden" name="id_dosen" id="id_dosen" value="{{ $idDosen }}">

                <div class="row g-4">

                    <!-- FORM DATA -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Pilihan Topik</label>
                        <input type="text" class="form-control bg-light" value="{{ $titleTopik ?? '' }}" disabled>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Dosen Pembimbing 1</label>
                        <input type="text" class="form-control bg-light" value="{{ $namaDosen ?? '' }}" disabled>
                    </div>

                    <!-- FORM INPUT -->
                    <div class="col-12 mt-3">
                        <label for="rancanganJudul" class="form-label">Rancangan Judul</label>
                        <input type="text" class="form-control" name="rancangan_judul" id="rancangan_judul" required>
                    </div>

                    <div class="col-12 my-3">
                        <label for="draft" class="form-label">Draft Proposal (.pdf, .docx)</label>
                        <input type="file" class="form-control" name="draft_proposal" id="draft_proposal"
                            accept=".pdf,.doc,.docx" required>
                    </div>
                </div>
                <div class="col-12 col-md-2 my-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary flex-fill">Submit</button>
                        <button type="reset" class="btn btn-sm btn-danger text-light flex-fill">Reset</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- DATA TABLE -->
        <div class="col-12 mt-3">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th>Topik</th>
                        <th>Dosen Pembimbing</th>
                        <th>Periode</th>
                        <th>Rancangan Judul</th>
                        <th>Berkas Draft</th>
                        <th>Status</th>
                        <th>Catatan Validasi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // TABLE 1
            $('#table1').DataTable({
                dom: 'ftp',
                scrollX: true,
                lengthChange: false,
                searching:false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x2.TugasAkhir1-json1') }}',
                columns: [{
                        data: 'topik',
                        name: 'topik'
                    },
                    {
                        data: 'dosen',
                        name: 'dosen'
                    },
                    {
                        data: 'periode_topik',
                        name: 'periode_topik'
                    },
                    {
                        data: 'rancangan_judul',
                        name: 'rancangan_judul'
                    },
                    {
                        data: 'draft_proposal',
                        name: 'draft_proposal',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'catatan',
                        name: 'catatan',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [7, 'desc']
                ]
            });
        });

        // POST DATA
        $('#formProposal').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response.message || 'Proposal berhasil diajukan!');
                    $('#formProposal')[0].reset();
                    $('#table1').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let msg = "Terjadi kesalahan.";
                    if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors).map(e => e[0]).join('\n');
                    }
                    alert(msg);
                }
            });
        });
    </script>
@endpush
