@extends('layouts.dosLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Form Penilaian Mahasiswa</span>
        <hr />

        <div class="row g-2">

            <form id="formReview" action="{{ route('x1.DataMahasiswa3-form1') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- HIDDEN INPUT -->
                <input type="hidden" name="submission_id" id="submission_id" value="{{ $idPengajuan }}">
                <input type="hidden" name="status_sidang" value="Dinilai">

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
                        <label for="input3" class="form-label">Judul Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $judul ?? '' }}" disabled>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label for="input3" class="form-label">Topik Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $titleTopik ?? '' }}" disabled>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Dosen Pembimbing</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control bg-light" value="{{ $namaDosen ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Dosen Pendamping</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control bg-light" value="{{ $namaDosen2 ?? '' }}" disabled>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Penguji 1</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $penguji }}" disabled>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label for="input2" class="form-label">Penguji 2</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $penguji2 ?? '' }}" disabled>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <label for="input3" class="form-label">Tipe Sidang</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" value="{{ $tipe_sidang ?? '' }}" disabled>
                        </div>
                    </div>
                    
                    <!-- FORM INPUT -->
                    <div class="col-12">
                        <label for="formFile1" class="form-label">Upload File Nilai</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="form1" id="form1"
                                accept=".pdf,.doc,.docx" required>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-2 mb-4">
                        <input type="hidden" name="ket_lulus" id="ket_lulus">
                        <label for="dropdown" class="form-label">Keterangan Nilai</label>
                        <div class="dropdown">
                            <button id="dropdownMenuButton" class="btn btn-light dropdown-toggle form-control text-start"
                                type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                                -- assign --
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"
                                        data-value="Lulus tanpa
                                                    perbaikan">Lulus
                                        tanpa
                                        perbaikan</a></li>
                                <li><a class="dropdown-item" href="#"
                                        data-value="Lulus dengan
                                                    perbaikan">Lulus
                                        dengan
                                        perbaikan</a></li>
                                <li><a class="dropdown-item" href="#" data-value="Lulus bersyarat">Lulus bersyarat</a>
                                </li>
                                <li><a class="dropdown-item" href="#" data-value="Tidak lulus">Tidak lulus</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                    <div class="col-12 col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary flex-fill" id="btnSubmit">
                                Submit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger text-white flex-fill">
                                Reset
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
            $('#formReview').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('x1.DataMahasiswa3-form1') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#btnSubmit').prop('disabled', true).text('Submitting...');
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        let err = xhr.responseJSON?.message || 'Terjadi kesalahan.';
                        alert(err);
                    },
                    complete: function() {
                        $('#btnSubmit').prop('disabled', false).text('Submit');
                    }
                });
            });

            // DROPDOWN
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value'); // Ambil nilai dari atribut data-value
                var selectedText = $(this).text(); // Ambil teks dari item yang dipilih

                $('#ket_lulus').val(selectedValue); // Set nilai input tersembunyi
                $('#dropdownMenuButton').text(selectedText); // Ubah teks tombol dropdown (opsional)
            });
        });
    </script>
@endpush
