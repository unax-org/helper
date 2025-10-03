<?php
/**
 * Helper
 *
 * @package UnaxHelper
 */

namespace Unax\Helper;

use Unax\Logger\Logger;
use Unax\Logger\LogHandlerFile;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper class.
 */
class Helper {
	/**
	 * Text domain.
	 *
	 * @var string
	 */
	public static $text_domain = 'unax-helper';


	/**
	 * Administrator address.
	 *
	 * @var string
	 */
	public static $administrator_email = '';

	/**
	 * No reply address.
	 *
	 * @var string
	 */
	public static $reply_to = 'no-reply@localhost';

	/**
	 * WP Mail content type.
	 *
	 * @var string
	 */
	public static $wp_mail_content_type = 'text/html';

	/**
	 * Encryption passphrase.
	 *
	 * @var string
	 */
	public static $passphrase = 'passphrase';

	/**
	 * The cipher method for openssl_encrypt/openssl_decrypt.
	 *
	 * @var string
	 */
	public static $cipher_algo = 'aes-256-ctr';

	/**
	 * Logs directory.
	 *
	 * @var string
	 */
	public static $logs_dir = '';

	/**
	 * Log threshold.
	 *
	 * @var string
	 */	
	public static $log_threshold = 'info';

	/**
	 * Logs prefix.
	 *
	 * @var string
	 */
	public static $log_prefix = '';

	/**
	 * Logger.
	 *
	 * @var \Unax\Logger\Logger|null
	 */
	public static $logger = null;


	/**
	 * Helper config.
	 *
	 * @return void
	 */
	public static function config(): void {
		try {
			$logs_dir = self::get_logs_dir();
			if ( ! is_dir( $logs_dir ) ) {
				if ( ! wp_mkdir_p( $logs_dir ) ) {
					throw new \Exception( sprintf( 'Create logs dir "%s" failed.', $logs_dir ) );
				}
			}

			// Set log file name.
			$log_file_name =  sprintf( 
				'%s/%s-%s-%s.log', 
				$logs_dir, 
				self::get_log_prefix(), 
				date( 'Y-m-d' ), 
				hash( 'crc32', date( 'Y-m-d' ) ) 
			);

			if ( ! class_exists('Unax\Logger\LogHandlerFile' ) ) {
				throw new \Exception( 'LogHandlerFile class not found.' );
			}
			LogHandlerFile::setLogHandlerFile( $log_file_name );

			if ( ! class_exists('Unax\Logger\Logger' ) ) {
				throw new \Exception( 'Logger class not found.' );
			}

			// Set log threshold.
			Logger::setLogThreshold( self::get_log_threshold() );

			// Set WP emails content type.
			add_filter( 'wp_mail_content_type', array( self::class, 'get_wp_mail_content_type' ) );
		} catch ( \Exception $e ) {
			error_log( $e->getMessage(), 0 );
		}
	}


	/**
	 * Get text domain.
	 *
	 * @return string
	 */
	public static function get_text_domain(): string {
		return self::$text_domain;
	}


	/**
	 * Set text domain.
	 *
	 * @param string $domain Text domain.
	 */
	public static function set_text_domain( string $text_domain ): void {
		self::$text_domain = $text_domain;
	}


	/**
	 * Get administrator email.
	 *
	 * @return string
	 */
	public static function get_administrator_email(): string {
		if ( empty( self::$administrator_email ) ) {
			return get_option( 'admin_email' );
		}

		return self::$administrator_email;
	}

	/**
	 * Set administrator email.
	 *
	 * @param string $email Email address.
	 */
	public static function set_administrator_email( string $email ): void {
		self::$administrator_email = $email;
	}


	/**
	 * Get no reply email.
	 *
	 * @return string
	 */
	public static function get_reply_to(): string {
		if ( empty( self::$reply_to ) ) {
			$http_host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : 'localhost';
			return sprintf( 'no-reply@%s', $http_host );
		}

		return self::$reply_to;
	}


