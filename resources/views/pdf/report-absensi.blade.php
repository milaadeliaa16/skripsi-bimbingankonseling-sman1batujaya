<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Report Absensi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h2 { margin: 0 0 8px; }
        p.meta { margin: 0 0 12px; color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; vertical-align: top; }
        th { background: #f3f4f6; text-align: left; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <h2>Report Absensi Siswa</h2>
    <p class="meta">Dicetak: {{ $generatedAt->format('d M Y H:i') }} WIB | Total Data: {{ $records->count() }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 28%;">Nama Siswa</th>
                <th style="width: 18%;">Kelas</th>
                <th style="width: 16%;">Status</th>
                <th style="width: 24%;">Tanggal Absensi</th>
                <th style="width: 14%;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td>{{ $record->student?->name ?? '-' }}</td>
                    <td>{{ $record->kelas?->name ?? '-' }}</td>
                    <td>{{ ucfirst($record->status ?? '-') }}</td>
                    <td>{{ $record->status === 'terlambat' ? optional($record->date)->format('d M Y H:i') : '-' }}</td>
                    <td>{{ $record->notes ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
