@extends('layouts.dosLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Daftar Jadwal Menguji/Membimbing Sidang</span>
        <hr />

        <!-- MODAL -->
        <div class="modal fade" id="modalDetailSidang" tabindex="-1" aria-labelledby="modalDetailSidangLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Review Sidang</h5>
                        <button type="button" class="btn-close modal-close" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <div class="col-12">
                            <label for="input1" class="form-label">Sebagai Dosen:</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white text-primary fw-semibold" id="roleDosen"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="input1" class="form-label">Topik Tugas Akhir</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white text-primary fw-semibold" id="modalTopik"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="input1" class="form-label">Judul Tugas Akhir</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white text-primary fw-semibold" id="modalJudul"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="input1" class="form-label">Tipe Sidang</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white text-primary fw-semibold" id="modalTipe"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="input1" class="form-label">Tipe Pengajuan</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white text-primary fw-semibold"
                                    id="modalPengajuan" disabled>
                            </div>
                        </div>

                        <hr class="my-2 " />

                        <div class="col-12">
                            <label for="input1" class="form-label">Tanggal Sidang</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white text-primary fw-semibold"
                                    id="modalTanggal" disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="input1" class="form-label">Waktu Mulai</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white text-primary fw-semibold" id="modalWaktu"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="input1" class="form-label">Lokasi / Ruang</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white text-primary fw-semibold"
                                    id="modalLokasi" disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="input1" class="form-label">Skema Sidang</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white text-primary fw-semibold" id="modalSkema"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="input1" class="form-label">Link Zoom / Google Meet</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bg-white text-primary fw-semibold" id="modalLink"
                                    disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="100px">Review</th>
                        <th scope="col" width="150px">Jenis Sidang</th>
                        <th scope="col" width="150px">Sebagai Dosen-</th>
                        <th scope="col" width="150px">NIM</th>
                        <th scope="col" width="150px">Nama Mahasiswa</th>
                        <th scope="col" width="150px">Tanggal</th>
                        <th scope="col" width="150px">Waktu</th>
                        <th scope="col" width="150px">Lokasi</th>
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
                processing: true,
                serverSide: true,
                ajax: '{{ route('x1.AgendaSidang-json1') }}',
                columns: [{
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tipe_sidang',
                        name: 'tipe_sidang'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'waktu',
                        name: 'waktu',
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi',
                    },

                ],
                order: [
                    [1, 'desc']
                ]
            });
        });

        // OPEN MODAL
        $('#table1').on('click', '.btn-detail', function() {
            const data = $(this).data();
            $('#roleDosen').val(data.role || 'N/A');
            $('#modalTopik').val(data.topik || 'N/A');
            $('#modalJudul').val(data.judul || 'N/A');
            $('#modalTipe').val(data.tipe || 'N/A');
            $('#modalPengajuan').val(data.pengajuan || 'N/A');
            $('#modalTanggal').val(data.tanggal || 'N/A');
            $('#modalWaktu').val(data.waktu || 'N/A');
            $('#modalLokasi').val(data.lokasi || 'N/A');
            $('#modalSkema').val(data.skema || 'N/A');
            $('#modalLink').val(data.link || 'N/A');

            $('#modalDetailSidang').modal('show');
        });

        // CLOSE MODAL 
        $(document).on('click', '.modal-close', function() {
            $('#modalDetailSidang').modal('hide');
        });
    </script>
@endpush