	/**
	 * Set no reply email.
	 *
	 * @param string $email Email address.
	 */
	public static function set_reply_to( string $email ): void {
		self::$reply_to = $email;
	}


	/**
	 * Get WP Mail content type.
	 *
	 * @return string
	 */
	public static function get_wp_mail_content_type(): string {
		return self::$wp_mail_content_type;
	}


	/**
	 * Set WP Mail content type.
	 *
	 * @param string $type Content type.
	 */
	public static function set_wp_mail_content_type( string $type ): void {
		self::$wp_mail_content_type = $type;
	}


	/**
	 * Get passphrase.
	 *
	 * @return string
	 */
	public static function get_passphrase(): string {
		return self::$passphrase;
	}


	/**
	 * Set passphrase.
	 *
	 * @param string $passphrase Passphrase.
	 */
	public static function set_passphrase( string $passphrase ): void {
		self::$passphrase = $passphrase;
	}


	/**
	 * Get cipher method.
	 *
	 * @return string
	 */
	public static function get_cipher_algo(): string {
		return self::$cipher_algo;
	}
	

	/**
	 * Set cipher method.
	 *
	 * @param string $algo Cipher method.
	 */
	public static function set_cipher_algo( string $algo ): void {
		self::$cipher_algo = $algo;
	}


	/**
	 * Get logger.
	 *
	 * @return \Unax\Logger\Logger|\WC_Logger  RFC 5424 logger instance.
	 */
	public static function get_logger() {
		if ( empty( self::$logger ) ) {
			self::$logger = \Unax\Logger\Logger::getLogger();
		}

		return self::$logger;
	}


	/**
	 * Set logger.
	 *
	 * @param Logger $logger Logger.
	 */
	public static function set_logger( Logger $logger ): void {
		self::$logger = $logger;
	}


	/**
	 * Get log threshold.
	 *
	 * @return string
	 */
	public static function get_log_threshold(): string {
		return self::$log_threshold;
	}


	/**
	 * Set log threshold.
	 *
	 * @param string $threshold Log threshold.
	 */
	public static function set_log_threshold( string $threshold ): void {
		self::$log_threshold = $threshold;
	}


	/**
	 * Get logs dir.
	 *
	 * @return string
	 */
	public static function get_logs_dir(): string {
		global $plugin;

		if ( empty( self::$logs_dir ) ) {
			$logs_dir = isset( $plugin ) ? untrailingslashit( plugin_dir_path( $plugin ) ) : dirname( __DIR__, 2 );
			return $logs_dir . '/logs';
		}

		return self::$logs_dir;
	}


	/**
	 * Set logs dir.
	 *
	 * @param string $dir Logs dir.
	 */
	public static function set_logs_dir( string $dir ): void {
		self::$logs_dir = $dir;
	}


	/**
	 * Get logs prefix.
	 */
	public static function get_log_prefix(): string {
		global $plugin;
		if ( empty( self::$log_prefix ) ) {
			return isset( $plugin ) ? basename( dirname( $plugin ) ) : 'helper';
		}

		return self::$log_prefix;
	}

	/**
	 * Set logs prefix.
	 *
	 * @param string $prefix Logs prefix.
	 */
	public static function set_log_prefix( string $prefix ): void {
		self::$log_prefix = $prefix;
	}

	/**
	 * Add admin notice.
	 *
	 * @param string $text        Message to be displayed.
	 * @param string $type        Message type.
	 * @param bool   $dismissible Dismissable.
	 */
	public static function add_admin_notice( string $text = '', string $type = 'error', bool $dismissible = true ) {
		$notices = get_option( 'unax-admin-notices', array() );

		$notice = array(
			'text' => $text,
			'type' => $type,
			'dismissible' => $dismissible,
		);

		array_push( $notices, $notice );

		update_option( 'unax-admin-notices', $notices, false );
	}


