<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload YouTube</title>
</head>
<body>

    <h1>Upload Video ke YouTube</h1>

    <form action="/youtube/upload" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label>Video</label>
            <br>
            <input type="file" name="video" accept="video/*" required>
        </div>

        <br>

        <div>
            <label>Judul</label>
            <br>
            <input type="text" name="title" required>
        </div>

        <br>

        <div>
            <label>Deskripsi</label>
            <br>
            <textarea name="description" rows="5"></textarea>
        </div>

        <br>

        <button type="submit">Upload ke YouTube</button>
    </form>

</body>
</html>