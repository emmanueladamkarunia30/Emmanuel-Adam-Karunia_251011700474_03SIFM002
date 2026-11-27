@extends('admin.template')

@section('title', 'Kelola User')
@section('heading', 'Admin Dashboard')
@section('subtitle', 'Halaman Kelola User')

@section('header-action')
<button type="button" class="btn btn-primary btn-sm px-3" id="btnAdd">Tambah User</button>
@endsection

@section('content')
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table table-bordered w-100" id="tb_user">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT --}}
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="userForm" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger d-none" id="formErrors"></div>

                    <input type="hidden" id="user_id">

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" id="phone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select id="role" class="form-select">
                                @foreach(\App\Models\User::ROLES as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" id="address" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password <span class="text-danger" id="pwRequired">*</span>
                        </label>
                        <input type="password" id="password" class="form-control" autocomplete="new-password">
                        <div class="form-text" id="pwHelp">Minimal 8 karakter.</div>
                    </div>

                    <div class="form-check d-none" id="activeWrap">
                        <input type="checkbox" id="is_active" class="form-check-input">
                        <label for="is_active" class="form-check-label">Akun aktif (boleh login)</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4" id="btnSave">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    const API_USERS = "{{ url('/api/users') }}";
    const ME_ID = {{ auth()->id() }};
    const modal = new bootstrap.Modal(document.getElementById('userModal'));

    const esc = (s) => $('<div>').text(s || '-').html();

    // Semua request ke API memakai token dari session
    function api(method, url, data) {
        return $.ajax({
            url: url,
            method: method,
            data: data ? JSON.stringify(data) : undefined,
            contentType: 'application/json',
            dataType: 'json',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + API_TOKEN
            }
        });
    }

    function errorText(xhr) {
        if (xhr.status === 401) {
            return 'Token API tidak valid. Silakan logout lalu login ulang.';
        }
        const r = xhr.responseJSON;
        if (r && r.errors) {
            return Object.values(r.errors).flat().join('\n');
        }
        return (r && r.message) ? r.message : 'Terjadi kesalahan pada server.';
    }

    $.fn.dataTable.ext.errMode = 'none';

    const table = $('#tb_user').DataTable({
        ajax: {
            url: "{{ route('list-users') }}",
            dataSrc: 'data',
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + API_TOKEN
            },
            error: function (xhr) {
                toastr.error(errorText(xhr));
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'name', render: (d) => esc(d) },
            { data: 'email', render: (d) => esc(d) },
            { data: 'phone', render: (d) => esc(d) },
            { data: 'address', render: (d) => esc(d) },
            {
                data: 'role',
                render: (d) => `<span class="badge ${d === 'admin' ? 'bg-dark' : 'bg-secondary'}">${esc(d)}</span>`
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    const self = row.id === ME_ID;
                    return `
                        <button class="btn btn-warning btn-sm btn-edit" data-id="${row.id}">Edit</button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="${row.id}"
                            ${self ? 'disabled title="Tidak bisa menghapus akun sendiri"' : ''}>Delete</button>
                    `;
                }
            }
        ]
    });

    function resetForm() {
        $('#userForm')[0].reset();
        $('#user_id').val('');
        $('#formErrors').addClass('d-none').text('');
        $('#role').prop('disabled', false);
    }

    // ---------- TAMBAH ----------
    $('#btnAdd').on('click', function () {
        resetForm();
        $('#userModalTitle').text('Tambah User');
        $('#pwRequired').show();
        $('#pwHelp').text('Minimal 8 karakter.');
        $('#activeWrap').addClass('d-none');
        modal.show();
    });

    // ---------- EDIT ----------
    $('#tb_user').on('click', '.btn-edit', function () {
        const row = table.row($(this).closest('tr')).data();
        resetForm();

        $('#userModalTitle').text('Edit User');
        $('#user_id').val(row.id);
        $('#name').val(row.name);
        $('#email').val(row.email);
        $('#phone').val(row.phone || '');
        $('#address').val(row.address || '');
        $('#role').val(row.role);
        $('#is_active').prop('checked', !!row.is_active);

        $('#pwRequired').hide();
        $('#pwHelp').text('Kosongkan jika tidak ingin mengganti password.');
        $('#activeWrap').removeClass('d-none');

        // Role dan status akun sendiri tidak bisa diubah
        if (row.id === ME_ID) {
            $('#role').prop('disabled', true);
            $('#is_active').prop('disabled', true);
        } else {
            $('#is_active').prop('disabled', false);
        }

        modal.show();
    });

    // ---------- SIMPAN (tambah / edit) ----------
    $('#userForm').on('submit', function (e) {
        e.preventDefault();

        const id = $('#user_id').val();
        const payload = {
            name: $('#name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            address: $('#address').val(),
            password: $('#password').val()
        };

        if (!$('#role').prop('disabled')) {
            payload.role = $('#role').val();
        }

        if (id) {
            if (!$('#is_active').prop('disabled')) {
                payload.is_active = $('#is_active').is(':checked');
            }
        }

        const req = id ? api('PUT', API_USERS + '/' + id, payload)
                       : api('POST', API_USERS, payload);

        $('#btnSave').prop('disabled', true);
        $('#formErrors').addClass('d-none');

        req.done(function (res) {
            modal.hide();
            toastr.success(res.message);
            table.ajax.reload(null, false);
        }).fail(function (xhr) {
            $('#formErrors').removeClass('d-none').text(errorText(xhr)).css('white-space', 'pre-line');
        }).always(function () {
            $('#btnSave').prop('disabled', false);
        });
    });

    // ---------- DELETE ----------
    $('#tb_user').on('click', '.btn-delete', function () {
        const row = table.row($(this).closest('tr')).data();

        if (!confirm('Hapus user "' + row.name + '"? Tindakan ini tidak bisa dibatalkan.')) {
            return;
        }

        api('DELETE', API_USERS + '/' + row.id)
            .done(function (res) {
                toastr.success(res.message);
                table.ajax.reload(null, false);
            })
            .fail(function (xhr) {
                toastr.error(errorText(xhr));
            });
    });
});
</script>
@endpush