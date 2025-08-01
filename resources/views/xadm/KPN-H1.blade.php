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
        <span class="fw-semibold fs-5 mb-1">Review Penilaian Sidang Mahasiswa</span>
        <hr />

        <div class="row">

            <!-- FORM DATA -->
            <form id="formReview" action="{{ route('x0.KelolaPenilaian-form1') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="submission_id" id="submission_id" value="{{ $idPengajuan }}">

                @php
                    $selectedPenguji1 = $data?->penguji?->name ?? 'N/A';
                    $selectedPenguji2 = $data?->penguji2?->name ?? 'N/A';
                    $selectedTanggal = $data?->jadwal_sidang ?? '';
                    $selectedWaktu = $data?->waktu_sidang ?? 'N/A';
                @endphp

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

                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Penguji 1</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $selectedPenguji1 ?? '' }}"
                                disabled>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Penguji 2</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $selectedPenguji2 ?? '-' }}"
                                disabled>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Tanggal Pelaksanaan</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $selectedTanggal ?? '' }}"
                                disabled>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Skema Pelaksanaan</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $data?->skema_sidang ?? '-' }}"
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

                    <div class="col-12 col-md-2">
                        <div class="d-flex gap-2">
                            <button type="button" id="btnAcc" class="btn btn-sm btn-primary flex-fill">
                                Terima
                            </button>
                            <button type="button" id="btnRej" class="btn btn-sm btn-danger text-white flex-fill">
                                Tahan
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
            // POST DATA
            function sendReview(status) {
                // Fungsi sendReview() untuk di script ini saja, tidak dibawa ke backend dan digunakan untuk mengelola button acc dan rej.
                var data = {
                    _token: '{{ csrf_token() }}',
                    submission_id: $('#submission_id').val(),
                    status_sidang: status
                };


                const redirectUrl = "{{ route('x0.KelolaPenilaian') }}";

                $.ajax({
                    url: $('#formReview').attr('action'),
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        console.log('Success:', response);
                        alert(response.message);
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

            // ACCEPT
            $('#btnAcc').on('click', function() {
                sendReview('Selesai');
            });

            // REJECT
            $('#btnRej').on('click', function() {
                sendReview('Dinilai');
            });
        });
    </script>
@endpush
