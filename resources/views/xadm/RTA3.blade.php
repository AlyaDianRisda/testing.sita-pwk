@extends('layouts.admLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Rekapitulasi Monitoring Progres Tugas Akhir</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Nama Periode</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Tipe Periode</th>
                        <th scope="col">Download</th>
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
                ajax: '{{ route('x0.RekapitulasiTA3-json1') }}',
                columns: [{
                        data: 'periode',
                        name: 'periode'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'tipe',
                        name: 'tipe'
                    },
                    {
                        data: 'review',
                        name: 'review',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [4, 'desc']
                ]
            });
        });
    </script>
@endpush
