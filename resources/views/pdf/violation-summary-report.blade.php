<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Report Pelanggaran Siswa</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 4px 0; }
        p { margin: 0 0 12px 0; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; vertical-align: middle; }
        thead th { background: #f3f4f6; font-weight: 700; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h1>Report Pelanggaran Siswa</h1>
    <p>Digenerate pada: {{ $generatedAt->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jenis Pelanggaran</th>
                <th class="text-center">Total Kasus</th>
                <th class="text-center">Total Poin</th>
                <th>Konseling Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record->student_name ?? '-' }}</td>
                    <td>{{ $record->kelas_name ?? '-' }}</td>
                    <td>{{ $record->type_of_violation ?? '-' }}</td>
                    <td class="text-center">{{ (int) ($record->total_cases ?? 0) }}</td>
                    <td class="text-center">{{ (int) ($record->total_points ?? 0) }}</td>
                    <td>{{ $record->last_counseling_at ? \Illuminate\Support\Carbon::parse($record->last_counseling_at)->format('d M Y H:i') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
