@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Daftar Topik Dosen Periode Aktif</span>
        <hr />

        <p class="p-2 border border-secondary rounded-3 fst-italic">
            <span class="fw-semibold fst-normal" style="color:darkblue">Penting!</span>
            <br>1. Gunakan halaman ini untuk <span class="fw-semibold">memilih topik tugas akhir </span>dalam pengajuan proposalmu.
            <br>2. Pengajuan akan ditutup sementara jika jumlah pengajuan sudah lebih dari <span class="fw-semibold">15 orang</span> atau ditutup permanen jika jumlah <span class="fw-semibold">mahasiswa diterima </span>sudah memenuhi kuota.
        </p>

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" colspan="7" id="table1">Nama Periode</th>
                    </tr>
                    <tr class="table-light">
                        <th scope="col" width="150px">Keahlian</th>
                        <th scope="col" width="150px">Topik Tugas Akhir</th>
                        <th scope="col" width="150px">Nama Dosen</th>
                        <th scope="col" width="150px">Kuota</th>
                        <th scope="col" width="150px">Diajukan</th>
                        <th scope="col" width="150px">Diterima</th>
                        <th scope="col" width="150px">Pengajuan</th>
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
