<?php
/**
 * Tests for Helper class
 *
 * @package UnaxHelper
 */

namespace Unax\Helper\Tests;

use PHPUnit\Framework\TestCase;
use Unax\Helper\Helper;
use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery;

/**
 * Helper test case.
 */
class HelperTest extends TestCase {

	/**
	 * Setup before each test
	 */
	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();

		// Reset static properties
		Helper::$text_domain = 'unax-helper';
		Helper::$administrator_email = '';
		Helper::$reply_to = 'no-reply@localhost';
		Helper::$wp_mail_content_type = 'text/html';
		Helper::$passphrase = 'passphrase';
		Helper::$cipher_algo = 'aes-256-ctr';
		Helper::$logs_dir = '';
		Helper::$log_threshold = 'info';
		Helper::$log_prefix = '';
		Helper::$logger = null;
	}

	/**
	 * Teardown after each test
	 */
	protected function tearDown(): void {
		Monkey\tearDown();
		Mockery::close();
		parent::tearDown();
	}

	/**
	 * Test get_text_domain
	 */
	public function test_get_text_domain() {
		$this->assertEquals( 'unax-helper', Helper::get_text_domain() );
	}

	/**
	 * Test set_text_domain
	 */
	public function test_set_text_domain() {
		Helper::set_text_domain( 'custom-domain' );
		$this->assertEquals( 'custom-domain', Helper::get_text_domain() );
	}

	/**
	 * Test get_administrator_email with empty value
	 */
	public function test_get_administrator_email_empty() {
		Functions\expect( 'get_option' )
			->once()
			->with( 'admin_email' )
			->andReturn( 'admin@example.com' );

		$this->assertEquals( 'admin@example.com', Helper::get_administrator_email() );
	}

	/**
	 * Test get_administrator_email with set value
	 */
	public function test_get_administrator_email_set() {
		Helper::set_administrator_email( 'custom@example.com' );
		$this->assertEquals( 'custom@example.com', Helper::get_administrator_email() );
	}

	/**
	 * Test set_administrator_email
	 */
	public function test_set_administrator_email() {
		Helper::set_administrator_email( 'test@example.com' );
		$this->assertEquals( 'test@example.com', Helper::$administrator_email );
	}

	/**
	 * Test get_reply_to with default value
	 */
	public function test_get_reply_to_default() {
		Helper::$reply_to = 'no-reply@localhost';
		$this->assertEquals( 'no-reply@localhost', Helper::get_reply_to() );
	}

	/**
	 * Test get_reply_to with empty value
	 */
	public function test_get_reply_to_empty() {
		Helper::$reply_to = '';
		$_SERVER['HTTP_HOST'] = 'example.com';

		Functions\expect( 'sanitize_text_field' )
			->once()
			->andReturnUsing( function( $value ) {
				return $value;
			});

		Functions\expect( 'wp_unslash' )
			->once()
			->andReturnUsing( function( $value ) {
				return $value;
			});

		$this->assertEquals( 'no-reply@example.com', Helper::get_reply_to() );
	}

	/**
	 * Test set_reply_to
	 */
	public function test_set_reply_to() {
		Helper::set_reply_to( 'noreply@example.com' );
		$this->assertEquals( 'noreply@example.com', Helper::get_reply_to() );
	}

	/**
	 * Test get_wp_mail_content_type
	 */
	public function test_get_wp_mail_content_type() {
		$this->assertEquals( 'text/html', Helper::get_wp_mail_content_type() );
	}

	/**
	 * Test set_wp_mail_content_type
	 */
	public function test_set_wp_mail_content_type() {
		Helper::set_wp_mail_content_type( 'text/plain' );
		$this->assertEquals( 'text/plain', Helper::get_wp_mail_content_type() );
	}

	/**
	 * Test get_passphrase
	 */
	public function test_get_passphrase() {
		$this->assertEquals( 'passphrase', Helper::get_passphrase() );
	}

	/**
	 * Test set_passphrase
	 */
	public function test_set_passphrase() {
		Helper::set_passphrase( 'new-passphrase' );
		$this->assertEquals( 'new-passphrase', Helper::get_passphrase() );
	}

	/**
	 * Test get_cipher_algo
	 */
	public function test_get_cipher_algo() {
		$this->assertEquals( 'aes-256-ctr', Helper::get_cipher_algo() );
	}

	/**
	 * Test set_cipher_algo
	 */
	public function test_set_cipher_algo() {
		Helper::set_cipher_algo( 'aes-128-cbc' );
		$this->assertEquals( 'aes-128-cbc', Helper::get_cipher_algo() );
	}

	/**
	 * Test get_log_threshold
	 */
	public function test_get_log_threshold() {
		$this->assertEquals( 'info', Helper::get_log_threshold() );
	}

	/**
	 * Test set_log_threshold
	 */
	public function test_set_log_threshold() {
		Helper::set_log_threshold( 'debug' );
		$this->assertEquals( 'debug', Helper::get_log_threshold() );
	}

	/**
	 * Test get_logs_dir with empty value
	 */
	public function test_get_logs_dir_empty() {
		Functions\expect( 'plugin_dir_path' )
			->never();

		Functions\expect( 'untrailingslashit' )
			->never();

		$expected = dirname( dirname( __DIR__ ), 1 ) . '/logs';
		$this->assertEquals( $expected, Helper::get_logs_dir() );
	}

	/**
	 * Test get_logs_dir with set value
	 */
	public function test_get_logs_dir_set() {
		Helper::set_logs_dir( '/custom/logs' );
		$this->assertEquals( '/custom/logs', Helper::get_logs_dir() );
	}

	/**
	 * Test set_logs_dir
	 */
	public function test_set_logs_dir() {
		Helper::set_logs_dir( '/var/log/custom' );
		$this->assertEquals( '/var/log/custom', Helper::$logs_dir );
	}

	/**
	 * Test get_log_prefix with empty value
	 */
	public function test_get_log_prefix_empty() {
		$this->assertEquals( 'helper', Helper::get_log_prefix() );
	}

	/**
	 * Test get_log_prefix with set value
	 */
	public function test_get_log_prefix_set() {
		Helper::set_log_prefix( 'custom-prefix' );
		$this->assertEquals( 'custom-prefix', Helper::get_log_prefix() );
	}

	/**
	 * Test set_log_prefix
	 */
	public function test_set_log_prefix() {
		Helper::set_log_prefix( 'test-prefix' );
		$this->assertEquals( 'test-prefix', Helper::$log_prefix );
	}

	/**
	 * Test add_admin_notice
	 */
	public function test_add_admin_notice() {
		Functions\expect( 'get_option' )
			->once()
			->with( 'unax-admin-notices', array() )
			->andReturn( array() );

		Functions\expect( 'update_option' )
			->once()
			->with( 'unax-admin-notices', Mockery::type( 'array' ), false )
			->andReturn( true );

		Helper::add_admin_notice( 'Test message', 'success', true );
		$this->assertTrue( true );
	}

	/**
	 * Test add_notice
	 */
	public function test_add_notice() {
		Functions\expect( 'get_option' )
			->once()
			->with( 'unax-notices', array() )
			->andReturn( array() );

		Functions\expect( 'update_option' )
			->once()
			->with( 'unax-notices', Mockery::type( 'array' ), false )
			->andReturn( true );

		Helper::add_notice( 'Test notice', 'info' );
		$this->assertTrue( true );
	}

	/**
	 * Test get_notices with empty notices
	 */
	public function test_get_notices_empty() {
		Functions\expect( 'get_option' )
			->once()
			->with( 'unax-notices', array() )
			->andReturn( array() );

		Functions\expect( 'delete_option' )
			->once()
			->with( 'unax-notices' )
			->andReturn( true );

		$notices = Helper::get_notices();
		$this->assertIsArray( $notices );
		$this->assertEmpty( $notices );
	}

	/**
	 * Test get_notices with notices
	 */
	public function test_get_notices_with_data() {
		$expected_notices = array(
			array( 'text' => 'Test', 'type' => 'info' ),
		);

		Functions\expect( 'get_option' )
			->once()
			->with( 'unax-notices', array() )
			->andReturn( $expected_notices );

		Functions\expect( 'delete_option' )
			->once()
			->with( 'unax-notices' )
			->andReturn( true );

		$notices = Helper::get_notices();
		$this->assertEquals( $expected_notices, $notices );
	}

	/**
	 * Test format_log without file and line
	 */
	public function test_format_log_simple() {
		$result = Helper::format_log( 'Test message' );
		$this->assertEquals( 'Test message', $result );
	}

	/**
	 * Test format_log with file and line
	 */
	public function test_format_log_with_file_line() {
		$result = Helper::format_log( 'Test message', '/path/to/file.php', '123' );
		$this->assertEquals( 'file.php:123 Test message', $result );
	}

	/**
	 * Test format_date with empty date
	 */
	public function test_format_date_empty() {
		$result = Helper::format_date( '' );
		$this->assertEquals( '', $result );
	}

	/**
	 * Test format_date with valid date
	 */
	public function test_format_date_valid() {
		Functions\expect( 'get_option' )
			->once()
			->with( 'date_format' )
			->andReturn( 'Y-m-d' );

		$result = Helper::format_date( '2024-01-15' );
		$this->assertEquals( '2024-01-15', $result );
	}

	/**
	 * Test format_date_wp with empty date
	 */
	public function test_format_date_wp_empty() {
		$result = Helper::format_date_wp( '' );
		$this->assertEquals( '', $result );
	}

	/**
	 * Test format_date_wp without time
	 */
	public function test_format_date_wp_without_time() {
		Functions\expect( 'get_option' )
			->once()
			->with( 'date_format' )
			->andReturn( 'Y-m-d' );

		$result = Helper::format_date_wp( '2024-01-15' );
		$this->assertEquals( '2024-01-15', $result );
	}

	/**
	 * Test format_date_wp with time
	 */
	public function test_format_date_wp_with_time() {
		Functions\expect( 'get_option' )
			->once()
			->with( 'date_format' )
			->andReturn( 'Y-m-d' );

		Functions\expect( 'get_option' )
			->once()
			->with( 'time_format' )
			->andReturn( 'H:i:s' );

		$result = Helper::format_date_wp( '2024-01-15 10:30:00', true );
		$this->assertEquals( '2024-01-15 10:30:00', $result );
	}

	/**
	 * Test nonce_field
	 */
	public function test_nonce_field() {
		Functions\expect( 'wp_nonce_field' )
			->once()
			->with( 'test_nonce', '_nonce_test_nonce' )
			->andReturn( true );

		Helper::nonce_field( 'test_nonce' );
		$this->assertTrue( true );
	}

	/**
	 * Test nonce_field with custom context and prefix
	 */
	public function test_nonce_field_custom() {
		Functions\expect( 'wp_nonce_field' )
			->once()
			->with( 'custom_context', 'custom_prefix_test' )
			->andReturn( true );

		Helper::nonce_field( 'test', 'custom_context', 'custom_prefix_' );
		$this->assertTrue( true );
	}

	/**
	 * Test verify_nonce with missing nonce
	 */
	public function test_verify_nonce_missing() {
		$_REQUEST = array();

		$logger = Mockery::mock( 'Unax\Logger\Logger' );
		$logger->shouldReceive( 'error' )->once();
		Helper::set_logger( $logger );

		$result = Helper::verify_nonce( 'test_nonce', null, 0, null );
		$this->assertFalse( $result );
	}

	/**
	 * Test verify_nonce with invalid nonce
	 */
	public function test_verify_nonce_invalid() {
		$_REQUEST = array( '_nonce_test_nonce' => 'invalid_nonce' );

		Functions\expect( 'sanitize_text_field' )
			->once()
			->andReturn( 'invalid_nonce' );

		Functions\expect( 'wp_unslash' )
			->once()
			->andReturnUsing( function( $value ) {
				return $value;
			});

		Functions\expect( 'wp_verify_nonce' )
			->once()
			->with( 'invalid_nonce', 'test_nonce' )
			->andReturn( false );

		$logger = Mockery::mock( 'Unax\Logger\Logger' );
		$logger->shouldReceive( 'error' )->once();
		Helper::set_logger( $logger );

		$result = Helper::verify_nonce( 'test_nonce' );
		$this->assertFalse( $result );
	}

	/**
	 * Test verify_nonce with valid nonce
	 */
	public function test_verify_nonce_valid() {
		$_REQUEST = array( '_nonce_test_nonce' => 'valid_nonce' );

		Functions\expect( 'sanitize_text_field' )
			->once()
			->andReturn( 'valid_nonce' );

		Functions\expect( 'wp_unslash' )
			->once()
			->andReturnUsing( function( $value ) {
				return $value;
			});

		Functions\expect( 'wp_verify_nonce' )
			->once()
			->with( 'valid_nonce', 'test_nonce' )
			->andReturn( 1 );

		$result = Helper::verify_nonce( 'test_nonce' );
		$this->assertTrue( $result );
	}

	/**
	 * Test prepare_fields
	 */
	public function test_prepare_fields() {
		$fields = array(
			'first_name' => 'John',
			'last_name' => 'Doe',
			'email_address' => 'john@example.com',
		);

		$result = Helper::prepare_fields( $fields );

		$this->assertArrayHasKey( 'first-name', $result );
		$this->assertArrayHasKey( 'last-name', $result );
		$this->assertArrayHasKey( 'email-address', $result );
		$this->assertEquals( 'John', $result['first-name'] );
	}

	/**
	 * Test validate_field with empty required field
	 */
	public function test_validate_field_empty_required() {
		Functions\expect( 'esc_html__' )
			->once()
			->andReturn( 'Mandatory field' );

		$result = Helper::validate_field( '', 'text', true );

		$this->assertFalse( $result['success'] );
		$this->assertEquals( 400, $result['code'] );
		$this->assertEquals( 'Mandatory field', $result['message'] );
	}

	/**
	 * Test validate_field with valid text
	 */
	public function test_validate_field_valid_text() {
		$result = Helper::validate_field( 'Test value', 'text', true );

		$this->assertTrue( $result['success'] );
		$this->assertEquals( 200, $result['code'] );
		$this->assertEquals( 'Test value', $result['data'] );
	}

	/**
	 * Test validate_field with invalid email
	 */
	public function test_validate_field_invalid_email() {
		Functions\expect( 'esc_html__' )
			->once()
			->andReturn( 'Invalid email address' );

		$result = Helper::validate_field( 'invalid-email', 'email', true );

		$this->assertFalse( $result['success'] );
		$this->assertEquals( 'Invalid email address', $result['message'] );
	}

	/**
	 * Test validate_field with valid email
	 */
	public function test_validate_field_valid_email() {
		$result = Helper::validate_field( 'test@example.com', 'email', true );

		$this->assertTrue( $result['success'] );
		$this->assertEquals( 'test@example.com', $result['data'] );
	}

	/**
	 * Test validate_field with invalid phone
	 */
	public function test_validate_field_invalid_phone() {
		Functions\expect( 'esc_html__' )
			->once()
			->andReturn( 'Invalid phone number' );

		$result = Helper::validate_field( '123-abc-456', 'tel', true );

		$this->assertFalse( $result['success'] );
		$this->assertEquals( 'Invalid phone number', $result['message'] );
	}

	/**
	 * Test validate_field with valid phone
	 */
	public function test_validate_field_valid_phone() {
		$result = Helper::validate_field( '+1 234 567 8900', 'tel', true );

		$this->assertTrue( $result['success'] );
	}

	/**
	 * Test validate_field with min length violation
	 */
	public function test_validate_field_min_length() {
		Functions\expect( 'esc_html__' )
			->once()
			->andReturn( 'Field length minimum %s.' );

		$result = Helper::validate_field( 'ab', 'text', true, 5 );

		$this->assertFalse( $result['success'] );
	}

	/**
	 * Test validate_field with max length violation
	 */
	public function test_validate_field_max_length() {
		Functions\expect( 'esc_html__' )
			->once()
			->andReturn( 'Field length maximum %s.' );

		$result = Helper::validate_field( 'abcdefghij', 'text', true, null, 5 );

		$this->assertFalse( $result['success'] );
	}

	/**
	 * Test encrypt with empty data
	 */
	public function test_encrypt_empty() {
		$result = Helper::encrypt( '' );
		$this->assertFalse( $result );
	}

	/**
	 * Test encrypt with valid data
	 */
	public function test_encrypt_valid() {
		// Set passphrase with at least 16 characters for AES-256-CTR
		Helper::set_passphrase( 'test-passphrase-key-1234567890' );

		$data = 'test data';
		$result = Helper::encrypt( $data );

		// Should return encrypted string
		$this->assertIsString( $result );
		$this->assertNotEquals( $data, $result );
	}

	/**
	 * Test decrypt with empty data
	 */
	public function test_decrypt_empty() {
		$result = Helper::decrypt( '' );
		$this->assertFalse( $result );
	}

	/**
	 * Test encrypt and decrypt
	 */
	public function test_encrypt_decrypt_roundtrip() {
		// Set passphrase with at least 16 characters for AES-256-CTR
		Helper::set_passphrase( 'test-passphrase-key-1234567890' );

		$original = 'sensitive data';
		$encrypted = Helper::encrypt( $original );
		$decrypted = Helper::decrypt( $encrypted );

		$this->assertEquals( $original, $decrypted );
	}

	/**
	 * Test send_email
	 */
	public function test_send_email() {
		Functions\expect( 'apply_filters' )
			->times( 3 )
			->andReturnUsing( function( $filter, $value ) {
				return $value;
			});

		Functions\expect( 'get_bloginfo' )
			->once()
			->with( 'site_title' )
			->andReturn( 'Test Site' );

		Functions\expect( 'wp_mail' )
			->once()
			->with( 'test@example.com', 'Test Subject', Mockery::type( 'string' ), Mockery::type( 'array' ) )
			->andReturn( true );

		$logger = Mockery::mock( 'Unax\Logger\Logger' );
		Helper::set_logger( $logger );

		$result = Helper::send_email( 'test@example.com', 'Test Subject', 'Test message' );
		$this->assertTrue( $result );
	}

	/**
	 * Test admin_notification with default email
	 */
	public function test_admin_notification() {
		Functions\expect( 'get_option' )
			->once()
			->with( 'admin_email' )
			->andReturn( 'admin@example.com' );

		Functions\expect( 'apply_filters' )
			->times( 3 )
			->andReturnUsing( function( $filter, $value ) {
				return $value;
			});

		Functions\expect( 'get_bloginfo' )
			->once()
			->with( 'site_title' )
			->andReturn( 'Test Site' );

		Functions\expect( 'wp_mail' )
			->once()
			->andReturn( true );

		$logger = Mockery::mock( 'Unax\Logger\Logger' );
		Helper::set_logger( $logger );

		$result = Helper::admin_notification( 'Test Subject', 'Test message' );
		$this->assertTrue( $result );
	}

	/**
	 * Test notification
	 */
	public function test_notification() {
		Functions\expect( 'esc_html__' )
			->once()
			->andReturn( 'This is an autogenerated message. Please do not reply.' );

		Functions\expect( 'apply_filters' )
			->times( 3 )
			->andReturnUsing( function( $filter, $value ) {
				return $value;
			});

		Functions\expect( 'get_bloginfo' )
			->once()
			->with( 'site_title' )
			->andReturn( 'Test Site' );

		Functions\expect( 'wp_mail' )
			->once()
			->andReturn( true );

		$logger = Mockery::mock( 'Unax\Logger\Logger' );
		Helper::set_logger( $logger );

		$result = Helper::notification( 'user@example.com', 'Subject', 'Message' );
		$this->assertTrue( $result );
	}

	/**
	 * Test check_google_recaptcha with empty token
	 */
	public function test_check_google_recaptcha_empty_token() {
		Functions\expect( 'esc_html__' )
			->once()
			->andReturn( 'Unauthorized' );

		Functions\expect( 'wp_send_json_error' )
			->once()
			->with( 'Unauthorized', 401 );

		$result = Helper::check_google_recaptcha( '', 'secret_key' );
		$this->assertFalse( $result );
	}

	/**
	 * Test check_google_recaptcha with valid token
	 */
	public function test_check_google_recaptcha_valid() {
		$response_body = json_encode( array(
			'success' => true,
			'score' => 0.9,
		) );

		Functions\expect( 'wp_remote_get' )
			->once()
			->andReturn( array( 'body' => $response_body ) );

		$result = Helper::check_google_recaptcha( 'valid_token', 'secret_key' );
		$this->assertTrue( $result );
	}

	/**
	 * Test check_google_recaptcha with low score
	 */
	public function test_check_google_recaptcha_low_score() {
		$response_body = json_encode( array(
			'success' => true,
			'score' => 0.3,
		) );

		Functions\expect( 'wp_remote_get' )
			->once()
			->andReturn( array( 'body' => $response_body ) );

		Functions\expect( 'wp_send_json_error' )
			->once();

		$result = Helper::check_google_recaptcha( 'valid_token', 'secret_key' );
		$this->assertFalse( $result );
	}

	/**
	 * Test generate_label
	 */
	public function test_generate_label() {
		Functions\when( 'esc_attr' )->returnArg();
		Functions\when( 'esc_html' )->returnArg();

		ob_start();
		Helper::generate_label( 'test_id', 'Test Label', 'custom-class', true, false );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'for="test_id"', $output );
		$this->assertStringContainsString( 'Test Label', $output );
		$this->assertStringContainsString( 'custom-class', $output );
		$this->assertStringContainsString( '<br>', $output );
	}

	/**
	 * Test generate_input_text
	 */
	public function test_generate_input_text() {
		Functions\expect( 'get_query_var' )
			->once()
			->with( 'test_id', '' )
			->andReturn( '' );

		Functions\when( 'esc_attr' )->returnArg();

		ob_start();
		Helper::generate_input_text( 'text', 'test_id', 'Test Label', 'form-control', null, true );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'type="text"', $output );
		$this->assertStringContainsString( 'name="test_id"', $output );
		$this->assertStringContainsString( 'id="test_id"', $output );
		$this->assertStringContainsString( 'class="form-control"', $output );
	}

	/**
	 * Test generate_input_datepicker
	 */
	public function test_generate_input_datepicker() {
		Functions\expect( 'get_query_var' )
			->once()
			->with( 'date_field', '' )
			->andReturn( '' );

		Functions\when( 'esc_attr' )->returnArg();

		ob_start();
		Helper::generate_input_datepicker( 'date_field', 'Select Date', 'datepicker-class' );
		$output = ob_get_clean();

		$this->assertStringContainsString( 'type="date"', $output );
		$this->assertStringContainsString( 'class="datepicker', $output );
	}

	/**
	 * Test generate_textarea
	 */
	public function test_generate_textarea() {
		Functions\expect( 'get_query_var' )
			->once()
			->with( 'textarea_id', '' )
			->andReturn( '' );

		Functions\when( 'esc_attr' )->returnArg();

		ob_start();
		Helper::generate_textarea( 'textarea_id', 'Enter text', 'textarea-class', null, true );
		$output = ob_get_clean();

		$this->assertStringContainsString( '<textarea', $output );
		$this->assertStringContainsString( 'name="textarea_id"', $output );
		$this->assertStringContainsString( 'class="textarea-class"', $output );
	}

	/**
	 * Test generate_dropdown
	 */
	public function test_generate_dropdown() {
		Functions\expect( 'get_query_var' )
			->once()
			->with( 'dropdown_id', '' )
			->andReturn( '' );

		Functions\expect( 'esc_html__' )
			->once()
			->andReturn( 'Choose' );

		Functions\when( 'esc_attr' )->returnArg();
		Functions\when( 'esc_html' )->returnArg();

		$options = array(
			'option1' => 'Option 1',
			'option2' => 'Option 2',
		);

		ob_start();
		Helper::generate_dropdown( 'dropdown_id', $options, 'select-class', '', '' );
		$output = ob_get_clean();

		$this->assertStringContainsString( '<select', $output );
		$this->assertStringContainsString( 'name="dropdown_id"', $output );
		$this->assertStringContainsString( 'Option 1', $output );
		$this->assertStringContainsString( 'Option 2', $output );
	}

	/**
	 * Test generate_radio_buttons
	 */
	public function test_generate_radio_buttons() {
		Functions\expect( 'get_query_var' )
			->once()
			->with( 'radio_id', '' )
			->andReturn( '' );

		Functions\when( 'esc_attr' )->returnArg();
		Functions\when( 'esc_html' )->returnArg();

		$options = array(
			'option1' => 'Option 1',
			'option2' => 'Option 2',
		);

		ob_start();
		Helper::generate_radio_buttons( 'radio_id', 'Select Option', $options, '', '' );
		$output = ob_get_clean();

		$this->assertStringContainsString( '<fieldset>', $output );
		$this->assertStringContainsString( 'type="radio"', $output );
		$this->assertStringContainsString( 'Option 1', $output );
		$this->assertStringContainsString( 'Option 2', $output );
	}

	/**
	 * Test generate_checkbox
	 */
	public function test_generate_checkbox() {
		Functions\expect( 'get_query_var' )
			->once()
			->with( 'checkbox_id', '' )
			->andReturn( '' );

		Functions\when( 'esc_attr' )->returnArg();
		Functions\when( 'esc_html' )->returnArg();

		ob_start();
		Helper::generate_checkbox( 'checkbox_id', 'Check me', 'checkbox-class', null, '' );
		$output = ob_get_clean();

		$this->assertStringContainsString( '<fieldset', $output );
		$this->assertStringContainsString( 'type="checkbox"', $output );
		$this->assertStringContainsString( 'Check me', $output );
	}
}
