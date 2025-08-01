@extends('layouts.admLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Review Penilaian Seminar dan Sidang</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">NIM</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Tipe Sidang</th>
                        <th scope="col">Dosen Pembimbing</th>
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
                ajax: '{{ route('x0.KelolaPenilaian-json1') }}',

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
                        data: 'pembimbing',
                        name: 'pembimbing'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'review',
                        name: 'review'
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
