<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Project</title>
    <style>
        @page { margin: 28px 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #000; }
        h2 { text-align: center; margin: 0 0 4px; font-size: 16px; }
        .tanggal { text-align: center; margin: 0 0 14px; }
        table { width: 100%; border-collapse: collapse; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        th, td { border: 1px solid #000; padding: 5px 6px; vertical-align: middle; }
        th { background: #e9ecef; text-align: left; }
        .c { text-align: center; }
        img { width: 60px; height: 40px; }
    </style>
</head>
<body>
    <h2>LAPORAN DATA PROJECT</h2>
    <p class="tanggal">Tanggal Cetak: {{ $tanggal }}</p>

    <table>
        <thead>
            <tr>
                <th class="c" style="width:4%">No</th>
                <th class="c" style="width:11%">Foto</th>
                <th style="width:17%">Judul Project</th>
                <th style="width:35%">Deskripsi</th>
                <th style="width:20%">Teknologi</th>
                <th style="width:13%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
                <tr>
                    <td class="c">{{ $loop->iteration }}</td>
                    <td class="c">
                        @if($project->pdf_image)
                            <img src="{{ $project->pdf_image }}" alt="">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->description }}</td>
                    <td>{{ $project->teknologi ?? '-' }}</td>
                    <td>{{ $project->status ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="c">Belum ada data project.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>