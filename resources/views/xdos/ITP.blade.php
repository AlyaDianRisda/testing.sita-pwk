@extends('layouts.dosLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Input Topik Dosen Pembimbing</span>
        <hr />

        <div class="row">

            <!-- FORM INPUT -->
            <form id="formTopik" action="{{ route('x1.TopikTA-form1') }}" method="post" autocomplete="off">
                <div class="row">
                    <div class="col-12">
                        <label for="input1" class="form-label">Topik Tugas Akhir</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="title" aria-label="title">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-5">
                        <label for="input1" class="form-label">Kuota Mahasiswa</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" id="kuota_topik" name="kuota_topik" min="1"
                                max="20" step="1">
                        </div>
                        <p class="p-2 border border-secondary rounded-3 fst-italic">
                            <span class="fw-semibold fst-normal" style="color:darkblue">Penting!</span><br> Sisa kuota
                            anda periode ini : <span class="fw-bold">{{ $kuota_tersisa }} / {{ $kuota_total }}
                                slot</span> <br>1. Silahkan
                            dibagi ke beberapa topik.
                            <br> 2. Hanya dosen pembimbing yang dapat membuat topik. Jika kuota anda 0, silahkan hubungi Koordinator Tugas Akhir.
                    </div>
                </div>

                <div class="row">
                    <input type="hidden" name="focus" id="selectedBidang">
                    <div class="col-12 col-md-4 mb-2">
                        <label for="dropdown" class="form-label">Kelompok Keahlian</label>
                        <div class="dropdown">
                            <button id="dropdownMenuButton"
                                class="btn btn-light dropdown-toggle form-control text-start border" type="button"
                                data-coreui-toggle="dropdown" aria-expanded="false">
                                Pilih KK
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-value="PWPB">KK-PWPB</a></li>
                                <li><a class="dropdown-item" href="#" data-value="PPIB">KK-PPIB</a></li>
                                <li><a class="dropdown-item" href="#" data-value="P3KB">KK-P3KB</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-danger btn-sm text-light flex-fill">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- DATA TABLE -->
        <div class="col-12 mt-4">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Topik</th>
                        <th scope="col">Keahlian</th>
                        <th scope="col">Periode</th>
                        <th scope="col">Kuota</th>
                        <th scope="col">Diajukan</th>
                        <th scope="col">Diterima</th>
                        <th scope="col">Hapus</th>
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
            var table = $('#table1').DataTable({
                dom: 'ftp',
                scrollX: true,
                lengthChange: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('x1.TopikTA-json1') }}',
                columns: [{
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'focus',
                        name: 'focus'
                    },
                    {
                        data: 'period_name',
                        name: 'period.name',
                    },
                    {
                        data: 'kuota_topik',
                        name: 'kuota_topik',
                    },
                    {
                        data: 'submission_count',
                        name: 'submission_count',
                    },
                    {
                        data: 'validated_sc',
                        name: 'validated_sc',
                    },
                    {
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-danger btn-sm btn-delete text-light flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;" data-id="${data}">Hapus Data</button>
                        `;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [7, 'desc']
                ]
            });

            // DROPDOWN
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value');
                var selectedText = $(this).text();

                $('#selectedBidang').val(selectedValue);
                $('#dropdownMenuButton').text(selectedText);
            });

            // DELETE TOPIK
            $(document).on('click', '.btn-delete', function() {
                var topicId = $(this).data('id');
                if (confirm('Yakin ingin menghapus data ini?')) {
                    $.ajax({
                        url: '/dosen/itp/delete/' + topicId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            alert(response.message);
                            location.reload();
                        },
                        error: function(xhr) {
                            alert(
                                'Data gagal dihapus.');
                        }
                    });
                }
            });
        });

        // POST TOPIK
        $('#formTopik').on('submit', function(e) {
            e.preventDefault();

            var topicFocus = $('#selectedBidang').val();
            var kuota = $('#kuota_topik').val();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: $('#title').val(),
                    focus: topicFocus,
                    kuota: kuota,

                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
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
        });
    </script>
@endpush
