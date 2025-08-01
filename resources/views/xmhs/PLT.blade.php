@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Daftar Topik Dosen Periode Aktif</span>
        <hr />

        <p class="p-2 border border-secondary rounded-3 fst-italic">
            <span class="fw-semibold fst-normal" style="color:darkblue">Penting!</span>
            <br>1. Gunakan halaman ini untuk memilih topik tugas akhirmu.
            <br>2. Pengajuan akan ditutup sementara jika jumlah pengajuan sudah lebih dari 15 orang atau ditutup permanen jika jumlah diterima sudah memenuhi kuota.
        </p>

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" colspan="7" id="table1">Nama Periode</th>
                    </tr>
                    <tr class="table-light">
                        <th scope="col">Keahlian</th>
                        <th scope="col">Topik Tugas Akhir</th>
                        <th scope="col">Nama Dosen</th>
                        <th scope="col">Kuota</th>
                        <th scope="col">Diajukan</th>
                        <th scope="col">Diterima</th>
                        <th scope="col">Pengajuan</th>
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
                ajax: {
                    url: '{{ route('x2.TopikTA-json1') }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        if (json.data.length > 0) {
                            var title = json.data[0].period_name;
                            $('#table1').text(
                                title);
                        } else {
                            $('#table1').text('Periode Pengajuan Proposal Tidak Dibuka');
                        }

                        return json.data;
                    }
                },
                columns: [{
                        data: 'focus',
                        name: 'focus'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'dosen_name',
                        name: 'dosen_name'
                    },
                    {
                        data: 'kuota',
                        name: 'kuota'
                    },
                    {
                        data: 'submission_count',
                        name: 'submission_count',
                    },
                    {
                        data: 'validated_sc',
                        name: 'validated_sc',
                    },
                    {
                        data: 'submission',
                        name: 'submission',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>
@endpush
