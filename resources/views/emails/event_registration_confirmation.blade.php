<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>イベント参加登録確認</title>
</head>
<body>
    <h1>{{ $user->name }}様</h1>
    <p>以下のイベントに参加登録が完了しました。</p>
    <p>イベント名: {{ $event->title }}</p>
    <p>開催日時: {{ $event->start_at }} 〜 {{ $event->end_at }}</p>
    {{-- <p>場所: {{ $event->venue }}</p> --}}
    <p>ご参加ありがとうございます。</p>
</body>
</html>