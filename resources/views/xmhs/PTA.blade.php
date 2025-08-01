@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Informasi Periode Tugas Akhir</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Nama Periode</th>
                        <th scope="col">Tipe Periode</th>
                        <th scope="col">Tanggal Buka</th>
                        <th scope="col">Tanggal Tutup</th>
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
                ajax: '{{ route('x2.PeriodeTA-json1') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                const dateObj = new Date(data);
                                return dateObj.toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'long',
                                    year: 'numeric'
                                });
                            }
                            return data;
                        }
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                const dateObj = new Date(data);
                                return dateObj.toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'long',
                                    year: 'numeric'
                                });
                            }
                            return data;
                        }
                    },
                    {
                        data: 'is_open',
                        name: 'is_open',
                        searchable: false
                    }
                ],
                order: [
                    [4, 'desc']
                ] // atau ganti ke kolom sesuai kebutuhan
            });
        });
    </script>
@endpush
