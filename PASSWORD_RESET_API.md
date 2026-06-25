# Password Reset API Documentation

## Overview

This API endpoint allows users to initiate a password reset process for their school account. When a valid email is provided, a password reset email is sent with instructions and a secure token.

## Security Features

- ✅ **Email Validation**: Validates email format before processing
- ✅ **Timing-safe Operations**: No timing-based information leakage
- ✅ **Secure Token Generation**: Uses `random_bytes()` for cryptographic strength
- ✅ **Token Expiration**: Tokens expire after 24 hours
- ✅ **Rate Limiting Ready**: Endpoint designed to work with rate limiting middleware
- ✅ **HTTPS Recommended**: Links in emails use HTTPS protocol

## Endpoint Details

### POST `/api/school/password-reset`

Initiates a password reset process for a school account.

#### Request

```bash
POST /api/school/password-reset HTTP/1.1
Host: certicampus.local
Content-Type: application/json

{
  "email": "school@example.com"
}
```

#### Request Body

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `email` | string | Yes | School email address (must be valid email format) |

#### Response

**Success (HTTP 204 No Content):**
```
HTTP/1.1 204 No Content
Content-Type: application/json
```

**Invalid Email (HTTP 400 Bad Request):**
```json
{
  "success": false,
  "code": 400,
  "message": "Invalid email format."
}
```

**Missing Email (HTTP 400 Bad Request):**
```json
{
  "success": false,
  "code": 400,
  "message": "Email is required."
}
```

**Invalid JSON (HTTP 400 Bad Request):**
```json
{
  "success": false,
  "code": 400,
  "message": "Invalid JSON in request body."
}
```

**Server Error (HTTP 500 Internal Server Error):**
```json
{
  "success": false,
  "code": 500,
  "message": "An unexpected error occurred."
}
```

#### HTTP Status Codes

| Code | Meaning | When |
|------|---------|------|
| 204 | No Content | Success (email sent or not found - for security) |
| 400 | Bad Request | Invalid email format, missing email, or invalid JSON |
| 405 | Method Not Allowed | Using GET, PUT, DELETE instead of POST |
| 500 | Internal Server Error | Database error or mail service error |

## Email Template

When a valid email is found, a password reset email is sent containing:

