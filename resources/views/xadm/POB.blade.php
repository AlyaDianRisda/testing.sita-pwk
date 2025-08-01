@extends('layouts.admLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Upload Template dan Panduan Mahasiswa</span>
        <hr />

        <div class="row">

            <!-- FORM DATA -->
            <form id="form1" action="#" method="post" autocomplete="off">

                <div class="row">

                    <div class="col-12">
                        <label for="formFile1" class="form-label">Buku Panduan Mahasiswa</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="inputFile1" aria-label="inputFile1" multiple>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="formFile2" class="form-label">Template Laporan</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="inputFile2" aria-label="inputFile2" multiple>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="formFile2" class="form-label">Template Form Pendaftaran</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="inputFile2" aria-label="inputFile2" multiple>
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
    </div>
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Upload Form dan Panduan Pembimbing</span>
        <hr />

        <div class="row">

            <!-- FORM DATA -->
            <form id="form1" action="#" method="post" autocomplete="off">

                <div class="row">

                    <div class="col-12">
                        <label for="formFile1" class="form-label">Buku Panduan Dosen</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="inputFile1" aria-label="inputFile1" multiple>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="formFile1" class="form-label">Template Form Dosen</label>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="inputFile1" aria-label="inputFile1" multiple>
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
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
