@extends('layouts.admLayout')

@section('content')
    <div class="card mb-2 py-3">

        <span class="fw-semibold fs-5 mb-1">Halo, Selamat Datang 👋</span>
        <hr />

        <!-- STATUS -->
        {{-- <span class="my-3 fw-bold fst-italic"> Progres Sidang Akhir :</span>
        <div class="row">
            <div class="col-6">
                <div class="border-start border-start-4 border-start-info px-3 mb-3">
                    <div class="small text-body-secondary text-truncate">Sidang Terakhir</div>
                    <div class="fs-5 fw-semibold">Seminar Proposal</div>
                </div>
            </div>
            <div class="col-6">
                <div class="border-start border-start-4 border-start-danger px-3 mb-3">
                    <div class="small text-body-secondary text-truncate">Pengajuan Terbaru</div>
                    <div class="fs-5 fw-semibold">Seminar Pembahasan</div>
                </div>
            </div>
        </div> --}}

        {{-- <span class="my-2"></span> --}}

        <!-- DATA TABLE -->
        <span class="my-3 fw-bold fst-italic"> Periode yang Terbuka :</span>
        <div class="col-12">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="110px">Nama Periode</th>
                        <th scope="col" width="100px">Tipe Periode</th>
                        <th scope="col" width="125px">Tgl Buka</th>
                        <th scope="col" width="125px">Tgl Tutup</th>
                        <th scope="col" width="100px">Status</th>
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
                ajax: '{{ route('x0.PeriodeTA-json1') }}',
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
