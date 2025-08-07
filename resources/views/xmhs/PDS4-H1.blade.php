@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Form Pengajuan Perubahan Nilai</span>
        <hr />

        <div class="row">

            <form id="formUbah" action="{{ route('x2.PendaftaranSidang4-form1') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="row g-2">

                    <!-- HIDDEN INPUT -->
                    <input type="hidden" name="id_topik" id="id_topik" value="{{ $idTopik }}">
                    <input type="hidden" name="id_dosen" id="id_dosen" value="{{ $idDosen }}">
                    <input type="hidden" name="inp_judul" id="inp_judul" value="{{ $judul }}">

                    <!-- FORM DATA -->
                    <div class="col-12 col-md-6">
                        <label for="input1" class="form-label">Dosen Pembimbing</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                                aria-label="input2" value="{{ $namaDosen ?? 'N/A' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Dosen Pendamping</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                                aria-label="input2" value="{{ $namaDosen2 ?? 'N/A' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="input3" class="form-label">Topik Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-white text-primary fw-semibold" id="topik"
                                aria-label="input1" value="{{ $titleTopik ?? 'N/A' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="input4" class="form-label">Judul Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-white text-primary fw-semibold" id="judul"
                                aria-label="input3" value="{{ $judul ?? 'N/A' }}" disabled>
                        </div>
                    </div>

                    <hr class ="my-3" />

                    <!-- FORM INPUT -->
                    <div class="col-12">
                        <label for="formFile1" class="form-label">Laporan Revisi</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="form1" id="form1"
                                accept=".pdf,.doc,.docx" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="formFile2" class="form-label">Logbook Lengkap</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="form2" id="form2"
                                accept=".pdf,.doc,.docx" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="formFile3" class="form-label">Berita Acara</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="form3" id="form3"
                                accept=".pdf,.doc,.docx" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="formFile4" class="form-label">Lembar Pengesahan</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="form4" id="form4"
                                accept=".pdf,.doc,.docx" required>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-2 mt-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary flex-fill">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-sm btn-danger text-light flex-fill">
                            Reset
                        </button>
                    </div>
            </form>
        </div>

        <!-- DATA TABLE -->
        <div class="col-12 mt-3">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="150px">Laporan Revisi</th>
                        <th scope="col" width="150px">Logbook</th>
                        <th scope="col" width="150px">Berita Acara</th>
                        <th scope="col" width="150px">Lembar Pengesahan</th>
                        <th scope="col" width="150px">Status</th>
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
                searching: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x2.PendaftaranSidang3-json1') }}',
                columns: [{
                        data: 'form1',
                        name: 'form1',
                    },
                    {
                        data: 'form2',
                        name: 'form2',
                    },
                    {
                        data: 'form3',
                        name: 'form3',
                    },
                    {
                        data: 'form4',
                        name: 'form4',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [5, 'desc']
                ]
            });

            // POST DATA
            $('#formUbah').on('submit', function(e) {
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
                        $('#formUbah')[0].reset();
                        $('#table1').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        let msg = "Terjadi kesalahan.";
                        if (xhr.responseJSON?.message) {
                            msg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON?.errors) {
                            msg = Object.values(xhr.responseJSON.errors).map(e => e[0]).join(
                                '\n');
                        }
                        alert(msg);
                    }
                });
            });
        });
    </script>
@endpush
