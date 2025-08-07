@extends('layouts.mhsLayout')

@section('content')
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Data Bimbingan Tugas Akhir</span>
        <hr />

        <div class="row">
            <div class="col-12">
                <p class="p-2 border border-secondary rounded-3 fst-italic">
                    <span class="fw-semibold fst-normal" style="color:darkblue">Penting!</span><br>Jika periode <span
                        class="fw-semibold">Pengajuan Proposal</span> masih dibuka,
                    masih ada kemungkinan bahwa <span class="fw-semibold">Dosen Pendamping (pembimbing kedua)</span> akan
                    ditetapkan, tunggu informasi <a href="{{ route('x2.PeriodeTA') }}">penutupan periode</a> terlebih dahulu.
                </p>
            </div>
        </div>

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="150px">Topik</th>
                        <th scope="col" width="150px">Judul</th>
                        <th scope="col" width="150px">Dosen Pembimbing</th>
                        <th scope="col" width="150px">Dosen Pendamping</th>
                        <th scope="col" width="150px">Status Bimbingan</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="card mb-4 py-3">

        <span class="fw-semibold fs-5 mb-1">Data Dosen Pembimbing</span>
        <hr />

        <!-- FORM DATA -->
        <div class="row border-bottom">

            <span class="my-3 fw-bold fst-italic "> Dosen Pembimbing 1</span>

            <div class="col-12 col-md-6">
                <label for="input2" class="form-label">Nama Lengkap</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen" aria-label="input2"
                        value="{{ $bimbingan->dosen->name ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input2" class="form-label">NIP/NRK</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen" aria-label="input2"
                        value="{{ $bimbingan->dosen->nip ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input2" class="form-label">E-mail</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen" aria-label="input2"
                        value="{{ $bimbingan->dosen->email ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input2" class="form-label">No. HP</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen" aria-label="input2"
                        value="{{ $bimbingan->dosen->wa_dos ?? 'N/A' }}" disabled>
                </div>
            </div>
        </div>

        <div class="row">

            <span class="my-3 fw-bold fst-italic "> Dosen Pembimbing 2</span>

            <div class="col-12 col-md-6">
                <label for="input2" class="form-label">Nama Lengkap</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen" aria-label="input2"
                        value="{{ $bimbingan->dosen2->name ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input2" class="form-label">NIP/NRK</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen" aria-label="input2"
                        value="{{ $bimbingan->dosen2->nip ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input2" class="form-label">E-mail</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen" aria-label="input2"
                        value="{{ $bimbingan->dosen2->email ?? 'N/A' }}" disabled>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="input2" class="form-label">No. HP</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-white text-primary fw-semibold" id="dosen" aria-label="input2"
                        value="{{ $bimbingan->dosen2->wa_dos ?? 'N/A' }}" disabled>
                </div>
            </div>
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
                searching: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x2.TugasAkhir2-json1') }}',
                columns: [{
                        data: 'topik',
                        name: 'topik'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'dosen_name',
                        name: 'dosen_name'
                    },
                    {
                        data: 'dosen2_name',
                        name: 'dosen2_name'
                    },
                    {
                        data: 'status_bimbingan',
                        name: 'status_bimbingan'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [5, 'desc']
                ]
            });
        });
    </script>
@endpush
