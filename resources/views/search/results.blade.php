<!-- resources/views/search/results.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả Tìm kiếm</title>
</head>
<body>
    <h1>Kết quả Tìm kiếm cho: "{{ $query }}"</h1>

    @if($results->isEmpty())
        <p>Không tìm thấy kết quả nào.</p>
    @else
        <ul>
            @foreach($results as $sach)
                <li>{{ $sach->tieu_de }} - {{ $sach->gioi_thieu }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>
