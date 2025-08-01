@extends('layouts.admLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Monitoring Umum Pendaftaran Sidang </span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">

            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="100px">NIM</th>
                        <th scope="col" width="140px">Nama Mahasiswa</th>
                        <th scope="col" width="100px">Judul</th>
                        <th scope="col" width="100px">Pembimbing</th>
                        <th scope="col" width="100px">Pendamping</th>
                        <th scope="col" width="100px">Penguji 1</th>
                        <th scope="col" width="100px">Penguji 2</th>
                        <th scope="col" width="100px">Tipe Sidang</th>
                        <th scope="col" width="150px">Tipe Pengajuan</th>
                        <th scope="col" width="100px">Status</th>
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
                ajax: '{{ route('x0.RekapitulasiTA2-json1') }}',
                columns: [{
                        data: 'nim',
                        name: 'nim'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'dos_utama',
                        name: 'dos_utama'
                    },
                    {
                        data: 'dos_kedua',
                        name: 'dos_kedua'
                    },
                    {
                        data: 'penguji1',
                        name: 'penguji1'
                    },
                    {
                        data: 'penguji2',
                        name: 'penguji2'
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
                        data: 'status_pengajuan',
                        name: 'status_pengajuan'
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
    </script>
@endpush
