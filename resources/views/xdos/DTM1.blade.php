@extends('layouts.dosLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Data Pengajuan Topik Tugas Akhir</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">NIM</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Pilihan Topik</th>
                        <th scope="col">Rancangan Judul</th>
                        <th scope="col">Berkas</th>
                        <th scope="col" width="100px">Catatan</th>
                        <th scope="col">Validasi</th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- MODAL CATATAN -->
        <div class="modal fade" id="modalCatatan" tabindex="-1" aria-labelledby="catatanLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="formCatatan" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Buat Catatan Validasi</h5>
                            <button type="button" class="btn-close modal-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <p id="namaMhs"></p>
                            <textarea name="catatan_validasi" class="form-control" rows="4" required></textarea>
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
            var table = $('#table1').DataTable({
                dom: 'ftp',
                scrollX: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x1.DataMahasiswa1-json1') }}',
                columns: [{
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'topik',
                        name: 'topik'
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
                        data: 'catatan_validasi',
                        name: 'catatan_validasi',
                        render: function(data, type, row) {
                            if (data) {
                                return `<span>${data}</span>`;
                            } else if (row.status_pengajuan === 'Ditolak') {
                                return `Tidak ada catatan.`;
                            } else if (row.status_pengajuan === 'Diterima') {
                                return `Tidak ada catatan.`;
                            } else {
                                return `<div class="d-flex"><button class="btn btn-sm btn-outline-dark btn-note flex-fill" style="min-width: 80px; padding: 4px 6px; font-size: 0.85rem;" data-id="${row.id}" data-nama="${row.nama}">Buat Catatan</button>`;
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (row.status_pengajuan === 'Diterima') {
                                return `<div class="d-flex"><button class="btn btn-outline-primary btn-sm flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;" disabled>Diterima</button>`;
                            } else if (row.status_pengajuan === 'Ditolak') {
                                return `<div class="d-flex"><button class="btn btn-outline-danger btn-sm flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;" disabled>Ditolak</button>`;
                            } else {
                                return `
                                    <div class="d-flex gap-1"><button class="btn btn-primary btn-sm btn-acc flex-fill" style="width: 70px; padding: 4px 6px; font-size: 0.85rem;" data-id="${data}">Terima</button>
                                    <button class="btn btn-danger btn-sm btn-rej text-light flex-fill" style="width: 70px; padding: 4px 6px; font-size: 0.85rem;" data-id="${data}">Tolak</button>
                                `;
                            }
                        }

                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [7, 'asc'] // Mhs yang mendaftar lebih dahulu ada di urutan atas
                ]
            });

            // ACCEPT
            $(document).on('click', '.btn-acc', function() {
                var id = $(this).data('id');
                $.post(`/dosen/dtm1/accept/${id}`, {
                    _token: '{{ csrf_token() }}'
                }, function(res) {
                    alert(res.message);
                    table.ajax.reload();
                }).fail(function(xhr) {
                    alert('Gagal menerima proposal.');
                });
            });

            // REJECT 
            $(document).on('click', '.btn-rej', function() {
                var id = $(this).data('id');
                $.post(`/dosen/dtm1/reject/${id}`, {
                    _token: '{{ csrf_token() }}'
                }, function(res) {
                    alert(res.message);
                    table.ajax.reload();
                }).fail(function(xhr) {
                    alert('Gagal menolak proposal.');
                });
            });

            // BUAT CATATAN
            $(document).on('click', '.btn-note', function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                $('#formCatatan').attr('action', `/dosen/dtm1/catatan/${id}`);
                $('#namaMhs').text(`Catatan untuk: ${nama}`);
                $('#modalCatatan').modal('show');
            });

            // CLOSE MODAL
            $(document).on('click', '.modal-close', function() {
                const submissionId = $(this).data('id');
                $('#table1').DataTable().ajax.reload(null, false);
                $('#modalCatatan').modal('hide');
            });

            // SUBMIT CATATAN
            $('#formCatatan').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const action = form.attr('action');
                const data = form.serialize();

                $.post(action, data, function(res) {
                    $('#modalCatatan').modal('hide');
                    $('#formCatatan')[0].reset();
                    alert(res.message);
                    table.ajax.reload();
                }).fail(function(xhr) {
                    alert('Gagal menyimpan catatan.');
                });
            });
        });
    </script>
@endpush
