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
			'show_in_menu' => false,
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
		add_submenu_page( $menu_slug , 'Sammanfattning', 'Sammanfattning', $capability, 'Sammanfattning' , $function, $icon_url, $position );
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
	$person = $_POST["person"];
		$args = array(
			'post_type' => array('tidsrapportering'),
			'post_status' => array('publish'),
			'posts_per_page' => -1,
			'order' => 'DESC',
			'meta_key' => 'person',
			'meta_value' => $person,
			'meta_compare' => '=',
		);
		$reports = new WP_Query( $args );
		$datas = array();
		while($reports->have_posts()){
			$reports->the_post();
			$person = get_post_meta(get_the_ID(), 'person', true);
			$date = get_post_meta(get_the_ID(), 'date', true);
			$time_start = get_post_meta(get_the_ID(), 'time_start', true);
			$time_end = get_post_meta(get_the_ID(), 'time_end', true);
			$date_end = get_post_meta(get_the_ID(), 'date_end', true);
			$total_hours = get_post_meta(get_the_ID(), 'total_hours', true);
			$task = get_post_meta(get_the_ID(), 'task', true);
			$work_area = get_post_meta(get_the_ID(), 'work_area', true);
			$extra_info = get_post_meta(get_the_ID(), 'extra_info', true);
			$datas[] = array(
				"person" => $person,
				"date" => $date,
				"time_start" => $time_start,
				"time_end" => $time_end,
				"total_hours" => $total_hours,
				"task" => $task,
				"work_area" => $work_area,
				"extra_info" => $extra_info
			);
		  }
		  $pdf = new TCPDF('P', 'pt', 'A4', true, 'UTF-8', false);
		  $pdf->SetMargins(10, 60, 10, true);
		  $pdf->SetAutoPageBreak(TRUE, 10);
		  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		  $pdf->setLanguageArray($l);
		  $pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
		  $pdf->SetTextColor(3, 3, 4);
		  $pdf->SetDrawColor(3, 3, 4, false, '');
		  $pdf->AddPage('P', 'A4');
		  ob_get_clean();
		  ob_start();
		  ?>
			<h1>Sammanfattning</h1>
			<table cellpadding="5">
				<thead>
		  			<tr style="font-size:13px; font-weight:bold;">
		  				<td>Arbetare</td>
						<td>Datum</td>
						<td>Tid från:</td>
						<td>Tid till:</td>
						<td>Antal timmer</td>
						<td>Uppdrag</td>
						<td>Arbetsplats</td>
						<td>Mer info</td>
					</tr>
				</thead>
				<tbody>
				<?php 
				foreach ($datas as $data) {
				?>
					<tr style="font-size:13px; height:150px">
						<td><?php echo $data["person"]; ?></td>
						<td><?php echo $data["date"]; ?></td>
						<td><?php echo $data["time_start"]; ?></td>
						<td><?php echo $data["time_end"]; ?></td>
						<td><?php echo $data["total_hours"]; ?></td>
						<td><?php echo $data["task"]; ?></td>
						<td><?php echo $data["work_area"]; ?></td>
						<td><?php echo $data["extra_info"]; ?></td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
		  <?php
		  $html = ob_get_contents();
		  ob_get_clean();
		  $pdf->writeHTML($html, true, false, true, false, '');
		  $pdf->Output('sammanfattning.pdf', 'D');
	}
}
