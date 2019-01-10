<?php
class Tidsrapportering_Admin {
	private $plugin_name;
	private $version;
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tidsrapportering-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'datepicker-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap-datepicker.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'timepicker-css', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'datepicker-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap-datepicker.js', array(), $this->version, false );
		wp_enqueue_script( 'timepicker-js', plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.js', array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tidsrapportering-admin.js', array( 'jquery' ), $this->version, false );
	}
	public function create_custom_post_type() {
		$labels = array(
			'name' => $this->plugin_name,
			'singular_name' => $this->plugin_name,
		);
		$args = array(
			'label' => $this->plugin_name,
			'description' => '',
			'labels' => $labels,
			'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments','custom-fields'),
			'taxonomies' => array(),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => true,
			'hierarchical' => false,
			'exclude_from_search' => false,
			'show_in_rest' => true,
			'publicly_queryable' => true,
			'capability_type' => 'post',
		);
		register_post_type( 'tidsrapportering', $args );
	}

	public function create_admin_menu() {
		$page_title = $this->plugin_name;
		$menu_title = 'Ny Tidsrapport';
		$capability = 'edit_posts';
		$menu_slug = 'tidsrapportering';
		$function = 'tidsrapportering_save';
		$icon_url = '';
		$position = 24;
	
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	}

	public function submit_report() {
		$datas = $_POST;
		$title = $datas["person"].' - '.$datas["date"];
		$args = array(
			'post_title' => $title,
			'post_content' => $datas["extra_info"],
			'post_status' => 'publish',
			'post_author'   => get_current_user_id(),
			'post_type' => 'tidsrapportering',
		);
		$report_id = wp_insert_post( $args  );
		foreach ($datas as $key => $value) {
			add_post_meta( $report_id, $key, $value, true);
		}
		wp_send_json(get_current_user_id());
		
	}
	public function get_reports() {
		$data = $_GET;
		$args = array(
			'post_type' => array('tidsrapportering'),
			'post_status' => array('publish'),
			'post_author'   => get_current_user_id(),
			'posts_per_page' => -1,
			'order' => 'DESC',
		);
		$reports = new WP_Query( $args );
		while($reports->have_posts()){
			$reports->the_post();
			$time_start = get_post_meta(get_the_ID(), 'time_start', true);
		  }
		wp_send_json($time_start);
	}
}
