<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #2196F3;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .blog-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #2196F3;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="header">
                <h2>Blog Post Updated ✏️</h2>
            </div>

            <p>Hello,</p>

            <p>A blog post has been updated. Here are the details:</p>

            <div class="blog-info">
                <strong>Blog Title:</strong> {{ $blog->name }}<br>
                <strong>Slug:</strong> {{ $blog->slug }}<br>
                <strong>Status:</strong> {{ $blog->status }}<br>
                <strong>Last Updated:</strong> {{ $blog->updated_at->format('M d, Y H:i A') }}
            </div>

            <div>
                <strong>Content Preview:</strong><br>
                {!! Illuminate\Support\Str::limit(strip_tags($blog->content), 500, '...') !!}
            </div>

            <div class="footer">
                <p>This is an automated notification from our Blog Management System.</p>
            </div>
        </div>
    </div>
</body>
</html>
