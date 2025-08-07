@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Data Pengajuan Jadwal Seminar dan Sidang</span>
        <hr />

        <div class="row">

            <!-- FORM DATA -->
            <form id="" action="" method="GET" enctype="multipart/form-data">
                @csrf
                <div class="row g-2">

                    <div class="col-12 col-md-12">
                        <label for="input1" class="form-label">Topik Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-white text-primary fw-semibold" id="topik"
                                aria-label="input1" value="{{ $titleTopik ?? 'N/A' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="input3" class="form-label">Judul Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-white text-primary fw-semibold" id="judul"
                                aria-label="input3" value="{{ $judul ?? 'N/A' }}" disabled>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- DATA TABLE -->
        <div class="col-12 mt-3">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="150px">Tipe Sidang</th>
                        <th scope="col" width="150px">Dosen Pembimbing</th>
                        <th scope="col" width="150px">Dosen Pendamping</th>
                        <th scope="col" width="150px">Penguji 1</th>
                        <th scope="col" width="150px">Penguji 2</th>
                        <th scope="col" width="150px">Tanggal</th>
                        <th scope="col" width="150px">Waktu</th>
                        <th scope="col" width="150px">Status</th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- FORM DATA -->
        <div class="row">

            <span class="my-3 fw-bold fst-italic">Upcoming :</span>

            <div class="col-12 col-md-6">
                <label for="input1" class="form-label">Sidang / Seminar</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $tipeSidang ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input2" class="form-label">Tipe Pengajuan</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $tipePengajuan ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input3" class="form-label">Dosen Pembimbing</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $namaDosen1 ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input4" class="form-label">Dosen Penguji 1</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $namaPenguji1 ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input5" class="form-label">Dosen Pendamping</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $namaDosen2 ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input6" class="form-label">Dosen Penguji 2</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $namaPenguji2 ?? 'N/A' }}" disabled>
                </div>
            </div>

            <hr class="my-3 " />

            <div class="col-12 col-md-6">
                <label for="input7" class="form-label">Tanggal Sidang</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $tanggalPel ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input8" class="form-label">Waktu Pelaksanaan</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $waktuPel ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input9" class="form-label">Lokasi Sidang</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $lokasiSidang ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input10" class="form-label">Skema Sidang</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $skemaSidang ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input11" class="form-label">Link Zoom/Google Meet</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen"
                        aria-label="input2" value="{{ $linkSidang ?? 'N/A' }}" disabled>
                </div>
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
                scrollX: true,
                lengthChange: false,
                searching: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x2.JadwalSidang-json1') }}',
                columns: [{
                        data: 'tipe_sidang',
                        name: 'tipe_sidang'
                    },
                    {
                        data: 'dosen',
                        name: 'dosen'
                    },
                    {
                        data: 'dosen2',
                        name: 'dosen2'
                    },
                    {
                        data: 'penguji',
                        name: 'penguji'
                    },
                    {
                        data: 'penguji2',
                        name: 'penguji2',
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
                    [8, 'desc']
                ]
            });
        });
    </script>
@endpush
