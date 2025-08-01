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
                    <div class="col-12 col-md-4">
                        <label for="input2" class="form-label">Dosen Pembimbing</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" id="dosen" aria-label="input2"
                                value="{{ $namaDosen ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12 col-md-8">
                        <label for="input1" class="form-label">Topik Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" id="topik" aria-label="input1"
                                value="{{ $titleTopik ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="input3" class="form-label">Judul Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" id="judul" aria-label="input3"
                                value="{{ $judul ?? '' }}" disabled>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- DATA TABLE -->
        <div class="col-12 mt-3">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Tipe Sidang</th>
                        <th scope="col">Dosen Pembimbing</th>
                        <th scope="col">Dosen Pendamping</th>
                        <th scope="col">Penguji 1</th>
                        <th scope="col">Penguji 2</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Waktu</th>
                        <th scope="col">Status</th>
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
