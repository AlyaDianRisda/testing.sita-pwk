@extends('layouts.admLayout')

@push('style')
    <style>
        .dropdown-menu.scrollable-dropdown {
            max-height: 250px;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>
@endpush

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Data Pengajuan Jadwal Seminar / Sidang</span>
        <hr />

        <div class="row">

            <form id="formReview" action="{{ route('x0.PlottingSidang-form1') }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf

                <!-- HIDDEN INPUT -->
                <input type="hidden" name="submission_id" id="submission_id" value="{{ $idPengajuan }}">
                <input type="hidden" name="waktu_sidang" id="waktu_sidang" value="{{ $data?->waktu_sidang }}">
                <input type="hidden" name="dosen_pembimbing" id="dosen_pembimbing" value="{{ $idDosen }}">
                <input type="hidden" name="dosen_pendamping" id="dosen_pendamping" value="{{ $idDosen2 }}">
                <input type="hidden" name="dosen_penguji1" id="dosen_penguji1" value="{{ $data?->penguji_id }}" required>
                <input type="hidden" name="dosen_penguji2" id="dosen_penguji2" value="{{ $data?->penguji2_id }}">

                @php
                    $selectedPenguji1 = $data?->penguji?->name ?? '-- assign --';
                    $selectedPenguji2 = $data?->penguji2?->name ?? '-- assign --';
                    $selectedTanggal = $data?->jadwal_sidang ?? '';
                    $selectedWaktu = $data?->waktu_sidang ?? '-- assign --';
                @endphp

                <!-- FORM DATA -->
                <div class="row g-2">

                    <div class="col-12 col-md-2">
                        <label for="input2" class="form-label">NIM</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $nimUser ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12 col-md-10">
                        <label for="input2" class="form-label">Nama Mahasiswa</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $namaUser ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Dosen Pembimbing</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $namaDosen ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Dosen Pendamping</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control bg-light" value="{{ $namaDosen2 ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="input1" class="form-label">Topik Tugas Akhir</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control bg-light" value="{{ $titleTopik ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="input3" class="form-label">Judul Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $judul ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="input3" class="form-label">Informasi Pendaftaran</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $tipe_sidang ?? '' }}" disabled>
                        </div>

                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $tipe_pengajuan ?? '' }}"
                                disabled>
                        </div>
                    </div>

                    <span class="">Daftar Berkas Mahasiswa</span>

                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            @if ($form1)
                                <a href="{{ asset('storage/' . $form1) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary form-control">{{ $formLabels[0] }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            @if ($form2)
                                <a href="{{ asset('storage/' . $form2) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary form-control">{{ $formLabels[1] }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            @if ($form3)
                                <a href="{{ asset('storage/' . $form3) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary form-control">{{ $formLabels[2] }}</a>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="input-group mb-3">
                            @if ($form4)
                                <a href="{{ asset('storage/' . $form4) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary form-control">{{ $formLabels[3] }}</a>
                            @endif
                        </div>
                    </div>



                    <!-- FORM INPUT -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Dosen Penguji 1</label>
                        <div class="dropdown mb-3">
                            <button class="btn btn-light dropdown-toggle form-control text-start" type="button"
                                data-coreui-toggle="dropdown" aria-expanded="false" id="dropdownMenuButton1">
                                {{ $selectedPenguji1 }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item pnj1" href="#" data-value="">-- assign --</a>
                                </li>
                                @foreach ($penguji as $dos1)
                                    <li><a class="dropdown-item pnj1" href="#"
                                            data-value="{{ $dos1->id }}">{{ $dos1->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Dosen Penguji 2 (Opsional)</label>
                        <div class="dropdown mb-3">
                            <button class="btn btn-light dropdown-toggle form-control text-start" type="button"
                                data-coreui-toggle="dropdown" aria-expanded="false" id="dropdownMenuButton2">
                                {{ $selectedPenguji2 }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item pnj2" href="#" data-value="">-- assign --</a>
                                </li>
                                @foreach ($penguji2 as $dos2)
                                    <li><a class="dropdown-item pnj2" href="#"
                                            data-value="{{ $dos2->id }}">{{ $dos2->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="tanggal" class="form-label">Pilih Tanggal</label>
                        <input id="datepicker1" name="date1" class="form-control" value="{{ $selectedTanggal }}" />
                    </div>

                    @php
                        $startTime = strtotime('08:00:00');
                        $endTime = strtotime('17:00:00');
                        $interval = 15 * 60;
                    @endphp

                    <div class="col-12 col-md-6">
                        <label for="waktu" class="form-label">Pilih Waktu</label>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle form-control text-start" type="button"
                                data-coreui-toggle="dropdown" aria-expanded="false" id="dropdownMenuButton3">
                                {{ $selectedWaktu }}
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

                    <div class="col-12 col-md-6 mb-3">
                        <label for="lokasi" class="form-label">Atur Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control"
                            placeholder="Contoh: Ruang 1 PWK, Lt.2 Gedung B"
                            value="{{ old('lokasi', $data->lokasi_sidang ?? '') }}" required>
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">Skema Pelaksanaan</label>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle form-control text-start" type="button"
                                data-coreui-toggle="dropdown" aria-expanded="false" id="dropdownSkemaBtn">
                                {{ $data?->skema_sidang ?? '-- assign --' }}
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item skema-item" href="#" data-value="">-- assign --</a>
                                </li>
                                <li>
                                    <a class="dropdown-item skema-item" href="#" data-value="Online">Online</a>
                                </li>
                                <li>
                                    <a class="dropdown-item skema-item" href="#" data-value="Offline">Offline</a>
                                </li>
                            </ul>
                        </div>
                        <input type="hidden" name="skema_pelaksanaan" id="skema_pelaksanaan"
                            value="{{ $data?->skema_sidang }}">
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="link_sidang" class="form-label">Link Zoom/Google Meet (Opsional)</label>
                        <input type="text" name="link_sidang" id="link_sidang" class="form-control"
                            placeholder="Contoh: https://meet.google.com/xyz "
                            value="{{ old('link_sidang', $data->link_sidang ?? '') }}">
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <div class="d-flex gap-2">
                        <button type="button" id="btnAcc" class="btn btn-sm btn-primary flex-fill">
                            Terima
                        </button>
                        <button type="button" id="btnRej" class="btn btn-sm btn-danger text-white flex-fill">
                            Tolak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // DATEPICKER
            $("#datepicker1").datepicker({
                uiLibrary: "bootstrap5",
                format: "yyyy-mm-dd",
                showOtherMonths: true,
                modal: true
            });

            // DROPDOWN PENGUJI 1
            $('.pnj1').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value');
                var selectedText = $(this).text();

                $('#dosen_penguji1').val(selectedValue);
                $('#dropdownMenuButton1').text(selectedText);
            });

            // DROPDOWN PENGUJI 2
            $('.pnj2').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value');
                var selectedText = $(this).text();

                $('#dosen_penguji2').val(selectedValue);

                // Jika value kosong, tampilkan -- assign -- sebagai teks
                if (!selectedValue) {
                    $('#dropdownMenuButton2').text('-- assign --');
                } else {
                    $('#dropdownMenuButton2').text(selectedText);
                }
            });

            // DROPDOWN WAKTU SIDANG
            $('.waktu').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value');
                var selectedText = $(this).text();

                $('#waktu_sidang').val(selectedValue); // Set nilai input tersembunyi
                $('#dropdownMenuButton3').text(selectedText); // Ubah teks tombol dropdown (opsional)
            });
        });

        // POST DATA
        function sendReview(status) {
            // Fungsi sendReview() untuk di script ini saja, tidak dibawa ke backend dan digunakan untuk mengelola button acc dan rej.
            var data = {
                _token: '{{ csrf_token() }}',
                submission_id: $('#submission_id').val(),
                status_sidang: status
            };

            // Hanya jika "Dibuat", tambahkan dosen & tanggal
            if (status === 'Dibuat') {
                data.dosen_penguji1 = $('#dosen_penguji1').val();
                data.dosen_penguji2 = $('#dosen_penguji2').val();
                data.dosen_pembimbing = $('#dosen_pembimbing').val();
                data.dosen_pendamping = $('#dosen_pendamping').val();
                data.waktu_sidang = $('#waktu_sidang').val();
                data.date1 = $('#datepicker1').val();
                data.lokasi = $('#lokasi').val();
                data.skema_pelaksanaan = $('#skema_pelaksanaan').val();
                data.link_sidang = $('#link_sidang').val();


            }

            const redirectUrl = "{{ route('x0.PlottingSidang') }}";

            $.ajax({
                url: $('#formReview').attr('action'),
                method: 'POST',
                data: data,
                success: function(response) {
                    alert(response.message);
                    $('#formReview')[0].reset();
                    $('#dropdownMenuButton1').text('-- assign --');
                    $('#dropdownMenuButton2').text('-- assign --');
                    $('#dropdownMenuButton3').text('-- assign --');
                    window.location.href = redirectUrl;
                },
                error: function(xhr) {
                    let msg = "Terjadi kesalahan.";
                    if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors).map(e => e[0]).join('\n');
                    }
                    alert(msg);
                }
            });
        }

        // SETTING SKEMA
        $('.skema-item').on('click', function(e) {
            e.preventDefault();

            var selectedValue = $(this).data('value');
            var selectedText = $(this).text();

            $('#skema_pelaksanaan').val(selectedValue);
            $('#dropdownSkemaBtn').text(selectedText);
        });

        // ACCEPT
        $('#btnAcc').on('click', function() {
            sendReview('Dibuat');
        });

        // REJECT
        $('#btnRej').on('click', function() {
            sendReview('Ditolak');
        });
    </script>
@endpush
