<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قائمة المستندات</title>
    <style>
        @page {
            margin: 20mm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            direction: rtl;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-size: 12px;
        }
        .status-draft {
            background: #6c757d;
        }
        .status-review1 {
            background: #0d6efd;
        }
        .status-proofread {
            background: #ffc107;
            color: #000;
        }
        .status-finalapproval {
            background: #198754;
        }
    </style>
</head>
<body>
    <h1>قائمة المستندات - {{ now()->format('Y-m-d') }}</h1>
    <table>
        <thead>
            <tr>
                <th>العنوان</th>
                <th>النوع</th>
                <th>المرحلة</th>
                <th>المالك</th>
                <th>المنوط</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $doc)
            <tr>
                <td>{{ $doc->title }}</td>
                <td>{{ $doc->type_label }}</td>
                <td>
                    <span class="status status-{{ $doc->current_stage }}">
                        {{ $doc->stage_label }}
                    </span>
                </td>
                <td>{{ $doc->creator?->name ?? '-' }}</td>
                <td>{{ $doc->assignee?->name ?? '-' }}</td>
                <td>{{ $doc->created_at->format('Y-m-d') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">
                    لا توجد مستندات
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

















