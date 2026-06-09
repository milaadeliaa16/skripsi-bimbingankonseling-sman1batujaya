<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Report Masalah Absensi Siswa</title>
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
    <h1>Report Masalah Absensi Siswa</h1>
    <p>Digenerate pada: {{ $generatedAt->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th class="text-center">Alpa</th>
                <th class="text-center">Izin</th>
                <th class="text-center">Terlambat</th>
                <th class="text-center">Sakit</th>
                <th class="text-center">Total Masalah Absensi</th>
                <th>Tanggal Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record->student_name ?? '-' }}</td>
                    <td>{{ $record->kelas_name ?? '-' }}</td>
                    <td class="text-center">{{ (int) ($record->total_alpa ?? 0) }}</td>
                    <td class="text-center">{{ (int) ($record->total_izin ?? 0) }}</td>
                    <td class="text-center">{{ (int) ($record->total_terlambat ?? 0) }}</td>
                    <td class="text-center">{{ (int) ($record->total_sakit ?? 0) }}</td>
                    <td class="text-center">{{ (int) ($record->total_issue_count ?? 0) }}</td>
                    <td>{{ $record->last_issue_date ? \Illuminate\Support\Carbon::parse($record->last_issue_date)->format('d M Y H:i') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
