<!DOCTYPE html>
<html>
<head>
    <title>イベントリマインダー通知</title>
</head>
<body>
    <p>{{ $event->title }} のイベントが明日開始されます。</p>
    <p>詳細は以下の通りです:</p>
    <ul>
        <li>開始時間: {{ $event->start_at }}</li>
        {{-- <li>場所: {{ $event->location }}</li> --}}
    </ul>
</body>
</html>