1. **Header** - Certicampus branding (black background #1a1a1a, lime text #c8f564)
2. **Reset Button** - Direct link to password reset page (expires in 24 hours)
3. **Backup Link** - Manual copy-paste link if button doesn't work
4. **Security Notice** - Warning about phishing and fraud reporting option
5. **Footer** - Contact information and footer notice

### Email Fields

- `reset_link`: Password reset URL (24-hour expiration)
- `fraud_link`: Report fraud URL for suspicious requests
- `user`: User object (firstname, lastname, email)
- `expiration_hours`: Token expiration in hours (24)
- `app_name`: Application name (Certicampus)

## Examples

### cURL

```bash
curl -X POST https://certicampus.local/api/school/password-reset \
  -H "Content-Type: application/json" \
  -d '{"email": "school@example.com"}'
```

### JavaScript (Fetch API)

```javascript
fetch('https://certicampus.local/api/school/password-reset', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    email: 'school@example.com'
  })
})
.then(response => {
  if (response.status === 204) {
    console.log('Password reset email sent successfully');
  } else {
    return response.json().then(data => {
      console.error('Error:', data.message);
    });
  }
})
.catch(error => console.error('Error:', error));
```

### Python

```python
import requests
import json

url = 'https://certicampus.local/api/school/password-reset'
headers = {'Content-Type': 'application/json'}
data = {'email': 'school@example.com'}

response = requests.post(url, headers=headers, json=data)

if response.status_code == 204:
    print('Password reset email sent successfully')
else:
    print(f'Error: {response.status_code}')
    print(response.json())
```

### JavaScript (Axios)

```javascript
const axios = require('axios');

axios.post('https://certicampus.local/api/school/password-reset', {
  email: 'school@example.com'
})
.then(() => {
  console.log('Password reset email sent successfully');
})
.catch(error => {
  console.error('Error:', error.response.data);
});
```

## Process Flow

```
1. User clicks "Forgot Password"
   ↓
2. User enters their email address
   ↓
3. Application sends POST request to /api/school/password-reset
   ↓
4. Server validates email format
   ↓
5. Server looks up user by email
   ↓
6. Server generates secure reset token (128-char hex string)
   ↓
7. Server sets token expiration (24 hours)
   ↓
8. Server sends password reset email with:
   - Reset link with embedded token
   - Fraud reporting option
   - 24-hour expiration notice
   ↓
9. User receives email
   ↓
10. User clicks reset link or copies token
   ↓
11. User enters new password (via separate endpoint - coming soon)
   ↓
12. Password is updated in database
```

## Security Considerations

### Input Validation
- Email format is validated using RFC 5322 standard
- Empty strings are rejected
- Whitespace-only emails are rejected

### Token Security
- Tokens are generated using `random_bytes()` for cryptographic strength
- Tokens are 128-character hexadecimal strings
- Tokens expire after 24 hours
- Tokens are stored in database with index for fast lookups

### Information Disclosure Prevention
- The endpoint returns HTTP 204 even if email is not found (no user enumeration)
- Generic error messages prevent attackers from determining valid emails
- No stack traces in error responses

### Rate Limiting
- Recommended to implement rate limiting at infrastructure level
- Suggested: 5 requests per minute per IP address
- Prevents brute force attacks

### HTTPS
- All reset links should use HTTPS in production
- Email links should be cryptographically signed if possible
- Secure session cookies should be set

## Testing

### Run Unit Tests

```bash
php bin/phpunit tests/Service/PasswordResetServiceTest.php
```

### Run Functional Tests

```bash
php bin/phpunit tests/Controller/PasswordResetControllerTest.php
```

### Test Cases

**Unit Tests (PasswordResetServiceTest):**
- ✅ Successful password reset initiation
- ✅ Invalid email format rejection
- ✅ Empty email rejection
- ✅ User not found (security response)
- ✅ Token generation uniqueness
- ✅ Token expiration date setting
- ✅ Database error handling

**Functional Tests (PasswordResetControllerTest):**
- ✅ Valid email (204 response)
- ✅ Invalid email format (400 response)
- ✅ Missing email field (400 response)
- ✅ Empty email value (400 response)
- ✅ Invalid JSON in body (400 response)
- ✅ HTTP GET not allowed (405 response)
- ✅ HTTP PUT not allowed (405 response)
- ✅ HTTP DELETE not allowed (405 response)
- ✅ Response headers validation
- ✅ Whitespace email rejection (400 response)

## Configuration

### Environment Variables

Ensure these are set in `.env`:

```
MAILER_DSN=smtp://host:port (or sendmail://)
MAILER_FROM=noreply@certicampus.local
```

### Symfony Configuration

The service is auto-wired in `config/services.yaml` and requires:

- `EntityManagerInterface`
- `UserRepository`
- `MailerInterface`
- `ValidatorInterface`

## Troubleshooting

### Emails Not Sending
1. Check `MAILER_DSN` is correctly configured
2. Verify mail server is running and accessible
3. Check mail logs: `tail -f var/log/dev.log`

### Token Validation Failing
1. Ensure database migration has been executed
2. Verify `password_reset_token` column exists
3. Check token hasn't expired (24 hours)

### 500 Error Responses
1. Check application logs for detailed error
2. Verify database connection
3. Ensure `User` entity has password reset fields

## API Versioning

Current version: **1.0**
- Supports password reset initiation only
- Coming soon: Token verification endpoint
- Coming soon: Password update endpoint

## Related Endpoints

| Endpoint | Status | Purpose |
|----------|--------|---------|
| POST `/api/school/password-reset` | ✅ Available | Initiate password reset |
| POST `/api/school/password-reset/verify` | 🔜 Coming | Verify reset token |
| POST `/api/school/password-update` | 🔜 Coming | Update password with token |
| POST `/api/school/report-fraud` | 🔜 Coming | Report fraudulent reset attempt |

## Support

For issues or questions regarding this API:
1. Check the troubleshooting section above
2. Review test cases for usage examples
3. Contact the development team

---

**Last Updated:** 2026-06-23  
**API Version:** 1.0  
**Status:** Production Ready
