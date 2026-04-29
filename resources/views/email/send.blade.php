<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .current-provider {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 12px;
            margin-bottom: 30px;
            border-radius: 4px;
            color: #2e7d32;
            font-size: 14px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            margin-top: 15px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        input[type="email"],
        input[type="text"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        input[type="email"]:focus,
        input[type="text"]:focus,
        input[type="file"]:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        button {
            flex: 1;
            min-width: 150px;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-test {
            background: #4caf50;
            color: white;
        }
        .btn-test:hover {
            background: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(76, 175, 80, 0.4);
        }
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
        }
        .tab-btn {
            padding: 10px 0;
            background: none;
            border: none;
            color: #999;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
            min-width: auto;
            flex: auto;
        }
        .tab-btn.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .info-box {
            background: #f0f7ff;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.6;
            color: #1565c0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email Notification Service</h1>
        
        <div class="current-provider">
            <strong>Current Provider:</strong> {{ strtoupper(env('EMAIL_PROVIDER', 'SMTP')) }}
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-error">
                {{ $message }}
            </div>
        @endif

        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('simple')">Simple Email</button>
            <button class="tab-btn" onclick="switchTab('html')">HTML Email</button>
            <button class="tab-btn" onclick="switchTab('bulk')">Bulk Email</button>
        </div>

        <!-- Simple Email Tab -->
        <div id="simple" class="tab-content active">
            <form action="{{ route('notification.send') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="to">Recipient Email *</label>
                    <input type="email" id="to" name="to" placeholder="user@example.com" value="{{ old('to') }}" required>
                    @error('to') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <input type="text" id="subject" name="subject" placeholder="Email subject" value="{{ old('subject') }}" required>
                    @error('subject') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" placeholder="Enter your email message here..." required>{{ old('message') }}</textarea>
                    @error('message') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="attachments">Attachments (Optional)</label>
                    <input type="file" id="attachments" name="attachments[]" multiple accept="*/*" onchange="updateFileList(this, 'attachments-list')">
                    <div id="attachments-list" style="margin-top: 10px; font-size: 13px; color: #666;"></div>
                    @error('attachments') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                    @error('attachments.*') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-primary">Send Email</button>
                    <button type="button" class="btn-test" onclick="testEmail()">Test Email</button>
                </div>
            </form>
        </div>

        <!-- HTML Email Tab -->
        <div id="html" class="tab-content">
            <form action="{{ route('notification.send-html') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="to_html">Recipient Email *</label>
                    <input type="email" id="to_html" name="to" placeholder="user@example.com" value="{{ old('to') }}" required>
                    @error('to') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="subject_html">Subject *</label>
                    <input type="text" id="subject_html" name="subject" placeholder="Email subject" value="{{ old('subject') }}" required>
                    @error('subject') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="html_content">HTML Content *</label>
                    <textarea id="html_content" name="html_content" placeholder="Enter your HTML content here..." required>{{ old('html_content') }}</textarea>
                    @error('html_content') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="attachments_html">Attachments (Optional)</label>
                    <input type="file" id="attachments_html" name="attachments[]" multiple accept="*/*" onchange="updateFileList(this, 'attachments-html-list')">
                    <div id="attachments-html-list" style="margin-top: 10px; font-size: 13px; color: #666;"></div>
                    @error('attachments') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                    @error('attachments.*') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="info-box">
                    <strong>Example HTML:</strong><br>
                    &lt;h1&gt;Hello&lt;/h1&gt;<br>
                    &lt;p&gt;This is an HTML email.&lt;/p&gt;
                </div>

                <div class="button-group" style="margin-top: 20px;">
                    <button type="submit" class="btn-primary">Send HTML Email</button>
                </div>
            </form>
        </div>

        <!-- Bulk Email Tab -->
        <div id="bulk" class="tab-content">
            <form action="{{ route('notification.send-bulk') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="recipients">Recipients (comma-separated) *</label>
                    <textarea id="recipients" name="recipients" placeholder="user1@example.com, user2@example.com, user3@example.com" required>{{ old('recipients') }}</textarea>
                    @error('recipients') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="subject_bulk">Subject *</label>
                    <input type="text" id="subject_bulk" name="subject" placeholder="Email subject" value="{{ old('subject') }}" required>
                    @error('subject') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="message_bulk">Message *</label>
                    <textarea id="message_bulk" name="message" placeholder="Enter your email message here..." required>{{ old('message') }}</textarea>
                    @error('message') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="attachments_bulk">Attachments (Optional)</label>
                    <input type="file" id="attachments_bulk" name="attachments[]" multiple accept="*/*" onchange="updateFileList(this, 'attachments-bulk-list')">
                    <div id="attachments-bulk-list" style="margin-top: 10px; font-size: 13px; color: #666;"></div>
                    @error('attachments') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                    @error('attachments.*') <span style="color: #d32f2f;">{{ $message }}</span> @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-primary">Send Bulk Email</button>
                </div>
            </form>
        </div>

        <div class="info-box" style="margin-top: 30px;">
            <strong>ℹ️ Configuration Details:</strong><br><br>
            <strong>To change email provider, update .env:</strong><br>
            EMAIL_PROVIDER=smtp (or sendgrid, mailgun)<br><br>
            <strong>For SendGrid:</strong><br>
            SENDGRID_API_KEY=your_api_key<br><br>
            <strong>For Mailgun:</strong><br>
            MAILGUN_SECRET=your_secret_key<br>
            MAILGUN_DOMAIN=your_domain.mailgun.org
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

            // Show selected tab
            document.getElementById(tab).classList.add('active');
            event.target.classList.add('active');
        }

        function updateFileList(input, listId) {
            const listDiv = document.getElementById(listId);
            const files = input.files;
            
            if (files.length === 0) {
                listDiv.innerHTML = '';
                return;
            }
            
            let html = '<strong>Selected files:</strong><ul style="margin: 8px 0; padding-left: 20px;">';
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const size = (file.size / 1024).toFixed(2); // Convert to KB
                html += `<li>${file.name} (${size} KB)</li>`;
            }
            html += '</ul>';
            listDiv.innerHTML = html;
        }

        function testEmail() {
            const testBtn = event.target;
            testBtn.disabled = true;
            testBtn.textContent = 'Sending...';

            fetch('{{ route("notification.test") }}')
                .then(response => response.json())
                .then(data => {
                    alert(`Status: ${data.status.toUpperCase()}\n${data.message}\n\nProvider: ${data.provider}\nRecipient: ${data.recipient}`);
                    testBtn.disabled = false;
                    testBtn.textContent = 'Test Email';
                })
                .catch(error => {
                    alert('Error sending test email: ' + error);
                    testBtn.disabled = false;
                    testBtn.textContent = 'Test Email';
                });
        }
    </script>
</body>
</html>
