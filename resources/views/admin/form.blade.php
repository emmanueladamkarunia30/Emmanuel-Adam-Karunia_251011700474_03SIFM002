@extends('admin.template')

@php($isEdit = isset($project))

@section('title', $isEdit ? 'Edit Project' : 'Tambah Project')
@section('heading', $isEdit ? 'Edit Project' : 'Tambah Project')
@section('subtitle', $isEdit ? 'Perbarui data project' : 'Tambahkan data project baru')

@section('header-action')
<a href="{{ route('admin.home') }}" class="btn btn-outline-secondary btn-sm px-3">
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

    <form action="{{ $isEdit ? route('admin.update', $project->id) : route('admin.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="row g-3">

            <div class="col-md-8">
                <label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
                <input type="text" id="title" name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $project->title ?? '') }}" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select id="kategori" name="kategori"
                        class="form-select @error('kategori') is-invalid @enderror" required>
                    <option value="">-- Pilih kategori --</option>
                    @foreach(['Hardware', 'Software'] as $kat)
                        <option value="{{ $kat }}"
                            {{ old('kategori', $project->kategori ?? '') == $kat ? 'selected' : '' }}>
                            {{ $kat }}
                        </option>
                    @endforeach
                </select>
                @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea id="description" name="description" rows="4"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $project->description ?? '') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-8">
                <label for="teknologi" class="form-label">Teknologi</label>
                <input type="text" id="teknologi" name="teknologi"
                       class="form-control @error('teknologi') is-invalid @enderror"
                       placeholder="contoh: Laravel & Bootstrap"
                       value="{{ old('teknologi', $project->teknologi ?? '') }}">
                @error('teknologi') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select id="status" name="status"
                        class="form-select @error('status') is-invalid @enderror" required>
                    @foreach(['selesai' => 'Selesai', 'In Progress' => 'In Progress'] as $val => $label)
                        <option value="{{ $val }}"
                            {{ old('status', $project->status ?? 'selesai') == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label for="image" class="form-label">Gambar</label>

                @if($isEdit && $project->image)
                    <div class="mb-2">
                        <img src="{{ $project->image_url }}" alt="{{ $project->title }}"
                             style="width:120px;height:90px;object-fit:cover;border-radius:4px;">
                        <div class="form-text">Gambar saat ini. Pilih file baru hanya jika ingin menggantinya.</div>
                    </div>
                @endif

                <input type="file" id="image" name="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept=".jpg,.jpeg,.png,.webp">
                <div class="form-text">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</div>
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm px-4">
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.home') }}" class="btn btn-outline-secondary btn-sm px-4">Batal</a>
        </div>

    </form>
</div>

@endsection