@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-2 py-3">

        <span class="fw-semibold fs-5 mb-1">Halo, Selamat Datang 👋</span>
        <hr />

        <!-- STATUS -->
        <span class="my-3 fw-bold fst-italic "> Progres Sidang Akhir :</span>
        <div class="row mb-5">
            <div class="col-6">
                <div class="border-start border-start-4 border-start-info px-3 mb-3">
                    <div class="small text-body-secondary fw-semibold text-truncate">Sidang Terakhir</div>
                    <div class="fs-5 fw-semibold">{{ $sidangTerakhir->tipe_sidang ?? 'N/A' }}</div>
                    <div class="small text-info text-truncate">Status: {{ $sidangTerakhir->status_sidang ?? '-' }}</div>
                </div>
            </div>
            <div class="col-6">
                <div class="border-start border-start-4 border-start-danger px-3 mb-3">
                    <div class="small text-body-secondary fw-semibold text-truncate">Pengajuan Terbaru</div>
                    <div class="fs-5 fw-semibold">{{ $pengajuanTerbaru->tipe_sidang ?? 'N/A' }}</div>
                    <div class="small text-info text-truncate">Status: {{ $pengajuanTerbaru->status_sidang ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- DATA TABLE -->
        <span class="my-3 fw-bold fst-italic "> Informasi Periode :</span>
        <div class="col-12 mb-5">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-info">
                        <th scope="col" width="150px">Nama Periode</th>
                        <th scope="col" width="150px">Tipe Periode</th>
                        <th scope="col" width="150px">Tgl Buka</th>
                        <th scope="col" width="150px">Tgl Tutup</th>
                        <th scope="col" width="150px">Status</th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- DATA TABLE -->
        <span class="my-3 fw-bold fst-italic "> Jadwal Sidang Terbaru :</span>
        <div class="col-12 mb-5">
            <table class="table table-hover" id="table2">
                <thead>
                    <tr class="table-warning">
                        <th scope="col" width="150px">Nama Mahasiswa</th>
                        <th scope="col" width="150px">Tipe Sidang</th>
                        <th scope="col" width="150px">Tipe Pengajuan</th>
                        <th scope="col" width="150px">Tanggal</th>
                        <th scope="col" width="150px">Waktu</th>
                        <th scope="col" width="150px">Pembimbing</th>
                        <th scope="col" width="150px">Pendamping</th>
                        <th scope="col" width="150px">Penguji 1</th>
                        <th scope="col" width="150px">Penguji 2</th>
                        <th scope="col" width="150px">Lokasi</th>
                        <th scope="col" width="150px">Skema</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="card mb-2 py-3">

        <span class="fw-semibold fs-5 mb-1">Downloadable Files 📄</span>
        <hr />

        <!-- DOWNLOADABLES -->
        <div class="row">
            <div class="col-12">
                <p class="">Daftar Panduan Mahasiswa </p>
                <a href="/"> Link download Panduan Mahasiswa </a>
            </div>
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
                searching: false,
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
                                return data.split(' ')[0];
                            }
                            return data;
                        }
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        render: function(data, type, row) {
                            if (type === 'display' && data) {
                                return data.split(' ')[0];
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

            // TABLE 2
            $('#table2').DataTable({
                dom: 'ftp',
                scrollX: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x2.Homepage-json2') }}',
                columns: [{
                        data: 'mhs_name',
                        name: 'mhs_name'
                    },
                    {
                        data: 'tipe_sidang',
                        name: 'tipe_sidang'
                    },
                    {
                        data: 'tipe_pengajuan',
                        name: 'tipe_pengajuan'
                    },
                    {
                        data: 'tanggal_sidang',
                        name: 'tanggal_sidang'
                    },
                    {
                        data: 'waktu_sidang',
                        name: 'waktu_sidang'
                    },
                    {
                        data: 'pembimbing',
                        name: 'pembimbing'
                    },
                    {
                        data: 'pendamping',
                        name: 'pendamping'
                    },
                    {
                        data: 'penguji_1',
                        name: 'penguji_1'
                    },
                    {
                        data: 'penguji_2',
                        name: 'penguji_2'
                    },
                    {
                        data: 'lokasi_sidang',
                        name: 'lokasi_sidang'
                    },
                    {
                        data: 'skema_sidang',
                        name: 'skema_sidang'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [11, 'desc']
                ] // atau ganti ke kolom sesuai kebutuhan
            });
        });
    </script>
@endpush
