@extends('layouts.admLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Pengelolaan Jadwal Seminar dan Sidang</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">NIM</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Tipe Sidang</th>
                        <th scope="col">Tipe Pengajuan</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Waktu</th>
                        <th scope="col">Lokasi</th>
                        <th scope="col">Skema</th>
                        <th scope="col">Status</th>
                        <th scope="col">Review</th>
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
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('x0.PlottingSidang-json1') }}',

                columns: [{
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'tipe_sidang',
                        name: 'tipe_sidang'
                    },
                    {
                        data: 'tipe_pengajuan',
                        name: 'tipe_pengajuan'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi'
                    },
                    {
                        data: 'skema',
                        name: 'skema'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
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
