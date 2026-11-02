<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Low-stock report</title>
    <style>
        body { color: #1d2939; font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { color: #1f3a5f; font-size: 22px; margin-bottom: 4px; }
        p { color: #667085; margin-top: 0; }
        table { border-collapse: collapse; margin-top: 20px; width: 100%; }
        th, td { border: 1px solid #dce3eb; padding: 8px; text-align: left; }
        th { background: #1f3a5f; color: #fff; }
        tr:nth-child(even) { background: #f3f6fa; }
    </style>
</head>

<body>
    <h1>Low-stock report</h1>
    <p>Generated {{ $generatedAt->format('Y-m-d H:i') }}. Low stock means quantity is at or below the reorder level.</p>
    @include('reports._table')
</body>

</html>
