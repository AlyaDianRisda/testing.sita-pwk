@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Informasi Periode Tugas Akhir</span>
        <hr />

        <p class="p-2 border border-secondary rounded-3 fst-italic">
            <span class="fw-semibold fst-normal" style="color:darkblue">Penting!</span>
            <br>1. Periode <span class="fw-semibold">Pengajuan Proposal</span> adalah periode untuk mengajukan judul tugas akhir dan dosen pembimbing. 
            <br>2. Periode <span class="fw-semibold">Seminar Proposal </span>dan <span class="fw-semibold">Seminar Pembahasan</span> adalah periode untuk pendaftaran seminar.
            <br>3. Periode <span class="fw-semibold">Sidang Ujian</span> adalah periode untuk pendaftaran sidang ujian/sidang akhir.
            <br>4. Setelah melakukan <span class="fw-semibold">Sidang Ujian</span>, kamu dapat mengajukan perubahan nilai melalui halaman <a href="{{ route('x2.PendaftaranSidang-4') }}">penilaian</a>.
        </p>

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
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
