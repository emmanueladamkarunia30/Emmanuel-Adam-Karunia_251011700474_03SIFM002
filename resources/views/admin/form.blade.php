@extends('admin.template')

@php($isEdit = isset($user))
@php($isSelf = $isEdit && $user->id === auth()->id())
@php($isActive = session()->hasOldInput() ? (bool) old('is_active') : ($user->is_active ?? true))

@section('title', $isEdit ? 'Edit User' : 'Tambah User')
@section('heading', $isEdit ? 'Edit User' : 'Tambah User')
@section('subtitle', $isEdit ? 'Perbarui akun dan hak akses' : 'Buat akun baru')

@section('header-action')
<a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm px-3">
    &larr; Kembali
</a>
@endsection

@section('content')

<div class="dashboard-card">

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Data belum valid:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('admin.users.update', $user->id) : route('admin.users.store') }}"
          method="POST">

        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row g-3">

            <div class="col-md-6">
                <label for="name" class="form-label">Nama <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name ?? '') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email ?? '') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label">
                    Password @if(! $isEdit)<span class="text-danger">*</span>@endif
                </label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       autocomplete="new-password" {{ $isEdit ? '' : 'required' }}>
                <div class="form-text">
                    {{ $isEdit ? 'Kosongkan jika tidak ingin mengganti password.' : 'Minimal 8 karakter.' }}
                </div>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" autocomplete="new-password">
            </div>

            <div class="col-md-6">
                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                <select id="role" name="role"
                        class="form-select @error('role') is-invalid @enderror"
                        {{ $isSelf ? 'disabled' : '' }} required>
                    @foreach(\App\Models\User::ROLES as $val => $label)
                        <option value="{{ $val }}"
                            {{ old('role', $user->role ?? 'editor') == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @if($isSelf)
                    <div class="form-text">Role akun Anda sendiri tidak bisa diubah.</div>
                @endif
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check mb-2">
                    <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input"
                           {{ $isActive ? 'checked' : '' }}
                           {{ $isSelf ? 'disabled' : '' }}>
                    <label for="is_active" class="form-check-label">Akun aktif (boleh login)</label>
                </div>
            </div>

        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm px-4">
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm px-4">Batal</a>
        </div>

    </form>
</div>

@endsection