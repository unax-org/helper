# WP Helper Class

The Helper class provides utilities for WordPress plugin development including logging, email functionality, form generation, encryption, and admin notices.

## Installation & Initialization

```php
use Unax\Helper\Helper;

// Initialize the helper (sets up logging, creates log directories, etc.)
Helper::init();
```

## Configuration

Configure the Helper class by setting properties before calling `config()`:

```php
// Basic configuration
Helper::set_text_domain( 'your-plugin-domain' );
Helper::set_administrator_email( 'admin@example.com' );
Helper::set_reply_to( 'no-reply@example.com' );
Helper::set_logs_dir( '/path/to/logs' );
Helper::set_log_prefix( 'your-plugin' );
Helper::set_log_threshold( 'info' ); // debug, info, warning, error
Helper::set_passphrase( 'your-encryption-key' );

// Initialize after configuration
Helper::init();
```

## Logging

### Basic Logging
```php
// Get logger instance
$logger = Helper::get_logger();

// Log with different levels
$logger->debug( 'Debug message' );
$logger->info( 'Info message' );
$logger->warning( 'Warning message' );
$logger->error( 'Error message' );

// Advanced logging with context
Helper::log( 'Context', 'Message', array( 'param1' => 'value1' ), __FILE__, __LINE__, 'error' );
```

## Email Functionality

### Send Email
```php
// Basic email
Helper::send_email( 'user@example.com', 'Subject', 'Message content' );

// Admin notification
Helper::admin_notification( 'Alert Subject', 'Alert message' );

// User notification (includes auto-generated footer)
Helper::notification( 'user@example.com', 'Subject', 'Message' );
```

## Admin Notices

### Add Admin Notices
```php
// Add dismissible admin notice
Helper::add_admin_notice( 'Success message', 'success', true );
Helper::add_admin_notice( 'Error message', 'error', false );

// Display notices (call in admin_notices hook)
add_action( 'admin_notices', array( Helper::class, 'admin_notices' ) );
```

### Frontend Notices
```php
// Add frontend notice
Helper::add_notice( 'Info message', 'info' );

// Display notices in templates
Helper::notices();

// Get notices array
$notices = Helper::get_notices();
```

## Form Generation

### Labels and Inputs
```php
// Generate label
Helper::generate_label( 'field_id', 'Field Label', 'css-class', true, false );

// Text input
Helper::generate_input_text( 'text', 'field_id', 'Label', 'css-class', 'default_value' );

// Email input
Helper::generate_input_text( 'email', 'email_field', 'Email', 'form-control' );

// Date picker
Helper::generate_input_datepicker( 'date_field', 'Select Date', 'datepicker-class' );

// Textarea
Helper::generate_textarea( 'message_field', 'Message', 'form-control', 'Default text' );
```

### Dropdowns and Radio Buttons
```php
// Dropdown select
$options = array( 'value1' => 'Label 1', 'value2' => 'Label 2' );
Helper::generate_dropdown( 'select_field', $options, 'form-select', 'value1', 'Choose option' );

// Radio buttons
Helper::generate_radio_buttons( 'radio_field', 'Radio Group', $options, 'radio-group', 'value1' );

// Checkbox
Helper::generate_checkbox( 'checkbox_field', 'Checkbox Label', 'checkbox-class', '1', '1' );
```

## Form Validation

### Field Validation
```php
// Validate different field types
$result = Helper::validate_field( 'user@example.com', 'email', true, 5, 100 );
$result = Helper::validate_field( '+1234567890', 'tel', true );
$result = Helper::validate_field( 'Text content', 'text', true, 10, 500 );

// Check validation result
if ( $result['success'] ) {
    $validated_value = $result['data'];
} else {
    $error_message = $result['message'];
}
```

### Prepare Form Fields
```php
// Convert underscores to hyphens in field keys
$fields = array( 'field_name' => 'value', 'another_field' => 'value2' );
$prepared = Helper::prepare_fields( $fields );
// Result: array( 'field-name' => 'value', 'another-field' => 'value2' )
```

## Security

### Nonce Handling
```php
// Generate nonce field
Helper::nonce_field( 'action_name', 'context', '_custom_nonce_' );

// Verify nonce
if ( Helper::verify_nonce( 'action_name', 'context', get_the_ID(), '_custom_nonce_' ) ) {
    // Process form
}
```

### Google reCAPTCHA
```php
// Verify reCAPTCHA token
$token = $_POST['g-recaptcha-response'];
$secret = 'your-recaptcha-secret-key';

if ( Helper::check_google_recaptcha( $token, $secret ) ) {
    // reCAPTCHA verified
}
```

### Encryption/Decryption
```php
// Encrypt sensitive data
$encrypted = Helper::encrypt( 'sensitive data' );

// Decrypt data
$decrypted = Helper::decrypt( $encrypted );
```

## Date Formatting

```php
// Format date to Y-m-d
$formatted = Helper::format_date( '12/31/2023' );

// Format date using WordPress settings
$wp_formatted = Helper::format_date_wp( '2023-12-31', true ); // includes time
$wp_formatted = Helper::format_date_wp( '2023-12-31', false ); // date only
```

## Configuration Properties

| Property | Default | Description |
|----------|---------|-------------|
| `$text_domain` | 'unax-helper' | Plugin text domain for translations |
| `$administrator_email` | '' | Admin email (falls back to WP admin_email) |
| `$reply_to` | 'no-reply@localhost' | Default reply-to email address |
| `$wp_mail_content_type` | 'text/html' | WordPress mail content type |
| `$passphrase` | 'passphrase' | Encryption passphrase |
| `$cipher_algo` | 'aes-256-ctr' | OpenSSL cipher algorithm |
| `$logs_dir` | '' | Custom logs directory path |
| `$log_threshold` | 'info' | Minimum log level to record |
| `$log_prefix` | 'helper' | Log file prefix |

## Error Handling

The Helper class includes comprehensive error handling:
- Logging initialization errors
- Email sending failures
- Encryption/decryption errors
- Form validation errors
- reCAPTCHA verification errors

All errors are logged using the configured logger and appropriate error messages are returned to the calling code.