	/**
	 * Displays notices on the admin screen.
	 *
	 * @return void
	 */
	public static function admin_notices(): void {
		$notices = get_option( 'unax-admin-notices', array() );
		if ( empty( $notices ) ) {
			return;
		}

		foreach ( $notices as $notice ) {
			$text = isset( $notice['text'] ) ? $notice['text'] : '';
			$type = isset( $notice['type'] ) && in_array( $notice['type'], array( 'error', 'warning', 'info', 'success' ), true ) ? $notice['type'] : '';
			$dismissible = isset( $notice['dismissible'] ) ? $notice['dismissible'] : true;

			printf(
				'<div class="notice notice-%s%s"><p>%s</p></div>',
				esc_attr( $type ),
				$dismissible ? ' is-dismissible' : '',
				esc_html( $text )
			);
		}

		delete_option( 'unax-admin-notices' );
	}


	/**
	 * Add notice.
	 *
	 * @param string $text        Message to be displayed.
	 * @param string $type        Message type.
	 */
	public static function add_notice( string $text = '', string $type = 'info' ) {
		$notices = get_option( 'unax-notices', array() );

		$notice = array(
			'text' => $text,
			'type' => $type,
		);

		array_push( $notices, $notice );

		update_option( 'unax-notices', $notices, false );
	}


	/**
	 * Displays notices.
	 */
	public static function notices(): void {
		$notices = self::get_notices();
		if ( empty( $notices ) ) {
			return;
		}

		foreach ( $notices as $notice ) {
			$text = isset( $notice['text'] ) ? $notice['text'] : '';
			$type = isset( $notice['type'] ) && in_array( $notice['type'], array( 'error', 'warning', 'info', 'success' ), true ) ? $notice['type'] : '';

			printf(
				'<p class="notice notice-%s">%s</p>',
				esc_attr( $type ),
				esc_html( $text )
			);
		}
	}


	/**
	 * Displays notices.
	 *
	 * @return array
	 */
	public static function get_notices(): array {
		$notices = get_option( 'unax-notices', array() );

		delete_option( 'unax-notices' );

		if ( empty( $notices ) ) {
			return array();
		}

		return $notices;
	}


	/**
	 * Format log message.
	 *
	 * @param string $message Log message.
	 * @param string $file    Log file.
	 * @param string $line    Log line.
	 */
	public static function format_log( string $message = '', string $file = '', string $line = '' ): string {
		if ( empty( $file ) && empty( $line ) ) {
			return $message;
		}

		return sprintf( '%s:%d %s', basename( $file ), $line, $message );
	}


	/**
	 * Log message.
	 *
	 * @param string $context Log context.
	 * @param string $message Log message.
	 * @param array  $params  Log params.
	 * @param string $file    Log file.
	 * @param string $line    Log line.
	 * @param string $level   Log level.
	 */
	public static function log( string $context = '', string $message = '', array $params = array(), string $file = '', string $line = '', string $level = 'error' ): void {
		$log = $context;

		if ( ! empty( $message ) ) {
			$log = sprintf(
				'%s: %s',
				$log,
				$message
			);
		}

		if ( ! empty( $params ) ) {
			$log .= ' (';
			foreach ( $params as $key => $value ) {
				$log .= sprintf( '%s #%d', $key, $value );

				if ( $value !== end( $params ) ) {
					$log .= ', ';
				}
			}
			$log .= ')';
		}

		$logger = self::get_logger();
		echo $level;
		$logger->$level( self::format_log( $log, $file, $line ) );
	}


	/**
	 * Format datetime.
	 *
	 * @param string $date  Date.
	 *
	 * @return string
	 */
	public static function format_date( string $date = '' ): string {
		if ( empty( $date ) ) {
			return '';
		}

		$datetime = date_create_from_format( get_option( 'date_format' ), $date );
		if ( empty( $datetime ) ) {
			return '';
		}

		return $datetime->format( 'Y-m-d' );
	}


