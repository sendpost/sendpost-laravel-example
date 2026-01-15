# SendPost Laravel Example

A Laravel application example demonstrating how to send emails using the SendPost API with custom mail drivers and Mailables.

## Prerequisites

- PHP 8.1+
- Composer
- Laravel 10+
- SendPost Sub-Account API Key

## Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/sendpost/sendpost-laravel-example.git
   cd sendpost-laravel-example
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment variables**
   ```bash
   cp .env.example .env
   php artisan key:generate
   # Edit .env and add your SendPost API key
   ```

4. **Start the development server**
   ```bash
   php artisan serve
   ```

5. **Test the API**
   ```bash
   curl -X POST http://localhost:8000/api/send-email \
     -H "Content-Type: application/json" \
     -d '{
       "to": "recipient@example.com",
       "subject": "Hello from Laravel",
       "htmlBody": "<h1>Hello World!</h1><p>This email was sent from Laravel.</p>"
     }'
   ```

## Project Structure

```
laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── EmailController.php
│   ├── Mail/
│   │   └── Drivers/
│   │       └── SendPostTransport.php
│   └── Providers/
│       └── AppServiceProvider.php
├── config/
│   └── sendpost.php
├── routes/
│   └── api.php
├── composer.json
└── README.md
```

## API Endpoints

### POST /api/send-email
Send an email using SendPost.

**Request Body:**
```json
{
  "to": "recipient@example.com",
  "subject": "Email Subject",
  "htmlBody": "<h1>HTML content</h1>",
  "textBody": "Plain text content (optional)"
}
```

**Response:**
```json
{
  "success": true,
  "messageId": "msg_xxx"
}
```

## Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `SENDPOST_API_KEY` | Your SendPost Sub-Account API Key | Required |
| `SENDPOST_FROM_EMAIL` | Default sender email | `hello@playwithsendpost.io` |
| `SENDPOST_FROM_NAME` | Default sender name | `SendPost` |
| `MAIL_MAILER` | Mail driver | `sendpost` |

## Using with Laravel Mail

You can use SendPost with Laravel's Mail facade:

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

Mail::to('user@example.com')->send(new WelcomeEmail($user));
```

## Documentation

- [SendPost Documentation](https://sendpost.io/docs)
- [Laravel Mail Documentation](https://laravel.com/docs/mail)
- [SendPost PHP SDK](https://github.com/sendpost/sendpost-php-sdk)

## License

MIT
