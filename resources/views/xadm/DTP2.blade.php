@extends('layouts.admLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Kelola Data Pengguna Dosen</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">NIP/NRK</th>
                        <th scope="col">No. HP</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Tipe Dosen</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Tambah Data</span>
        <hr />

        <!-- INPUT FORM -->
        <form id="formDosen" action="{{ route('x0.DataPengguna2-form1') }}" method="post" autocomplete="off">
            <div class="row g-2 mt-2">
                @csrf
                
                <div class="row mb-3">

                    <div class="col-12 col-md-6">
                        <input type="hidden" name="tipeDosen" id="selectedTipeDos">
                        <label for="tipe_dos" class="form-label">Tipe Dosen</label>
                        <div class="dropdown">
                            <button id="dropdownMenuButton"
                                class="btn btn-light border dropdown-toggle form-control text-start" type="button"
                                data-coreui-toggle="dropdown" aria-expanded="false">
                                Pilih Tipe Dosen
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" data-value="Utama">Utama</a></li>
                                <li><a class="dropdown-item" href="#" data-value="Pendamping">Pendamping</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <div class="input-group mb-3">
                            <input type="text" name="name" class="form-control" id="name" aria-label="name">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label">E-mail ITERA</label>
                        <div class="input-group mb-3">
                            <input type="text" name="email" class="form-control" id="email" aria-label="email">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <label for="nip" class="form-label">NIP/NRK</label>
                        <div class="input-group mb-3">
                            <input type="text" name="nip" class="form-control" id="nip" aria-label="nip">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <label for="no_telf" class="form-label">No. HP / Kontak</label>
                        <div class="input-group mb-3">
                            <input type="text" name="no_telf" class="form-control" id="no_telf" aria-label="no_telf">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Password Default</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control bg-light" placeholder="default-sitapwk" aria-label="title"
                                disabled>
                        </div>
                    </div>
                </div>

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
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // TABLE 1
            var table = $('#table1').DataTable({
                "dom": 'ftp',
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('x0.DataPengguna2-json1') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nip',
                        name: 'nip'
                    },
                    {
                        data: 'wa_dos',
                        name: 'wa_dos'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'tipe_dos',
                        name: 'tipe_dos'
                    },
                    {
                        data: 'id',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                            <button class="btn btn-primary text-white btn-sm btn-reset flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;" data-id="${data}">Reset Password</button>
                            <button class="btn btn-danger text-white btn-sm btn-delete flex-fill" style="min-width: 100px; padding: 4px 6px; font-size: 0.85rem;" data-id="${data}">Hapus Data</button>
                        `;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false
                    },

                ],
                order: [
                    [6, 'desc']
                ]
            });

            // DROPDOWN TIPE DOS
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();

                var selectedValue = $(this).data('value');
                var selectedText = $(this).text();

                $('#selectedTipeDos').val(selectedValue);
                $('#dropdownMenuButton').text(selectedText);
            });

            // RESET PASSWORD
            $(document).on('click', '.btn-reset', function() {
                var userId = $(this).data('id');
                if (confirm('Yakin ingin reset password akun ini?')) {
                    $.ajax({
                        url: '/admin/post-json/' + userId + '/reset-password',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert(response.message);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Gagal reset password.');
                        }
                    });
                }
            });

            // DELETE USERDATA
            $(document).on('click', '.btn-delete', function() {
                var userId = $(this).data('id');
                if (confirm('Yakin ingin menghapus akun ini?')) {
                    $.ajax({
                        url: '/admin/del-json/' + userId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            alert(response.message);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Gagal menghapus data.');
                        }
                    });
                }
            });

        });

        // POST DATA 
        $('#formDosen').on('submit', function(e) {
            e.preventDefault();

            var dosType = $('#selectedTipeDos').val();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val(),
                    email: $('#email').val(),
                    tipe_dos: dosType,
                    nip: $('#nip').val(),
                    no_telf: $('#no_telf').val(),

                },
                success: function(response) {
                    alert(response.message);
                    $('#formDosen')[0].reset();
                    $('#table1').DataTable().ajax.reload();
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
