@extends('layouts.admLayout')

@section('content')
    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Kelola Data Pengguna Administrator</span>
        <hr />

        <!-- DATA TABLE -->
        <div class="col-12 mt-2">
            <table class="table table-bordered table-hover" id="table1">
                <thead>
                    <tr class="table-light">
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="card py-3">

        <span class="fw-semibold fs-5 mb-1">Tambah Data</span>
        <hr />

        <!-- FORM INPUT -->
        <form id="formAdmin" action="{{ route('x0.DataPengguna1-form1') }}" method="post" autocomplete="off">
            <div class="row g-2 mt-2">
                @csrf
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
            // GET DATA 
            var table = $('#table1').DataTable({
                "dom": 'ftp',
                lengthChange: false,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: '{{ route('x0.DataPengguna1-json1') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
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
                    }
                ],
                order: [
                    [3, 'desc']
                ]
            });

            // RESET PASSWORD
            $(document).on('click', '.btn-reset', function() {
                var userId = $(this).data('id');
                if (confirm('Yakin ingin reset password akun ini?')) {
                    $.ajax({
                        url: '/admin/dtp/reset-password/' + userId,
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

            // DELETE USER
            $(document).on('click', '.btn-delete', function() {
                var userId = $(this).data('id');
                if (confirm('Yakin ingin menghapus akun ini?')) {
                    $.ajax({
                        url: '/admin/dtp/delete-user/' + userId,
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
        $('#formAdmin').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: $('#name').val(),
                    email: $('#email').val(),

                },
                success: function(response) {
                    alert(response.message);
                    $('#formAdmin')[0].reset();
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
