@extends('layouts.admLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Review Penilaian Seminar dan Sidang</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="150px">NIM</th>
                        <th scope="col" width="150px">Nama Mahasiswa</th>
                        <th scope="col" width="150px">Tipe Sidang</th>
                        <th scope="col" width="150px">Dosen Pembimbing</th>
                        <th scope="col" width="150px">Review</th>
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
                    [5, 'desc']
                ]
            });
        });
    </script>
@endpush
