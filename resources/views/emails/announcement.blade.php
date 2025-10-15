<!DOCTYPE html>
<html>
<head>
    <title>Announcement</title>
</head>
<body>
    <h2>{{ $announcement->title }}</h2>
    <p>{{ $announcement->message }}</p>
    <small>📅 {{ $announcement->created_at->format('M d, Y h:i A') }}</small>
</body>
</html>
