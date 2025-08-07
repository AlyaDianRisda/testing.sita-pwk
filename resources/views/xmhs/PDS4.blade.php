@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Data Hasil Keputusan dan Nilai Sidang</span>
        <hr />

        <p class="p-2 border border-secondary rounded-3 fst-italic">
            <span class="fw-semibold fst-normal" style="color:darkblue">Penting!</span>
            <br> Nilai huruf dapat dilihat di <a href="https://siakad.itera.ac.id/" target="_blank">SIAKAD ITERA</a> dan permintaan perubahan nilai hanya bisa dilakukan setelah melaksanakan <span class="fw-semibold">Sidang Ujian</span>.
        </p>

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">

            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="150px">Tipe Sidang</th>
                        <th scope="col" width="150px">Dosen Pembimbing</th>
                        <th scope="col" width="150px">Status</th>
                        <th scope="col" width="150px">Keputusan</th>
                        <th scope="col" width="150px">Aksi</th>
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
                searching: false,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x2.PendaftaranSidang4-json1') }}',
                columns: [{
                        data: 'tipe_sidang',
                        name: 'tipe_sidang',
                    },
                    {
                        data: 'dosen',
                        name: 'dosen'
                    },
                    {
                        data: 'status_sidang',
                        name: 'status_sidang',
                    },
                    {
                        data: 'ket_hasil',
                        name: 'ket_hasil',
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
                    [5, 'desc']
                ]
            });
        });
    </script>
@endpush
