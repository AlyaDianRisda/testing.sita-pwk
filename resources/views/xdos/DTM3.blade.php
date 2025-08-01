@extends('layouts.dosLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Upload Penilaian Sidang Mahasiswa</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Tipe Sidang</th>
                        <th scope="col">NIM</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Status Sidang</th>
                        <th scope="col">Hasil</th>
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
            var table = $('#table1').DataTable({
                dom: 'ftp',
                scrollX: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x1.DataMahasiswa3-json1') }}',
                columns: [{
                        data: 'tipe_sidang',
                        name: 'tipe_sidang',
                    },
                    {
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
                        data: 'status_sidang',
                        name: 'status_sidang',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
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
        });
    </script>
@endpush
