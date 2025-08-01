@extends('layouts.admLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Monitoring Progres Pengajuan Proposal</span>
        <hr />

        <!-- DATA TABLE 1 -->
        <span class="my-3 fw-bold fst-italic"> Proses Pengajuan Mahasiswa :</span>
        <div class="col-12 mt-1">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">NIM</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Draft Laporan</th>
                        <th scope="col">Topik Pilihan</th>
                        <th scope="col">Dosen Pembimbing</th>
                        <th scope="col">Validasi</th>
                        <th scope="col">Dosen Pendamping</th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- DATA TABLE 2 -->
        <span class="my-3 fw-bold fst-italic"> Data Topik dan Validasi Dosen :</span>
        <div class="col-12 mt-1">
            <table class="table table-bordered table-hover" id="table2">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Nama Dosen</th>
                        <th scope="col">Topik</th>
                        <th scope="col">Bidang</th>
                        <th scope="col">Kuota</th>
                        <th scope="col">Diajukan</th>
                        <th scope="col">Diterima</th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- MODAL ASSIGN KUOTA -->
        <div class="modal fade" id="modalAssignDosen" tabindex="-1" aria-labelledby="modalAssignDosenLabel"
            aria-hidden="true">
            <div class="modal-dialog">

                <form id="formAssignDosen" method="POST">
                    @csrf
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAssignDosenLabel">Assign Pembimbing Kedua</h5>
                            <button type="button" class="btn-close modal-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" id="submissionId" name="submission_id">
                            <div class="mb-3">
                                <label for="dosenKedua" class="form-label">Pilih Dosen</label>
                                <select class="form-select" name="dosen_kedua_id" id="dosenKedua" required>
                                    <option value="">-- assign --</option>
                                    @foreach ($listDosen as $dosen)
                                        <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">Simpan</button>
                            <button type="button" class="btn btn-secondary modal-close btn-sm flex-fill"
                                data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // TABLE 1
            $('#table1').DataTable({
                dom: 'ftp',
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('x0.RekapitulasiTA1-json1') }}',
                columns: [{
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'draft_path',
                        name: 'draft_path'
                    },
                    {
                        data: 'topik_pilihan',
                        name: 'topik_pilihan'
                    },
                    {
                        data: 'dos_utama',
                        name: 'dos_utama'
                    },
                    {
                        data: 'status_pengajuan',
                        name: 'status_pengajuan'
                    },
                    {
                        data: 'dos_kedua',
                        name: 'dos_kedua',
                        render: function(data, type, row) {
                            if (!data || data === '') {
                                return `<a href="#" class="assign-dosen" data-id="${row.id}" data-nama="${row.nama}">Assign</a>`;
                            }
                            return data;
                        }
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

            // TABLE 2
            $('#table2').DataTable({
                dom: 'ftp',
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('x0.RekapitulasiTA1-json2') }}',
                columns: [{
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'topik_ta',
                        name: 'topik_ta'
                    },
                    {
                        data: 'kelompok_keahlian',
                        name: 'kelompok_keahlian'
                    },
                    {
                        data: 'kuota_topik',
                        name: 'kuota_topik'
                    },
                    {
                        data: 'jml_diajukan',
                        name: 'jml_diajukan'
                    },
                    {
                        data: 'jml_validasi',
                        name: 'jml_validasi'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [6, 'desc']
                ]

            });

            // OPEN MODAL
            $(document).on('click', '.assign-dosen', function() {
                const submissionId = $(this).data('id');
                $('#submissionId').val(submissionId);
                $('#modalAssignDosen').modal('show');
            });

            // CLOSE MODAL
            $(document).on('click', '.modal-close', function() {
                const submissionId = $(this).data('id');
                $('#table1').DataTable().ajax.reload(null, false);
                $('#modalAssignDosen').modal('hide');
            });

            // POST DATA
            $('#formAssignDosen').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('x0.RekapitulasiTA1-form1') }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response.message);
                        $('#modalAssignDosen').modal('hide');
                        $('#table1').DataTable().ajax.reload(null, false);
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
                        $('#modalAssignDosen').modal('hide');
                        $('#table1').DataTable().ajax.reload(null, false);
                    }
                });
            });
        });
    </script>
@endpush
