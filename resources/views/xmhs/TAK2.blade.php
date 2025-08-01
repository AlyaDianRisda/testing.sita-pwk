@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Data Bimbingan Tugas Akhir</span>
        <hr />

        <div class="row">
            <div class="col-12">
                <p class="p-2 border border-secondary rounded-3 fst-italic">
                    <span class="fw-semibold fst-normal" style="color:darkblue">Penting!</span><br>Jika periode "Pengajuan Proposal" masih dibuka,
                    masih ada kemungkinan bahwa dosen pembimbing ke-2 akan ditetapkan, tunggu informasi penutupan periode
                    terlebih dahulu.
                </p>
            </div>
        </div>

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th style="col">Topik</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Dosen Pembimbing</th>
                        <th scope="col">Dosen Pendamping</th>
                        <th scope="col">Status Bimbingan</th>
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
                searching: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x2.TugasAkhir2-json1') }}',
                columns: [{
                        data: 'topik',
                        name: 'topik'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'dosen_name',
                        name: 'dosen_name'
                    },
                    {
                        data: 'dosen2_name',
                        name: 'dosen2_name'
                    },
                    {
                        data: 'status_bimbingan',
                        name: 'status_bimbingan'
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
