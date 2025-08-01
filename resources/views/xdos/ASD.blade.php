@extends('layouts.dosLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Daftar jadwal menguji/membimbing sidang mahasiswa</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Jenis Sidang</th>
                        <th scope="col">Sebagai Dosen-</th>
                        <th scope="col">NIM</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Topik</th>
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
                processing: true,
                serverSide: true,
                ajax: '{{ route('x1.AgendaSidang-json1') }}',
                columns: [{
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
                        data: 'topik',
                        name: 'topik',
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

                ],
                order: [
                    [0, 'desc']
                ]
            });
        });
    </script>
@endpush
