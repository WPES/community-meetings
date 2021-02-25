<?php

/**
 * Library for admin settings
 *
 * @package    WordPress
 * @author     David Perez <david@closemarketing.es>
 * @copyright  2021 Closemarketing
 * @version    1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Library for Admin Settings
 */
class METGS_Settings_Page {
	/**
	 * Settings
	 *
	 * @var array
	 */
	private  $meetup_settings;
	/**
	 * Construct of class
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Adds plugin page.
	 *
	 * @return void
	 */
	public function add_plugin_page() {
		add_submenu_page(
			'edit.php?post_type=metgs_meeting',
			__( 'Settings', 'meetings' ),
			__( 'Settings', 'meetings' ),
			'manage_options',
			'meetings_admin',
			array( $this, 'create_admin_page' ),
		);
	}

	/**
	 * Create admin page.
	 *
	 * @return void
	 */
	public function create_admin_page() {
		$this->meetup_settings = get_option( 'meetings' );
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Meetings Settings', 'meetings' ); ?>
			</h2>
			<p></p>
			<?php
			settings_errors();
			?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'meetings_settings' );
				do_settings_sections( 'meetings-admin' );
				submit_button( __( 'Save settings', 'meetings' ), 'primary', 'submit_settings' );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Init for page
	 *
	 * @return void
	 */
	public function page_init() {
		register_setting( 'meetings_settings', 'meetings', array( $this, 'sanitize_fields' ) );

		add_settings_section(
			'metgs_setting_section',
			__( '', 'meetings' ),
			array( $this, 'metgs_section_info' ),
			'meetings-admin'
		);

		add_settings_field(
			'metgs_meetup_url',
			__( 'Meetup URL', 'meetings' ),
			array( $this, 'metgs_meetup_url_callback' ),
			'meetings-admin',
			'metgs_setting_section'
		);
	}

	/**
	 * Sanitize fiels before saves in DB
	 *
	 * @param array $input Input fields.
	 * @return array
	 */
	public function sanitize_fields($input)
	{
		$sanitary_values = array();
		$meetup_settings = get_option('meetings');

		if ( isset( $input['metgs_meetup_url'] ) ) {
			$sanitary_values['metgs_meetup_url'] = sanitize_text_field( $input['metgs_meetup_url'] );
		}
		return $sanitary_values;
	}

	/**
	 * Info for holded automate section.
	 *
	 * @return void
	 */
	public function metgs_section_info() {
		echo sprintf( __( 'Put the connection API key settings in order to connect and sync products. You can go here <a href="%s" target="_blank">Meetings API</a>. ', 'meetings' ), 'https://www.meetup.com/' );
	}

	public function metgs_meetup_url_callback() {
		printf( '<input class="regular-text" type="text" name="meetings[metgs_meetup_url]" id="metgs_meetup_url" value="%s">', ( isset( $this->meetup_settings['metgs_meetup_url'] ) ? esc_attr( $this->meetup_settings['metgs_meetup_url'] ) : '' ) );
	}
}
if ( is_admin() ) {
	$meetings_admin = new METGS_Settings_Page();
}
