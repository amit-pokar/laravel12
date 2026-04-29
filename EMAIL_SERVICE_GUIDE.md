# Email Notification Service - Complete Setup Guide

## 📧 Overview

This email notification service supports three providers:
1. **SMTP** (Default - Gmail, Mailtrap, etc.)
2. **SendGrid** (Cloud-based email service)
3. **Mailgun** (Cloud-based email service)

---

## 🚀 Quick Start

### Test the Service

1. **Start the Laravel development server:**
   ```bash
   php artisan serve
   ```

2. **Navigate to the email form:**
   ```
   http://localhost:8000/notifications/send
   ```

3. **Test endpoints:**
   - Test Email (API): `http://localhost:8000/notifications/test`
   - Send Form: `http://localhost:8000/notifications/send`

---

## 🔧 Configuration

Update your `.env` file with the email provider of your choice:

```env
# Choose your provider: smtp, sendgrid, or mailgun
EMAIL_PROVIDER=smtp
```

---

## 📌 SMTP Configuration (Default)

### Using Gmail

```env
EMAIL_PROVIDER=smtp
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Steps:**
1. Enable 2-Step Verification on your Google Account
2. Generate App Password: https://myaccount.google.com/apppasswords
3. Use the generated password in `MAIL_PASSWORD`

### Using Mailtrap

```env
EMAIL_PROVIDER=smtp
MAIL_MAILER=smtp
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Steps:**
1. Sign up at https://mailtrap.io
2. Create a new project
3. Get credentials from SMTP Settings
4. Use them in your `.env`

---

## 🎯 SendGrid Configuration

### Account Setup

**1. Create SendGrid Account:**
   - Go to https://sendgrid.com
   - Click "Sign Up"
   - Complete registration and verify email

**2. Generate API Key:**
   - Login to SendGrid Dashboard
   - Go to **Settings > API Keys**
   - Click **"Create API Key"**
   - Name: `Laravel-App` (or any name)
   - Select **Full Access**
   - Click **Create & Verify**
   - **Copy the API Key** (you won't see it again)

**3. Verify Sender Identity:**
   - Go to **Settings > Sender Authentication**
   - Choose **Single Sender Verification** or **Domain Authentication**
   - Follow the verification steps
   - Use your verified email in `MAIL_FROM_ADDRESS`

### .env Configuration

```env
EMAIL_PROVIDER=sendgrid
SENDGRID_API_KEY=SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Test SendGrid

```bash
# In Laravel tinker
php artisan tinker

# Test sending
$service = app('App\Contracts\EmailNotificationInterface');
$service->send('test@example.com', 'Test Subject', 'Test Message');
```

---

## 📮 Mailgun Configuration

### Account Setup

**1. Create Mailgun Account:**
   - Go to https://www.mailgun.com
   - Click **Sign Up**
   - Complete registration
   - Verify email address

**2. Get API Credentials:**
   - Login to Mailgun Dashboard
   - Go to **API Security**
   - Copy **Private API Key** (starts with `key-`)
   - This is your `MAILGUN_SECRET`

**3. Get Domain:**
   - Go to **Sending > Domains**
   - You'll see a domain like `sandboxXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX.mailgun.org`
   - This is your `MAILGUN_DOMAIN`

**4. Verify Domain (Optional - for production):**
   - Click on your domain
   - Go to **Domain Information**
   - Add DNS records for verification
   - Complete verification process

### .env Configuration

```env
EMAIL_PROVIDER=mailgun
MAILGUN_SECRET=key-xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MAILGUN_DOMAIN=sandboxXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX.mailgun.org
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Example with real domain:**
```env
EMAIL_PROVIDER=mailgun
MAILGUN_SECRET=key-abc123def456ghi789jkl012
MAILGUN_DOMAIN=mail.yourdomain.com
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="My Laravel App"
```

### Test Mailgun

```bash
php artisan tinker

# Test
$service = app('App\Contracts\EmailNotificationInterface');
$service->send('test@example.com', 'Test', 'Hello from Mailgun');
```

---

## 📊 Comparison Table

| Feature | SMTP | SendGrid | Mailgun |
|---------|------|----------|---------|
| Setup Complexity | Easy | Medium | Medium |
| Cost | Free* | Free (100/day) | Free (100/month) |
| Deliverability | Depends | Excellent | Excellent |
| API | No | Yes | Yes |
| Analytics | No | Yes | Yes |
| Best For | Testing | Production | Production |

---

## 🛠️ API Usage Examples

### Simple Text Email
```php
$emailService = app('App\Contracts\EmailNotificationInterface');

$result = $emailService->send(
    'user@example.com',
    'Welcome!',
    'Welcome to our platform!'
);
```

### HTML Email
```php
$htmlContent = '<h1>Welcome</h1><p>Thanks for joining!</p>';

$result = $emailService->sendHtml(
    'user@example.com',
    'Welcome!',
    $htmlContent
);
```

### Bulk Email
```php
$recipients = [
    'user1@example.com',
    'user2@example.com',
    'user3@example.com'
];

$result = $emailService->sendBulk(
    $recipients,
    'Newsletter',
    'This is our newsletter...'
);
```

---

## 🔍 Troubleshooting

### "Failed to send email" error
- Verify credentials in `.env`
- Check `MAIL_FROM_ADDRESS` is verified in the service
- For Gmail: Ensure App Password is used, not your regular password
- For SendGrid: Verify sender email is authenticated
- For Mailgun: Check API key is correct

### Test endpoint returns error
- Navigate to `/notifications/test`
- Check Laravel logs: `storage/logs/laravel.log`
- Verify `EMAIL_PROVIDER` value in `.env`

### Emails not received
- Check spam folder
- Verify sender domain authentication
- Check email service dashboard for bounces/failures

---

## 📝 Routes Available

| Route | Method | Description |
|-------|--------|-------------|
| `/notifications/test` | GET | Quick test email |
| `/notifications/send` | GET | Show email form |
| `/notifications/send` | POST | Send simple email |
| `/notifications/send-html` | POST | Send HTML email |
| `/notifications/send-bulk` | POST | Send bulk emails |

---

## ✅ Checklist for Production

- [ ] Choose email provider (SendGrid/Mailgun recommended)
- [ ] Create account and get API credentials
- [ ] Verify sender domain/email
- [ ] Update `.env` with correct credentials
- [ ] Test with actual email (not test email)
- [ ] Configure error handling and logging
- [ ] Set up monitoring/alerts for failures
- [ ] Implement email templates (optional)
- [ ] Add rate limiting if needed

---

**Questions?** Check the `.env` file for all available configuration options!
