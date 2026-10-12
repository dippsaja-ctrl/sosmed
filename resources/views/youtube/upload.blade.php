<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Upload YouTube</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f7fb;
            min-height: 100vh;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 28px;
            color: #222;
            margin-bottom: 8px;
        }

        .header p {
            color: #777;
            font-size: 15px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #ddd;
            border-radius: 9px;
            font-size: 15px;
            outline: none;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: #ff0000;
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }

        input[type="file"] {
            width: 100%;
            padding: 15px;
            border: 2px dashed #ddd;
            border-radius: 10px;
            background: #fafafa;
            cursor: pointer;
        }

        .file-info {
            margin-top: 8px;
            font-size: 13px;
            color: #888;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 9px;
            background: #ff0000;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background: #d90000;
        }

        .youtube-icon {
            width: 55px;
            height: 38px;
            background: #ff0000;
            border-radius: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .youtube-icon span {
            color: white;
            font-size: 22px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div class="youtube-icon">
            <span>▶</span>
        </div>

        <h1>Upload Video ke YouTube</h1>
        <p>Upload video dan lengkapi informasi sebelum dikirim ke YouTube.</p>
    </div>

    <div class="card">

        <form action="#" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="video">Pilih Video</label>

                <input
                    type="file"
                    id="video"
                    name="video"
                    accept="video/*"
                    required
                >

                <div class="file-info">
                    Format video yang didukung dapat berupa MP4, MOV, AVI, dan lainnya.
                </div>
            </div>

            <div class="form-group">
                <label for="title">Judul Video</label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Masukkan judul video"
                    required
                >
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>

                <textarea
                    id="description"
                    name="description"
                    placeholder="Masukkan deskripsi video"
                    required
                ></textarea>
            </div>

            <button type="submit" class="btn">
                Upload ke YouTube
            </button>

        </form>

    </div>

</div>

</body>
</html>