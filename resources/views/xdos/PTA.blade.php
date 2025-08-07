@extends('layouts.dosLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Informasi Periode Tugas Akhir</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 my-2">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="150px">Nama Periode</th>
                        <th scope="col" width="150px">Tipe Periode</th>
                        <th scope="col" width="150px">Tanggal Buka</th>
                        <th scope="col" width="150px">Tanggal Tutup</th>
                        <th scope="col" width="150px">Status</th>
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
                ajax: '{{ route('x1.PeriodeTA-json1') }}',
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

            // POST DATA
            $("#form1").on("submit", function(event) {
                var input1 = $("#input1").val()
                    .trim();

                if (!input1) {
                    alert("Semua form harus diisi sebelum submit.");
                    event.preventDefault();
                }
            });
        });
    </script>
@endpush