	/**
	 * Format datetime to settings format.
	 *
	 * @param string $date          Date.
	 * @param bool   $display_time  Date.
	 *
	 * @return string
	 */
	public static function format_date_wp( string $date = '', bool $display_time = false ): string {
		if ( empty( $date ) ) {
			return '';
		}

		$datetime = new \DateTime( $date );
		if ( empty( $datetime ) ) {
			return '';
		}

		if ( $display_time ) {
			return $datetime->format( sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) ) );
		}

		return $datetime->format( get_option( 'date_format' ) );
	}


	/**
	 * Verify nonce.
	 *
	 * @param string      $nonce   Nonce name (concatenated with the prefix _nonce_).
	 * @param string|null $context Nonce context.
	 * @param string|null $prefix  Nonce prefix.
	 *
	 * @return void
	 */
	public static function nonce_field( string $nonce, ?string $context = null, ?string $prefix = null ): void {
		if ( null === $prefix ) {
			$prefix = '_nonce_';
		}

		if ( null === $context ) {
			$context = $nonce;
		}

		wp_nonce_field( $context, sprintf( '%s%s', $prefix, $nonce ) );
	}


	/**
	 * Verify nonce.
	 *
	 * @param string      $nonce   Nonce name (concatenated with the prefix _nonce_).
	 * @param string|null $context Nonce context.
	 * @param int         $post_id Post ID.
	 * @param string|null $prefix  Nonce prefix.
	 *
	 * @return bool
	 */
	public static function verify_nonce( string $nonce, ?string $context = null, int $post_id = 0, ?string $prefix = null ): bool {
		if ( null === $prefix ) {
			$prefix = '_nonce_';
		}

		if ( null === $context ) {
			$context = $nonce;
		}

		if ( empty( $_REQUEST[ sprintf( '%s%s', $prefix, $nonce ) ] ) ) {
			self::log(
				'Verify nonce',
				'Nonce missing',
				array(
					'nonce' => $nonce,
					'context' => $context,
					'post_id' => $post_id,
				),
				__FILE__,
				__LINE__
			);
			return false;
		}

		$verify_nonce = wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_REQUEST[ sprintf( '%s%s', $prefix, $nonce ) ] ) ),
			$context
		);

		if ( empty( $verify_nonce ) ) {
			self::log(
				'Verify nonce',
				'Nonce not valid',
				array(
					'nonce' => $nonce,
					'context' => $context,
					'post_id' => $post_id,
				),
				__FILE__,
				__LINE__
			);
			return false;
		}

		return true;
	}


	/**
	 * Prepare fields form fields.
	 *
	 * @param array $fields Form fields.
	 *
	 * @return array
	 */
	public static function prepare_fields( array $fields = array() ): array {
		$prepared_fields = array();
		foreach ( $fields as $key => $field ) {
			$prepared_key                     = \preg_replace( '/[\_]/', '-', $key );
			$prepared_fields[ $prepared_key ] = $field;
		}

		return $prepared_fields;
	}


	/**
	 * Validate form field.
	 *
	 * @param mixed    $value      Field value.
	 * @param string   $type       Field type.
	 * @param bool     $required   Field mandatory.
	 * @param int|null $min_length Value min length.
	 * @param int|null $max_length Value max length.
	 *
	 * @return array
	 */
	public static function validate_field( $value = '', string $type = 'text', bool $required = true, ?int $min_length = null, ?int $max_length = null ): array {
		$result = array(
			'success' => false,
			'code'    => 400,
			'message' => null,
			'data'    => null,
		);

		$value = \is_array( $value ) ? $value : trim( $value );

		if ( $required && empty( $value ) ) {
			$result['message'] = esc_html__( 'Mandatory field', 'sezarweld' );
			return $result;
		}

		switch ( $type ) {
			case 'email':
				if ( false === filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
					$result['message'] = esc_html__( 'Invalid email address', 'sezarweld' );
					return $result;
				}
				break;

			case 'tel':
				if ( ! empty( preg_match( '/[^0-9\+\s+]/', $value ) ) ) {
					$result['message'] = esc_html__( 'Invalid phone number', 'sezarweld' );
					return $result;
				}

				break;

			default:
				// code...
				break;
		}

		if ( ! empty( $min_length ) && ! is_array( $value ) && strlen( $value ) < $min_length ) {
			$result['message'] = sprintf(
				// Translators: Field length (Integer).
				esc_html__( 'Field length minimum %s.', 'sezarweld' ),
				$min_length
			);
			return $result;
		}

		if ( ! empty( $max_length ) && ! is_array( $value ) && strlen( $value ) > $max_length ) {
			$result['message'] = sprintf(
				// Translators: Field length (Integer).
				esc_html__( 'Field length maximum %s.', 'sezarweld' ),
				$max_length
			);
			return $result;
		}

		$result['success'] = true;
		$result['code']    = 200;
		$result['data']    = $value;

		return $result;
	}

	/**
	 * Generate label.
	 *
	 * @param string $id            Select id.
	 * @param string $label         Select label.
	 * @param string $attr_class    Label class.
	 * @param bool   $label_visible Label visible.
	 * @param bool   $inline        Inline label.
	 */
	public static function generate_label( string $id, string $label = '', string $attr_class = '', bool $label_visible = true, bool $inline = false ): void {
		$attr_class !== '' ? ' ' . $attr_class : '';
		printf(
			'<label for="%s" class="%s%s">%s</label>',
			esc_attr( $id ),
			$label_visible ? '' : 'screen-reader-text',
			esc_attr( $attr_class ),
			esc_html( $label )
		);

		if ( ! $inline ) {
			echo '<br>';
		}
	}


	/**
	 * Generate input type text.
	 *
	 * @param string      $type        Input type.
	 * @param string      $id          Input id.
	 * @param string      $label       Input label.
	 * @param string      $attr_class  Input class.
	 * @param string|null $value       Input value.
	 * @param bool        $placeholder Input placeholder.
	 */
	public static function generate_input_text( string $type, string $id, string $label = '', string $attr_class = '', ?string $value = null, bool $placeholder = true ): void {
		if ( empty( $value ) ) {
			$value = get_query_var( $id, '' );
		}

		printf(
			'<input type="%1$s" name="%2$s" id="%2$s" value="%3$s" class="%4$s" placeholder="%5$s">',
			esc_attr( $type ),
			esc_attr( $id ),
			esc_attr( $value ),
			esc_attr( $attr_class ),
			$placeholder ? esc_attr( $label ) : ''
		);
	}


	/**
	 * Generate input datepicker.
	 *
	 * @param string      $id         Input id.
	 * @param string      $label      Input label.
	 * @param string      $attr_class Input class.
	 * @param string|null $value      Input value.
	 */
	public static function generate_input_datepicker( string $id, string $label = '', string $attr_class = '', ?string $value = null ): void {
		if ( empty( $value ) ) {
			$value = get_query_var( $id, '' );
		}

		$attr_class !== '' ? ' ' . $attr_class : '';
		printf(
			'<input type="date" name="%1$s" id="%1$s" value="%2$s" class="datepicker%3$s" placeholder="%4$s">',
			esc_attr( $id ),
			esc_attr( $value ),
			esc_attr( $attr_class ),
			esc_attr( $label )
		);
	}


	/**
	 * Generate textare.
	 *
	 * @param string      $id          Input id.
	 * @param string      $label       Input label.
	 * @param string      $attr_class  Input class.
	 * @param string|null $value       Input value.
	 * @param bool        $placeholder Input placeholder.
	 */
	public static function generate_textarea( string $id, string $label = '', string $attr_class = '', ?string $value = null, bool $placeholder = true ): void {
		if ( empty( $value ) ) {
			$value = get_query_var( $id, '' );
		}

		printf(
			'<textarea name="%1$s" id="%1$s" class="%2$s" placeholder="%3$s">%4$s</textarea>',
			esc_attr( $id ),
			esc_attr( $attr_class ),
			$placeholder ? esc_attr( $label ) : '',
			esc_attr( $value ),
		);
	}


	/**
	 * Generate dropdown.
	 *
	 * @param string $id          Select id.
	 * @param array  $options     Select options.
	 * @param string $attr_class  Select class.
	 * @param string $selected    Selected value.
	 * @param string $choose_text Choose text.
	 *
	 * @return void
	 */
	public static function generate_dropdown( string $id, array $options = array(), string $attr_class = '', string $selected = '', string $choose_text = '' ): void {
		printf( '<select name="%1$s" id="%1$s" class="%2$s">', esc_attr( $id ), esc_attr( $attr_class ) );
		if ( empty( $choose_text ) ) {
			printf( '	<option value="">%s</option>', esc_html__( 'Choose', 'sezarweld' ) );
		} else {
			printf( '	<option value="">%s</option>', esc_html( $choose_text ) );
		}

		if ( empty( $selected ) ) {
			$selected = get_query_var( $id, '' );
		}

		foreach ( $options as $key => $value ) {
			printf(
				'	<option value="%s"%s>%s</option>',
				esc_attr( $key ),
				$key === $selected ? ' selected="selected"' : '',
				esc_html( $value ),
			);
		}
		echo '</select>';
	}


	/**
	 * Generate radio buttons.
	 *
	 * @param string $id         Select id.
	 * @param string $label      Select label.
	 * @param array  $options    Select options.
	 * @param string $attr_class Select class.
	 * @param string $selected   Selected value.
	 *
	 * @return void
	 */
	public static function generate_radio_buttons( string $id, string $label = '', array $options = array(), string $attr_class = '', string $selected = '' ): void {
		if ( empty( $selected ) ) {
			$selected = get_query_var( $id, '' );
		}

		echo '<fieldset>';
		printf( '<legend class="screen-reader-text %s"><span>%s</span></legend>', esc_attr( $attr_class ), esc_html( $label ) );

		foreach ( $options as $key => $value ) {
			printf(
				'<label for="%1$s"><input type="radio" id="%1$s" name="%2$s" value="%3$s"%4$s> %5$s</label><br>',
				esc_attr( $key ),
				esc_attr( $id ),
				esc_attr( $key ),
				$key === $selected ? ' checked="checked"' : '',
				esc_html( $value )
			);
		}

		echo '</fieldset>';
	}


	/**
	 * Generate radio buttons.
	 *
	 * @param string      $id         Select id.
	 * @param string      $label      Select label.
	 * @param string      $attr_class Select class.
	 * @param string|null $value      Select value.
	 * @param string      $selected   Selected value.
	 */
	public static function generate_checkbox( string $id, string $label = '', string $attr_class = '', ?string $value = null, string $selected = '' ): void {
		if ( empty( $selected ) ) {
			$selected = get_query_var( $id, '' );
		}

		printf( '<fieldset class="%s">', esc_attr( $attr_class ) );
		printf( '	<legend class="screen-reader-text"><span>%s</span></legend>', esc_html( $label ), esc_attr( $attr_class ) );
		printf(
			' 	<label for="%1$s"><input name="%1$s" type="checkbox" id="%1$s" value="1"%2$s>%3$s</label>',
			esc_attr( $id ),
			$value === $selected ? ' checked="checked"' : '',
			esc_html( $label ),
		);
		echo '</fieldset>';
	}


	/**
	 * Check Google Recaptcha.
	 *
	 * @param string $recaptcha_token Recaptcha token.
	 * @param string $secret_key      Secret key.
	 *
	 * @throws \Exception Unauthorized.
	 *
	 * @return bool
	 */
	public static function check_google_recaptcha( string $recaptcha_token, string $secret_key ): bool {
		try {
			if ( empty( $recaptcha_token ) ) {
				throw new \Exception( esc_html__( 'Unauthorized', 'sezarweld' ) );
			}

			// Check Google Recaptcha.
			$data = array(
				'response' => $recaptcha_token,
				'secret'   => $secret_key,
			);

			$recaptcha_repsonse = wp_remote_get( sprintf( 'https://www.google.com/recaptcha/api/siteverify?%s', http_build_query( $data ) ) );
			if ( \is_wp_error( $recaptcha_repsonse ) ) {
				throw new \Exception( $recaptcha_repsonse->get_error_message() );
			}

			if ( empty( $recaptcha_repsonse['body'] ) ) {
				throw new \Exception( 'Google Recaptcha response error.' );
			}

			$repsonse = \json_decode( $recaptcha_repsonse['body'], true );
			if ( empty( $repsonse ) ) {
				throw new \Exception( 'Google Recaptcha decoding error.' );
			}

			if ( ! isset( $repsonse['success'] ) || false === $repsonse['success'] ) {
				if ( isset( $repsonse['error-codes'] ) ) {
					throw new \Exception( sprintf( 'Google Recaptcha error: %s.', implode( ', ', $repsonse['error-codes'] ) ) );
				}

				throw new \Exception( 'Google Recaptcha failed.' );
			}

			if ( ! isset( $repsonse['score'] ) || $repsonse['score'] <= 0.5 ) {
				throw new \Exception( 'Google Recaptcha low score.' );
			}

			return true;
		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage(), 401 );
			return false;
		}
	}


	/**
	 * Send email.
	 *
	 * @param string $to      Comma-separated list of email addresses to send message.
	 * @param string $subject Email subject.
	 * @param string $message Message contents.
	 *
	 * @return bool
	 */
	public static function send_email( string $to, string $subject, string $message ): bool {
		$from_name  = apply_filters( 'unax_helper_send_mail_from_name', \get_bloginfo( 'site_title' ) );
		$from_email  = apply_filters( 'unax_helper_send_mail_from_email', self::get_reply_to() );
		$reply_to = apply_filters( 'unax_helper_send_mail_reply_to', self::get_reply_to() );

		$headers = array(
			sprintf( 'From: %s <%s>', $from_name, $from_email ),
			sprintf( 'Reply-To: <%s>', 	$reply_to ),
		);

		$wp_mail = \wp_mail( $to, $subject, nl2br( $message ), $headers );

		if ( ! $wp_mail ) {
			$logger = self::get_logger();
			$logger->error( sprintf( 'Sending email to %s failed', esc_html( $to ) ) );
		}

		return $wp_mail;
	}


	/**
	 * Send admin notification.
	 *
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 * @param string $to      Email address.
	 *
	 * @return bool
	 */
	public static function admin_notification( string $subject = '', string $message = '', string $to = '' ): bool {
		if ( empty( $to ) ) {
			$to = self::get_administrator_email();
		}

		return self::send_email( $to, $subject, $message );
	}


	/**
	 * Send notification.
	 *
	 * @param string $to      Email address.
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 *
	 * @return bool
	 */
	public static function notification( string $to, string $subject = '', string $message = '' ): bool {
		$message .= '<br><br>';
		$message .= esc_html__( 'This is an autogenerated message. Please do not reply.', 'sezarweld' );

		return self::send_email( $to, $subject, $message );
	}


	/**
	 * Encrypt data.
	 *
	 * @param string $data Data to be encrypted.
	 *
	 * @return string|false
	 */
	public static function encrypt( string $data ) {
		if ( empty( $data ) ) {
			return false;
		}

		if ( ! in_array( self::$cipher_algo, openssl_get_cipher_methods(), true ) ) {
			return false;
		}

		$passphrase = self::get_passphrase();
		$iv = substr( $passphrase, 0, openssl_cipher_iv_length( self::$cipher_algo ) );

		return openssl_encrypt( $data, self::$cipher_algo, $passphrase, 0, $iv );
	}


	/**
	 * Decrypt data.
	 *
	 * @param string $data Data to be decrypted.
	 *
	 * @return string|false
	 */
	public static function decrypt( string $data ) {
		if ( empty( $data ) ) {
			return false;
		}

		if ( ! in_array( self::$cipher_algo, openssl_get_cipher_methods(), true ) ) {
			return false;
		}

		$passphrase = self::get_passphrase();
		$iv = substr( $passphrase, 0, openssl_cipher_iv_length( self::$cipher_algo ) );

		return openssl_decrypt( $data, self::$cipher_algo, $passphrase, 0, $iv );
	}
}
