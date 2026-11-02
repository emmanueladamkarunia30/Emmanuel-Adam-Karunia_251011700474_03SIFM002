@extends('admin.template')

@section('title', 'Admin Dashboard')
@section('heading', 'Admin Dashboard')
@section('subtitle', 'Halaman Kelola Projects')

@section('header-action')
<a href="{{ route('admin.create') }}" class="btn btn-primary btn-sm px-3">
Tambah Data
</a>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="dashboard-card">

{{-- FILTER DAN SEARCH --}}
<form action="{{ route('admin.home') }}" method="GET">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

        <div class="d-flex align-items-center gap-2">
            <label class="text-secondary mb-0">Tampilkan</label>

            <select name="kategori"
                    class="form-select form-select-sm"
                    style="width:150px;"
                    onchange="this.form.submit()">

                <option value="">Semua kategori</option>

                <option value="Hardware" {{ request('kategori') == 'Hardware' ? 'selected' : '' }}>
                    Hardware
                </option>

                <option value="Software" {{ request('kategori') == 'Software' ? 'selected' : '' }}>
                    Software
                </option>

            </select>
        </div>

        <div class="d-flex align-items-center gap-2">
            <label class="text-secondary mb-0">Search:</label>

            <input type="text"
                   name="search"
                   class="form-control form-control-sm"
                   style="width:250px;"
                   placeholder="Cari project..."
                   value="{{ request('search') }}">

            <button type="submit" class="btn btn-primary btn-sm">
                Cari
            </button>
        </div>

    </div>

</form>

{{-- TABEL --}}
<div class="table-responsive">

    <table class="table table-hover align-middle">

        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Teknologi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            @forelse($projects as $project)

                <tr>
                    <td>{{ $projects->firstItem() + $loop->index }}</td>

                    <td>
                        <img src="{{ $project->image_url }}"
                             width="48"
                             height="38"
                             style="object-fit:cover;border-radius:3px;"
                             alt="{{ $project->title }}">
                    </td>

                    <td>
                        <strong>{{ $project->title }}</strong>

                        <br>

                        @if($project->kategori == 'Hardware')
                            <span class="badge-hardware">HARDWARE</span>
                        @elseif($project->kategori == 'Software')
                            <span class="badge-software">SOFTWARE</span>
                        @else
                            <span class="badge bg-secondary">BELUM DITENTUKAN</span>
                        @endif
                    </td>

                    <td>
                        {{ \Illuminate\Support\Str::limit($project->description, 75) }}
                    </td>

                    <td>{{ $project->teknologi ?? '-' }}</td>

                    <td>
                        @if(strtolower($project->status) == 'selesai')
                            <span class="badge bg-success">Selesai</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ $project->status ?? '-' }}</span>
                        @endif
                    </td>

                    <td>
                        <div class="d-flex gap-1">

                            <a href="{{ route('admin.edit', $project->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('admin.destroy', $project->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete
                                </button>

                            </form>

                        </div>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center py-5">
                        Belum ada data project.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

{{-- PAGINATION --}}
<div class="mt-3">
    {{ $projects->withQueryString()->links() }}
</div>

</div>

@endsection