@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Form Pendaftaran Seminar Pembahasan</span>
        <hr />

        <div class="row">

            <form id="formSemhas" action="{{ route('x2.PendaftaranSidang2-form1') }}" method="POST"
                enctype="multipart/form-data" autocomplete="off">
                @csrf

                <div class="row g-2">

                    <!-- HIDDEN INPUT -->
                    <input type="hidden" name="id_topik" id="id_topik" value="{{ $idTopik }}">
                    <input type="hidden" name="id_dosen" id="id_dosen" value="{{ $idDosen }}">
                    <input type="hidden" name="id_dosen2" id="id_dosen2" value="{{ $idDosen2 }}">
                    <input type="hidden" name="inp_judul" id="inp_judul" value="{{ $judul }}">
                    <input type="hidden" name="waktu_sidang" id="waktu_sidang">
                    <input type="hidden" name="tipe_pengajuan" id="selectedTipePengajuan">

                    <!-- FORM DATA -->
                    <div class="col-12 col-md-4">
                        <label for="input2" class="form-label">Dosen Pembimbing</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" id="dosen" aria-label="input2"
                                value="{{ $namaDosen ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12 col-md-8">
                        <label for="input1" class="form-label">Topik Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" id="topik" aria-label="input1"
                                value="{{ $titleTopik ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="input3" class="form-label">Judul Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" id="judul" aria-label="input3"
                                value="{{ $judul ?? '' }}" disabled>
                        </div>
                    </div>

                    <!-- FORM INPUT -->
                    <div class="col-12">
                        <label for="formFile2" class="form-label">Form Pendaftaran</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="form1" id="form1"
                                accept=".pdf,.doc,.docx" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="formFile2" class="form-label">Logbook</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="form2" id="form2"
                                accept=".pdf,.doc,.docx" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="formFile2" class="form-label">Draft Laporan</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="form3" id="form3"
                                accept=".pdf,.doc,.docx" required>
                        </div>
                    </div>

                    <div class="col-12 col-md-3 mb-4">
                        <label for="jenis_periode" class="form-label">Tipe Pengajuan</label>
                        <div class="dropdown">
                            <button id="dropdownMenuButton"
                                class="btn btn-light border dropdown-toggle form-control text-start" type="button"
                                data-coreui-toggle="dropdown" aria-expanded="false">
                                -- assign --
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item tipe_pengajuan" href="#"
                                        data-value="Pengajuan Periode">Pengajuan
                                        Periode</a></li>
                                <li><a class="dropdown-item tipe_pengajuan" href="#"
                                        data-value="Pengajuan Mandiri">Pengajuan
                                        Mandiri</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- OPTIONAL FORM -->
                <div class="row g-2">
                    <span class="fw-semibold"> Menu Opsional </span>

                    <div class="col-12 col-md-6">
                        <label for="tanggal" class="form-label">Pilih Tanggal (Y/M/D)</label>
                        <input id="datepicker1" name="date1" class="form-control" value="" />
                    </div>

                    @php
                        $startTime = strtotime('08:00:00');
                        $endTime = strtotime('17:00:00');
                        $interval = 15 * 60;
                    @endphp

                    <div class="col-12 col-md-6">
                        <label for="waktu" class="form-label">Pilih Waktu (WIB)</label>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle form-control text-start" type="button"
                                data-coreui-toggle="dropdown" aria-expanded="false" id="dropdownMenuButton3">
                                -- assign --
                            </button>
                            <ul class="dropdown-menu scrollable-dropdown">
                                <li>
                                    <a class="dropdown-item waktu" href="#" data-value="">-- assign --</a>
                                </li>
                                @for ($time = $startTime; $time <= $endTime; $time += $interval)
                                    <li>
                                        <a class="dropdown-item waktu" href="#"
                                            data-value="{{ date('H:i:s', $time) }}">
                                            {{ date('H:i:s', $time) }}
                                        </a>
                                    </li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-2 mt-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary flex-fill">
                            Submit
                        </button>
                        <button type="reset" class="btn btn-sm btn-danger text-light flex-fill">
                            Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- DATA TABLE -->
        <div class="col-12 mt-5">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Topik</th>
                        <th scope="col">Judul</th>
                        <th scope="col">Tipe Sidang</th>
                        <th scope="col">Tipe Pengajuan</th>
                        <th scope="col">Form Pendaftaran</th>
                        <th scope="col">Logbook</th>
                        <th scope="col">Draft Laporan</th>
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
                searching: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x2.PendaftaranSidang2-json1') }}',
                columns: [{
                        data: 'topik',
                        name: 'topik'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
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
                        data: 'form1',
                        name: 'form1',
                    },
                    {
                        data: 'form2',
                        name: 'form2',
                    },
                    {
                        data: 'form3',
                        name: 'form3',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [8, 'desc']
                ]
            });

            // POST DATA
            $('#formSemhas').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert(response.message || 'Proposal berhasil diajukan!');
                        $('#formSemhas')[0].reset();
                        $('#table1').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        let msg = "Terjadi kesalahan.";
                        if (xhr.responseJSON?.message) {
                            msg = xhr.responseJSON.message;
                        } else if (xhr.responseJSON?.errors) {
                            msg = Object.values(xhr.responseJSON.errors).map(e => e[0]).join(
                                '\n');
                        }
                        alert(msg);
                    }
                });
            });

            // DATEPICKER
            $("#datepicker1").datepicker({
                uiLibrary: "bootstrap5",
                format: "yyyy-mm-dd",
                showOtherMonths: true,
                modal: true
            });

            // DROPDOWN TIPE PENGAJUAN
            $('.tipe_pengajuan').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value');
                var selectedText = $(this).text();

                $('#selectedTipePengajuan').val(selectedValue);
                $('#dropdownMenuButton').text(selectedText);
            });

            // DROPDOWN WAKTU SIDANG
            $('.waktu').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value');
                var selectedText = $(this).text();

                $('#waktu_sidang').val(selectedValue);
                $('#dropdownMenuButton3').text(selectedText);
            });
        });
    </script>
@endpush
