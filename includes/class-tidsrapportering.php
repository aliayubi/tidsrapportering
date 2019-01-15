<?php
class Tidsrapportering {
	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if ( defined( 'TIDSRAPPORTERING_VERSION' ) ) {
			$this->version = TIDSRAPPORTERING_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = PLUGIN_NAME;

		$this->load_dependencies();
		$this->define_admin_hooks();
	}

	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/topdf/tcpdf_include.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/tidsrapportering-admin-display.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tidsrapportering-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-tidsrapportering-admin.php';
		$this->loader = new Tidsrapportering_Loader();
	}
	private function define_admin_hooks() {
		$plugin_admin = new Tidsrapportering_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'create_custom_post_type' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'create_admin_menu' );
		$this->loader->add_action( 'wp_ajax_submit_report', $plugin_admin, 'submit_report' );
		$this->loader->add_action( 'wp_ajax_nopriv_submit_report', $plugin_admin, 'submit_report' );
		$this->loader->add_action( 'wp_ajax_get_reports', $plugin_admin, 'get_reports' );
		$this->loader->add_action( 'wp_ajax_nopriv_get_reports', $plugin_admin, 'get_reports' );
	}
	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

		
	public function get_version() {
		return $this->version;
	}
}
