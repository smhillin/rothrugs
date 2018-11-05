<?php
/*
	Plugin Name: MouseWheel Smooth Scroll
	Plugin URI: http://kubiq.sk
	Description: MouseWheel smooth scrolling for your WordPress website
	Version: 2.2
	Author: Jakub Novák
	Author URI: http://kubiq.sk
*/

if (!class_exists('wpmss')) {
	class wpmss {
		var $domain = 'wpmss';
		var $plugin_admin_page;
		var $settings;
		var $tab;
		
		function wpmss_func(){ $this->__construct(); }	
		
		function __construct(){
			$mo = plugin_dir_path(__FILE__) . 'languages/' . $this->domain . '-' . get_locale() . '.mo';
			load_textdomain($this->domain, $mo);
			add_action( 'admin_menu', array( &$this, 'plugin_menu_link' ) );
			add_action( 'init', array( &$this, "plugin_init" ) );
		}
		
		function filter_plugin_actions($links, $file) {
		   $settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings') . '</a>';
		   array_unshift( $links, $settings_link );
		   return $links;
		}
		
		function plugin_menu_link() {
			$this->plugin_admin_page = add_submenu_page(
				'options-general.php',
				__( 'MouseWheel Smooth Scroll', $this->domain ),
				__( 'MouseWheel Smooth Scroll', $this->domain ),
				'manage_options',
				basename(__FILE__),
				array( $this, 'admin_options_page' )
			);
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'filter_plugin_actions'), 10, 2 );
		}
		
		function plugin_init(){
			$this->settings = get_option('wpmss_settings');
			add_action( 'wp_enqueue_scripts', array($this, 'plugin_scripts_load') );
		}

		function plugin_scripts_load() {
			$options = array(
				'step' => isset( $this->settings['general']['step'] ) && trim( $this->settings['general']['step'] ) != "" ? $this->settings['general']['step'] : 100,
				'speed' => isset( $this->settings['general']['speed'] ) && trim( $this->settings['general']['step'] ) != "" ? $this->settings['general']['speed'] : 400,
			);
			wp_enqueue_script( 'wpmss_scroll_scripts', plugins_url( 'js/wpmss.php?'.http_build_query($options), __FILE__ ), array('jquery'));
		}
		
		function plugin_admin_tabs( $current = 'general' ) {
			$tabs = array( 'general' => __('General'), 'info' => __('Help') ); ?>
			<h2 class="nav-tab-wrapper">
			<?php foreach( $tabs as $tab => $name ){ ?>
				<a class="nav-tab <?php echo ( $tab == $current ) ? "nav-tab-active" : "" ?>" href="?page=<?php echo basename(__FILE__) ?>&amp;tab=<?php echo $tab ?>"><?php echo $name ?></a>
			<?php } ?>
			</h2><br><?php
		}

		function admin_options_page() {
			if ( get_current_screen()->id != $this->plugin_admin_page ) return;
			$this->tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';
			if(isset($_POST['plugin_sent'])) $this->settings[ $this->tab ] = $_POST;
			update_option( "wpmss_settings", $this->settings ); ?>
			<div class="wrap">
				<h2><?php _e( 'MouseWheel Smooth Scroll', $this->domain ); ?></h2>
				<?php if(isset($_POST['plugin_sent'])) echo '<div id="message" class="below-h2 updated"><p>'.__( 'Settings saved.' ).'</p></div>'; ?>
				<form method="post" action="<?php admin_url( 'options-general.php?page=' . basename(__FILE__) ); ?>">
					<input type="hidden" name="plugin_sent" value="1"><?php
					$this->plugin_admin_tabs( $this->tab );
					switch ( $this->tab ) :
						case 'general' :
							$this->plugin_general_options();
							break;
						case 'info' :
							$this->plugin_info_options();
							break;
					endswitch; ?>
				</form>
			</div><?php
		}
		
		function plugin_general_options(){ ?>
			<table class="form-table">
				<tr>
					<th>
						<label for="q_field_1"><?php _e("Step:", $this->domain) ?></label> 
					</th>
					<td>
						<input type="number" name="step" placeholder="100" value="<?php echo $this->settings[ $this->tab ]["step"]; ?>" id="q_field_1">
					</td>
				</tr>
				<tr>
					<th>
						<label for="q_field_2"><?php _e("Speed:", $this->domain) ?></label> 
					</th>
					<td>
						<input type="number" name="speed" placeholder="400" value="<?php echo $this->settings[ $this->tab ]["speed"]; ?>" id="q_field_2">
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" class="button button-primary button-large" value="<?php _e( 'Save' ) ?>"></p><?php
		}
		
		function plugin_info_options(){ ?>
			<p><?php _e('Any ideas, problems, issues?', $this->domain) ?></p>
			<p>Ing. Jakub Novák</p>
			<p><a href="mailto:info@kubiq.sk" target="_blank">info@kubiq.sk</a></p>
			<p><a href="http://kubiq.sk/" target="_blank">http://kubiq.sk</a></p><?php
		}
	}
}

if (class_exists('wpmss')) { 
	$wpmss_var = new wpmss();
} ?>