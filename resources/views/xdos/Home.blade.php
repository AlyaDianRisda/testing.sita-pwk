@extends('layouts.dosLayout')

@section('content')
    <div class="card mb-2 py-3">

        <span class="fw-semibold fs-5 mb-1">Halo, Selamat Datang 👋</span>
        <hr />

        <!-- STATUS -->
        <span class="my-3 fw-bold fst-italic "> Data Mahasiswa :</span>
        
        <div class="row mb-2">
            <div class="col-6">
                <div class="border-start border-start-4 border-start-primary px-3 mb-3">
                    <div class="small text-body-secondary fw-semibold text-truncate">Jumlah Mahasiswa</div>
                    <div class="fs-5 fw-semibold">{{ $jumlahMahasiswa ?? 'N/A' }}&nbsp;&nbsp;Mahasiswa</div>
                    <div class="small text-primary text-truncate"><a class="text-decoration-none" href="{{ route('x1.DataMahasiswa-2') }}">🕊️ Data Mahasiswa Dibimbing</a></div>
                </div>
            </div>
            <div class="col-6">
                <div class="border-start border-start-4 border-start-info px-3 mb-3">
                    <div class="small text-body-secondary fw-semibold text-truncate">Pengajuan Topik</div>
                    <div class="fs-5 fw-semibold">{{ $pengajuanTopik ?? 'N/A' }}&nbsp;&nbsp;Pengajuan</div>
                    <div class="small text-primary text-truncate"><a class="text-decoration-none" href="{{ route('x1.DataMahasiswa-1') }}">🕊️ Data Pengajuan Proposal</a></div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-6">
                <div class="border-start border-start-4 border-start-danger px-3 mb-3">
                    <div class="small text-body-secondary fw-semibold text-truncate">Agenda Sidang</div>
                    <div class="fs-5 fw-semibold">{{ $permintaanSidang ?? 'N/A' }}&nbsp;&nbsp;Agenda</div>
                    <div class="small text-primary text-truncate"><a class="text-decoration-none" href="{{ route('x1.AgendaSidang') }}">🕊️ Halaman Agenda Sidang</a></div>
                </div>
            </div>
            <div class="col-6">
                <div class="border-start border-start-4 border-start-success px-3 mb-3">
                    <div class="small text-body-secondary fw-semibold text-truncate">Permintaan Nilai</div>
                    <div class="fs-5 fw-semibold">{{ $permintaanNilai ?? 'N/A' }}&nbsp;&nbsp;Permintaan</div>
                    <div class="small text-primary text-truncate"><a class="text-decoration-none" href="{{ route('x1.DataMahasiswa-3') }}">🕊️ Halaman Permintaan Nilai</a></div>
                </div>
            </div>
        </div>

        {{-- <span class="my-2"></span> --}}

        <!-- DATA TABLE -->
        <span class="my-3 fw-bold fst-italic ">Data Periode Terkini :</span>
        <div class="col-12 mb-5">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="150px">Nama Periode</th>
                        <th scope="col" width="150px">Tipe Periode</th>
                        <th scope="col" width="150px">Tgl Buka</th>
                        <th scope="col" width="150px">Tgl Tutup</th>
                        <th scope="col" width="150px">Status</th>
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
                <p class="">Daftar Panduan Dosen </p>
                <a href="/"> Link download Panduan Dosen </a>
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
        });
    </script>
@endpush
