@extends('layouts.admLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5">Manajemen Periode Tugas Akhir</span>
        <hr />

        <form id="form1" action="{{ route('x0.PeriodeTA-form1') }}" method="post" autocomplete="off">
            <div class="row g-2">
                @csrf

                <!-- HIDDEN INPUT -->
                <input type="hidden" name="type" id="selectedPeriodType">

                <!-- FORM INPUT -->
                <div class="col-12 col-md-2">
                    <label for="jenis_periode" class="form-label">Jenis Periode</label>
                    <div class="dropdown">
                        <button id="dropdownMenuButton" class="btn btn-light border dropdown-toggle form-control text-start"
                            type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                            Pilih Periode
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-value="Pengajuan Proposal">Pengajuan
                                    Proposal</a></li>
                            <li><a class="dropdown-item" href="#" data-value="Seminar Proposal">Seminar
                                    Proposal</a></li>
                            <li><a class="dropdown-item" href="#" data-value="Seminar Pembahasan">Seminar
                                    Pembahasan</a></li>
                            <li><a class="dropdown-item" href="#" data-value="Sidang Ujian">Sidang
                                    Ujian</a></li>
                            {{-- <li><a class="dropdown-item" href="#" data-value="Sidang Mandiri">Seminar/ Sidang
                                    Mandiri</a></li> --}}
                        </ul>
                    </div>
                </div>

                <div class="col-12 col-md-10">
                    <label for="title" class="form-label">Title / Nama Periode</label>
                    <div class="input-group mb-3">
                        <input type="text" name="title" class="form-control" id="title" aria-label="title">
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label for="tanggal_mulai" class="form-label">Tgl Buka (Hari ini)</label>
                    <input id="datepicker1" name="start_date" class="form-control" />
                </div>

                <div class="col-12 col-md-6">
                    <label for="tanggal_selesai" class="form-label">Tgl Tutup (Manual)</label>
                    <input id="datepicker2" name="end_date" class="form-control" />
                </div>

                <p class="p-2 border border-secondary rounded-3 fst-italic">
                    <span class="fw-semibold fst-normal" style="color:darkblue">Penting!</span><br>1. Harap diperhatikan
                    bahwa tanggal
                    harus dibuka pada hari-H pembukaan periode. <br>2. Tanggal tutup hanya bersifat informatif, penutupan
                    tidak otomatis mengikuti tanggal. <br>
                    3. Pembukaan periode "Pengajuan Proposal" akan mengaktifkan table kuota dosen dibawah.
                </p>

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

        <!-- DATA TABLE -->
        <div class="col-12 mt-4">
            <table class="table table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col" width="150px">Nama Periode</th>
                        <th scope="col" width="150px">Tipe Periode</th>
                        <th scope="col" width="150px">Tanggal Buka</th>
                        <th scope="col" width="150px">Tanggal Tutup</th>
                        <th scope="col" width="150px">Status</th>
                        <th scope="col" width="150px">Penutupan</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="card py-3">

        <span class="fw-semibold fs-5">Kuota Dosen Pengajuan Proposal</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-hover" id="table2">
                <thead>
                    <tr class="table-light">
                        <th scope="col" colspan="4" id="periode-title">Nama Periode</th>
                    </tr>
                    <tr class="table-light">
                        <th scope="col" width="150px">Nama Dosen</th>
                        <th scope="col" width="150px">Tipe Dosen</th>
                        <th scope="col" width="150px">Kuota</th>
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
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
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
                        name: 'is_open'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [6, 'desc']
                ]
            });

            // DATEPICKER
            const today = new Date().toISOString().split('T')[0];
            $("#datepicker1").val(today)
                .prop("readonly", true);;
            $("#datepicker2").datepicker({
                uiLibrary: "bootstrap5",
                format: "yyyy-mm-dd",
                showOtherMonths: true,
                modal: true
            });

            // DROPDOWN TIPE PERIODE
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value');
                var selectedText = $(this).text();

                $('#selectedPeriodType').val(selectedValue);
                $('#dropdownMenuButton').text(selectedText);
            });

            // TABLE 2
            $('#table2').DataTable({
                dom: 'ftp',
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: '{{ route('x0.PeriodeTA-json2') }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        if (json.data.length > 0) {
                            var title = json.data[0].title;
                            $('#periode-title').text(
                                title);
                        } else {
                            $('#periode-title').text('Periode Pengajuan Proposal Tidak Dibuka');
                        }

                        return json.data;
                    }
                },
                columns: [{
                        data: 'dosen',
                        name: 'dosen.name'
                    },
                    {
                        data: 'tipe_dosen',
                        name: 'dosen.tipe_dos'
                    },
                    {
                        data: 'kuota_ta',
                        name: 'kuota_ta'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'period.title',
                        visible: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    }
                ],
                order: [
                    [1, 'desc']
                ]
            });

            // EDIT KUOTA
            $('#table2').on('click', '.edit-kuota', function() {
                let row = $(this).closest('tr');
                let kuotaCell = row.find('td:eq(2)');
                let currentKuota = $(this).data('kuota');
                let id = $(this).data('id');

                if (kuotaCell.find('input').length) return;

                kuotaCell.html(`
                <div class="input-group input-group-sm border border-secondary rounded">
                    <input type="number" class="form-control" value="${currentKuota}" min="0">
                    <button class="btn btn-sm btn-dark save-kuota" data-id="${id}">Save</button>
                </div>
                `);
            });

            // SAVE KUOTA
            $('#table2').on('click', '.save-kuota', function() {
                let id = $(this).data('id');
                let input = $(this).closest('.input-group').find('input');
                let newKuota = input.val();

                $.ajax({
                    url: `/admin/pta/kuota/${id}`,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kuota_ta: newKuota
                    },
                    success: function(response) {
                        $('#table2').DataTable().ajax.reload(null,
                            false);
                    },
                    error: function(xhr) {
                        let message = 'Gagal memperbarui kuota.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        alert(message);
                        console.error(xhr);
                    }

                });
            });
        });

        // POST DATA
        $('#form1').on('submit', function(e) {
            e.preventDefault();

            var periodType = $('#selectedPeriodType').val();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title: $('#title').val(),
                    type: periodType,
                    start_date: $('#datepicker1').val(),
                    end_date: $('#datepicker2').val()
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

        // CLOSE PERIODE
        function tutupPeriode(id) {
            $.ajax({
                url: '/admin/pta/close/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#table1').DataTable().ajax.reload();
                    $('#table2').DataTable().ajax.reload();
                    alert(response.message);
                },
                error: function() {
                    alert('Gagal menutup periode.');
                }
            });
        }

        // DELETE PERIODE
        function hapusPeriode(id) {
            fetch(`/admin/pta/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) alert(data.message);
                    location.reload();
                })
                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                    alert('Gagal menghapus data.');
                });
        }
    </script>
@endpush
