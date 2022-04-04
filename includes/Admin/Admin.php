<?php
namespace ZiUltimate\Admin;

use ZiUltimate\RegisterElements;
use ZionBuilder\Whitelabel;
use ZiUltimate\Admin\License;
use ZiUltimate\Admin\AdminMenuBar;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class Admin {

	private $menu_name = 'ZiUltimate';
	private $menu_slug = 'ziultimate';
	private $zuwl = '';

	function __construct() {
		$this->zu_save_white_label_data();

		$this->zuwl = get_option('zuwl');
		if( ! empty( $this->zuwl ) ) {
			$this->menu_name = ! empty( $this->zuwl['menu_name'] ) ? esc_html( $this->zuwl['menu_name'] ) : $this->menu_name;
			$this->menu_slug = ! empty( $this->zuwl['menu_slug'] ) ? esc_html( $this->zuwl['menu_slug'] ) : $this->menu_slug;
		}

		add_action( 'admin_menu', array( $this, 'zu_register_admin_menu' ) );

		add_filter( 'plugin_action_links', array( $this, 'zu_add_settings_link' ), 10, 2 );
		add_filter( 'network_admin_plugin_action_links', array( $this, 'zu_add_settings_link' ), 10, 2 );
		
		if( is_admin() ) 
		{
			add_action('admin_footer', [ $this, 'zu_change_settings_ui'] );
		}

		//* Show mnu items to admin bar
		if( is_admin_bar_showing() && ! is_admin() ) 
		{
			new AdminMenuBar();
		}
	}

	/**
	 * Registering the sub menu link
	 */
	function zu_register_admin_menu() {
		add_submenu_page( Whitelabel::get_id(), $this->menu_name, $this->menu_name, 'manage_options', $this->menu_slug, array( $this, 'render_options_form' ) );
	}

	/**
	 * Rendering the options form
	 */
	function render_options_form() {
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : false;
		if ( ! License::has_valid_license() ) {
			$tab = 'license';
		}

		$user_id 	= get_current_user_id();
		$permission = [ $user_id ];
		$permission = ! empty( $this->zuwl['tab_permission'] ) ? explode( ",", $this->zuwl['tab_permission'] ) : $permission;
	?>
		<div class="wrap">
			<h2 class="nav-tab-wrapper">
				<a href="?page=<?php echo $this->menu_slug; ?>&amp;tab=elements" class="nav-tab<?php echo ( $tab === false || $tab == 'editor' || $tab == 'elements' ) ? ' nav-tab-active' : '';?>"><span class="dashicons dashicons-admin-tools" style="height: 18px; font-size: 17px; margin-right: 3px;"></span> <?php _e( 'General Elements', 'ziultimate' ); ?></a>
				
				<?php if( class_exists('WooCommerce') && class_exists( 'ZionBuilderPro\Plugin' ) ): ?>
					<a href="?page=<?php echo $this->menu_slug; ?>&amp;tab=woo" class="nav-tab<?php echo ( $tab === 'woo' ) ? ' nav-tab-active' : '';?>">
						<span class="dashicons dashicons-wordpress" style="height: 18px; font-size: 17px; margin-right: 3px;"></span> <?php _e( 'Woo Elements', 'ziultimate' ); ?></a>
				<?php endif; ?>

				<?php if( in_array( $user_id, $permission ) ) : ?>
					<a href="?page=<?php echo $this->menu_slug; ?>&amp;tab=whitelabel" class="nav-tab<?php echo ($tab == 'whitelabel') ? ' nav-tab-active' : '';?>">
						<span class="dashicons dashicons-edit" style="height: 18px; font-size: 17px; margin-right: 3px;"></span> <?php _e( 'White Label', 'ziultimate' ); ?></a>
				<?php endif; ?>
				
				<a href="?page=<?php echo $this->menu_slug; ?>&amp;tab=license" class="nav-tab<?php echo ($tab == 'license') ? ' nav-tab-active' : '';?>">
					<span class="dashicons dashicons-admin-network" style="height: 18px; font-size: 17px; margin-right: 3px;"></span> <?php _e( 'License', 'ziultimate' ); ?>
				</a>
			</h2>
	<?php
			if ( $tab === 'elements' || $tab == false ) {
				$this->zu_elements_wplist_table();
			}

			if ( $tab === 'woo' ) {
				$this->zuwoo_elements_wplist_table();
			}

			if ( $tab === 'whitelabel' && is_user_logged_in() && in_array( $user_id, $permission ) ) { 
				$this->zu_white_label();
			}

			if ( $tab === 'license' ) { 
				$this->zu_license_form();
			}

		echo '</div>';
	}

	/**
	 * Making the Woo elements listing table
	 * 
	 * @return void
	 */
	private function zuwoo_elements_wplist_table() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<h2 class="heading"><?php _e('WooCommerce Elements', 'ziultimate'); ?><br/>
			<span style="color: #e64be0; font-size: 10px;text-transform: uppercase;">
				<?php esc_html_e('You need ZiUltimate Pro for these elements', 'ziultimate' ); ?>
			</span>
		</h2>
		<?php
	}

	/**
	 * Making the elements listing table
	 * 
	 * @return void
	 */
	private function zu_elements_wplist_table() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if( isset( $_POST['action'] ) && $_POST['action'] == "save_elements" ){
			$details = self::zu_slice_array( RegisterElements::get_elements(), $_POST['cur_page'] );
			$active_els = (array) get_option('ziultimate_active_els');

			if( isset( $_POST['zuel'] ) ) {
				$posted_els = array_diff( $active_els, $_POST['zuel'] );
				$prev_actv_els = array_diff($posted_els, array_keys( $details['elements'] ) );
				$prev_actv_els = array_merge( $prev_actv_els, $_POST['zuel'] );

				if( empty( $prev_actv_els[0] ) ) { unset( $prev_actv_els[0] ); }

				update_option('ziultimate_active_els', array_values( $prev_actv_els ) );
			} else {
				$prev_actv_els = array_diff($active_els, array_keys( $details['elements'] ) );
				if( count( $prev_actv_els ) - 1 <= 0 && empty( $prev_actv_els[0] ) )
					delete_option('ziultimate_active_els');
				else
					update_option('ziultimate_active_els', $prev_actv_els );
			}

			printf('<div class="notice notice-info is-dismissible"><p>%s</p></div>', __('Settings saved successfully.', 'ziultimate'));
		} else {
			$details = self::zu_slice_array( RegisterElements::get_elements() );
		}

		$active_els = (array) get_option('ziultimate_active_els');
		$url 		= add_query_arg( 'tab', 'elements', menu_page_url( $this->menu_slug, false ) );

		// variables for pagination links
		$page_first = $details['page'] > 1 ? 1 : '';
		$page_prev  = $details['page'] > 1 ? $details['page'] - 1 : '';
		$page_next  = $details['page'] < $details['total_pages'] ? $details['page'] + 1 : '';
		$page_last  = $details['page'] < $details['total_pages'] ? $details['total_pages'] : '';
		?>
		<h2 class="heading"><?php _e('General Elements', 'ziultimate'); ?><br/>
			<span style="color: #e64be0; font-size: 10px;text-transform: uppercase;">
				<?php esc_html_e('Only active elements will show and use on the Zion Builder', 'ziultimate' ); ?>
			</span>
		</h2>

		<?php if( is_array( $active_els ) && ! empty( $active_els[0] ) ) { $count = count($active_els); ?>
			<p><strong>Activated: </strong><?php printf( translate_nooped_plural( _n_noop( '%s element', '%s elements', 'ziultimate' ), $count, 'ziultimate' ), number_format_i18n( $count ) ); ?></p>
		<?php } ?>
		<div class="form-plugin-links" style="display: flex;">
			<form method="post" action="<?php echo $url; ?>&paged=<?php echo $details['page']; ?>" style="width: 100%;">
				<input type="hidden" name="action" value="save_elements" />
				<table class="wp-list-table widefat plugins">
					<thead>
						<tr>
							<td id="cb" class="manage-column column-cb check-column">
								<label class="screen-reader-text" for="cb-select-all-1">
									<?php esc_html_e('Activate All' ); ?></label>
									<input id="cb-select-all-1" type="checkbox">
							</td>
							<th scope="col" id="name" class="manage-column column-name column-primary">
								<?php esc_html_e('Enable All'); ?>
							</th>
							<th style="width: 12%; text-align: right;"><?php _e('Documentation'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php echo self::zu_elements_list( $details['elements'] ); ?>
					</tbody>
					<tfoot>
						<tr>
							<td id="cb" class="manage-column column-cb check-column">
								<label class="screen-reader-text" for="cb-select-all-1">
									<?php esc_html_e('Activate All' ); ?></label>
									<input id="cb-select-all-1" type="checkbox">
							</td>
							<th scope="col" id="name" class="manage-column column-name column-primary">
								<?php esc_html_e('Enable All'); ?>
							</th>
							<td></td>
						</tr>
					</tfoot>
				</table>

				<div class="tablenav bottom">
					<div class="alignleft actions bulkactions">
						<?php submit_button(); ?>
						<input type="hidden" name="cur_page" value="<?php echo $details['page']; ?>">		
					</div>
					<div class="tablenav-pages">
						<span class="displaying-num"><?php echo $details['total_elements']; ?> <?php _e('items'); ?></span>
						<span class="pagination-links">
							<?php if( $page_first ) : ?>
								<a class="first-page button" href="<?php echo $url . '&paged=' . $page_first?>">«</a>
							<?php endif; ?>

							<?php if( $page_prev ) : ?>
								<a class="prev-page button" href="<?php echo $url . '&paged=' . $page_prev?>">‹</a>
							<?php endif; ?>

							<span class="screen-reader-text">Current Page</span>
							<span id="table-paging" class="paging-input">
								<span class="tablenav-paging-text"><?php echo $details['page']; ?> of <span class="total-pages"><?php echo $details['total_pages']; ?></span></span>
							</span>

							<?php if( $page_next ) : ?>
							<a class="next-page button" href="<?php echo $url . '&paged=' . $page_next?>"><span class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
							<?php endif; ?>

							<?php if( $page_last ) : ?>
								<a class="last-page button" href="<?php echo $url . '&paged=' . $page_last?>">»</a>
							<?php endif; ?>
						</span>
					</div>
					<br class="clear">
				</div>

			</form>
		</div>
		<?php
	}
	
	/**
	 * Registering the license activation/deactivation form
	 */
	private function zu_license_form() {
		$url = add_query_arg( 'tab', 'license', menu_page_url( $this->menu_slug, false ) );
		
		if ( isset( $_POST[ 'license_activate' ] ) ) {
			echo License::zu_acivate_license( $_POST[ 'zu_license_key' ] );
		}

		if ( isset( $_POST[ 'license_deactivate' ] ) ) {
			echo License::delete_license();
		}
		
		$status = get_option(License::API_KEY_STATUS_FIELD);

		?>
		<h2><?php _e( 'License Settings', 'ziultimate' ); ?></h2>
		<p class="description">Add the license key you received when purchasing ZiUltimate add-on.</p>
		<form method="post" action="<?php echo $url; ?>" class="license-activate-form">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e( 'License Key' ); ?>
							<?php if ( $status !== false && $status == 'valid' ) { ?>
								<span style="color:green;">(<?php _e( 'active' ); ?>)</span>
							<?php } ?>
						</th>
						<td>
							<input id="license_key" name="zu_license_key" type="password" class="regular-text" placeholder="<?php _e( 'Enter your license key' ); ?>" value="<?php echo ( $status !== false && $status == 'valid' ) ? '*****************************' : '' ;?>" />
							<?php if ( $status !== false && $status == 'valid' ) { ?>
								<input type="submit" class="button-secondary button" name="license_deactivate" value="<?php _e( 'Deactivate License' ); ?>"/>
							<?php } else { ?>
								<input type="submit" class="button button-primary" name="license_activate" value="<?php _e( 'Activate License' ); ?>"/>
							<?php } ?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php wp_nonce_field( 'zu_nonce_action', 'zu_nonce_field' ); ?>
		</form>
		<?php
	}

	private function zu_save_white_label_data() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if( isset( $_POST['action'] ) && $_POST['action'] == "save_wl_data" ){
			if( isset( $_POST['zuwl'] ) ) {
				update_option('zuwl', $_POST['zuwl']);
			} else {
				delete_option('zuwl');
			}

			printf('<div class="notice notice-info is-dismissible"><p>%s</p></div>', __('Settings saved successfully.', 'ziultimate'));

			$menu_slug = 'ziultimate';
			$zuwl = get_option('zuwl');
			if( $zuwl ) {
				$menu_slug = ! empty( $zuwl['menu_slug'] ) ? esc_html( $zuwl['menu_slug'] ) : $menu_slug;
			}

			$url = add_query_arg( 'tab', 'whitelabel', menu_page_url( $menu_slug, false ) ) . '&page='. $menu_slug;

			if ( wp_safe_redirect( $url ) ) {
			    exit;
			}
		}
	}

	private function zu_white_label() {
		$plugin_name 	= 'placeholder="ZiUltimate"';
		$plugin_uri 	= 'placeholder="https://ziultimate.com"';
		$author_name 	= 'placeholder="Chinmoy Paul"';
		$author_uri 	= 'placeholder="https://paulchinmoy.com"';
		$menu_name 		= 'placeholder="ZiUltimate"';
		$menu_slug 		= 'placeholder="ziultimate"';
		$menuslug 		= 'ziultimate';
		$plugin_desc 	= '';
		$tab_permission = 'placeholder="Enter user ID. Use comma for multiple users"';

		if( $this->zuwl ) {
			$plugin_name 	= ! empty( $this->zuwl['plugin_name'] ) ? 'value="' . esc_html( $this->zuwl['plugin_name'] ) . '"' : $plugin_name;
			$plugin_uri 	= ! empty( $this->zuwl['plugin_uri'] ) ? 'value="' . esc_html( $this->zuwl['plugin_uri'] ) . '"' : $plugin_uri;
			$author_name 	= ! empty( $this->zuwl['author_name'] ) ? 'value="' . esc_html( $this->zuwl['author_name'] ) . '"' : $author_name;
			$author_uri 	= ! empty( $this->zuwl['author_uri'] ) ? 'value="' . esc_html( $this->zuwl['author_uri'] ) . '"' : $author_uri;
			$plugin_desc 	= ! empty( $this->zuwl['plugin_desc'] ) ? esc_html( $this->zuwl['plugin_desc'] ) : $plugin_desc;
			$menu_name 		= ! empty( $this->zuwl['menu_name'] ) ? 'value="' . esc_html( $this->zuwl['menu_name'] ) . '"' : $menu_name;
			$menu_slug 		= ! empty( $this->zuwl['menu_slug'] ) ? 'value="' . esc_html( $this->zuwl['menu_slug'] ) . '"' : $menu_slug;
			$menuslug 		= ! empty( $this->zuwl['menu_slug'] ) ? esc_html( $this->zuwl['menu_slug'] ) : $menuslug;
			$tab_permission = ! empty( $this->zuwl['tab_permission'] ) ? 'value="' . esc_html( $this->zuwl['tab_permission'] ) . '"' : $tab_permission;
		}

		$url = add_query_arg( 'tab', 'whitelabel', menu_page_url( $menuslug, false ) );
	?>
		<h2><?php _e( 'White Label', 'ziultimate' ); ?></h2>
		<p class="description">It gives you the ability to control and transform the appearance of the back-end.</p>
		<div style="border-top: 1px solid #ccd0d4; margin-top: 15px;">
			<form method="post" action="<?php echo $url; ?>">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'Plugin Name', 'ziultimate' ); ?>
							</th>
							<td>
								<input id="plugin_name" name="zuwl[plugin_name]" type="text" class="regular-text" <?php echo $plugin_name; ?> />
							</td>
						</tr>

						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'Plugin URI', 'ziultimate' ); ?>
							</th>
							<td>
								<input id="plugin_uri" name="zuwl[plugin_uri]" type="url" class="regular-text" <?php echo $plugin_uri; ?> />
							</td>
						</tr>

						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'Author Name', 'ziultimate' ); ?>
							</th>
							<td>
								<input id="author_name" name="zuwl[author_name]" type="text" class="regular-text" <?php echo $author_name; ?> />
							</td>
						</tr>

						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'Author URI', 'ziultimate' ); ?>
							</th>
							<td>
								<input id="author_uri" name="zuwl[author_uri]" type="url" class="regular-text" <?php echo $author_uri; ?> />
							</td>
						</tr>

						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'Plugin Description', 'ziultimate' ); ?>
							</th>
							<td>
								<textarea id="plugin_desc" name="zuwl[plugin_desc]" class="large-text" cols="5" rows="8" ><?php echo $plugin_desc; ?></textarea>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'Admin Menu Name', 'ziultimate' ); ?>
							</th>
							<td>
								<input id="menu_name" name="zuwl[menu_name]" type="text" class="regular-text" <?php echo $menu_name; ?> />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'Admin Menu Link Slug', 'ziultimate' ); ?>
							</th>
							<td>
								<input id="menu_slug" name="zuwl[menu_slug]" type="text" class="regular-text" <?php echo $menu_slug; ?> />
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'Permission', 'ziultimate' ); ?><br/>
								<lebel style="font-weight: normal; color: #999;"><?php _e( 'who can access this page', 'ziultimate' ); ?></lebel>
							</th>
							<td>
								<input id="tab_permission" name="zuwl[tab_permission]" type="text" class="regular-text" <?php echo $tab_permission; ?> />
							</td>
						</tr>
					</tbody>
				</table>
				<?php wp_nonce_field( 'zu_nonce_action', 'zu_nonce_field' ); ?>
				<input type="hidden" name="action" value="save_wl_data" />
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
	}

	/**
	 * Making the body part of the wp list table
	 */
	private static function zu_elements_list( $elements ) {
		$active_els = (array) get_option('ziultimate_active_els');
		foreach ( $elements as $key => $element ) {
		?>
			<tr valign="top" <?php echo in_array($key, $active_els) ? 'class="active"' : 'class="inactive"'; ?>>
				<th scope="row" class="check-column">
					<input id="<?php echo $key; ?>" name="zuel[]" type="checkbox" value="<?php echo $key; ?>" <?php echo in_array($key, $active_els) ? 'checked="checked"' : ''; ?> />
				</th>
				<td class="plugin-title column-primary">
					<?php echo '<strong>' . $element['name'] . '</strong>'; ?>
				</td>
				<td style="text-align: right;">
					<a href="<?php echo $element['link']; ?>" target="_blank"><?php _e('Read Doc'); ?></a>
				</td>
			</tr>
		<?php
		}
	}

	/**
	 * Slicing the array for pagination
	 */
	private static function zu_slice_array( $data = array(), $cur_page = 1 ) {
		$page = isset($_GET['paged']) ? intval($_GET['paged']) : $cur_page;
		$page = isset($_POST['cur_page']) ? intval($_POST['cur_page']) : $page;

		// The number of records to display per page
		$page_size = 15;

		// Calculate total number of records, and total number of pages
		$total_records = count($data);
		$total_pages   = ceil($total_records / $page_size);

		// Validation: Page to display can not be greater than the total number of pages
		if ($page > $total_pages) {
		    $page = $total_pages;
		}

		// Validation: Page to display can not be less than 1
		if ($page < 1) {
		    $page = 1;
		}

		// Calculate the position of the first record of the page to display
		$offset = ($page - 1) * $page_size;

		// Get the subset of records to be displayed from the array
		$data = array_slice($data, $offset, $page_size);

		return array( 'elements' => $data, 'page' => $page, 'total_pages' => $total_pages, 'total_elements' => $total_records );
	}

	/**
	 * Making the body part of the wp list table
	 */
	private static function zuwoo_elements_list() {
		$elements = RegisterWooElements::get_woo_elements();
		$active_els = (array) get_option('ziultimate_active_wcels');
		foreach ( $elements as $key => $element ) {
		?>
			<tr valign="top" <?php echo in_array($key, $active_els) ? 'class="active"' : 'class="inactive"'; ?>>
				<th scope="row" class="check-column">
					<input id="<?php echo $key; ?>" name="zuwooel[]" type="checkbox" value="<?php echo $key; ?>" <?php echo in_array($key, $active_els) ? 'checked="checked"' : ''; ?> />
				</th>
				<td class="plugin-title column-primary">
					<?php echo '<strong>' . $element['name'] . '</strong>'; ?>
				</td>
				<td style="text-align: right;">
					<a href="<?php echo $element['link']; ?>" target="_blank"><?php _e('Read Doc'); ?></a>
				</td>
			</tr>
		<?php
		}
	}

	/**
	 * Adding the settings link
	 */
	public function zu_add_settings_link( $links, $file ) {
		if ( $file === 'ziultimate/ziultimate.php' && current_user_can( 'install_plugins' ) ) {
			if ( current_filter() === 'plugin_action_links' ) {
				$url = admin_url( 'admin.php?page=' . $this->menu_slug );
			} else {
				$url = admin_url( '/network/admin.php?page=' . $this->menu_slug );
			}

			$settings = sprintf( '<a href="%s">%s</a>', $url, __( 'Settings' ) );
			array_unshift(
				$links,
				$settings
			);
		}

		return $links;
	}

	function zu_change_settings_ui() {
		$screen = get_current_screen();

		if( empty( $screen ) || ! strstr( $screen->id, $this->menu_slug ) )
			return;

		$class = 'zion-builder_page_' . $this->menu_slug;

	    echo "<style type=\"text/css\">
	    	.{$class} .wrap {
				background: #fff;
				padding: 0 40px 40px;
				max-width: 600px;
				margin-top: 23px;
				box-shadow: 0 0 18px #ddd;
			}

			.{$class} .wrap .heading{
				font-size: 1.8em;
			}

			.{$class} .nav-tab-wrapper, 
			.{$class} .wrap h2.nav-tab-wrapper {
				padding: 38px 35px 0;
				margin: 0 -40px 30px;
			}

			.{$class} .nav-tab {
				background-color: #fcfcfc;
				color: #777;
				display: flex; 
				align-items: center;
				padding: 10px 18px 6px;
				font-size: 12px;
			}

			.{$class} .about-wrap .nav-tab-active, 
			.{$class} .nav-tab-active, 
			.{$class} .nav-tab-active:hover {
				background-color: #fff;
				border-bottom-color: #fff;
				color: #333;
			}

			.{$class} table.widefat {
				border-left: 0;
				border-right: 0;
			}

			.{$class} .plugins .active td, 
			.{$class} .plugins .active th {
				background-color: #fafafa;
			}

			.{$class} .plugins .active th.check-column {
				border-left: 0;
			}

			.{$class} p.submit {
				padding: 0;
				margin: 0;
			}
			.{$class} .wp-list-table{
				margin-bottom: 10px;
			}
			.{$class} input[type=text],
			.{$class} input[type=email],
			.{$class} input[type=password],
			.{$class} input[type=url] {
				padding: 7px 12px;
			}
			.{$class} input[type=text],
			.{$class} input[type=email],
			.{$class} input[type=url],
			.{$class} input[type=password],
			.{$class} textarea {
				border: 1px solid #cdcdcd;
				color: #333;
				width: 100%;
			}
			.{$class} ::placeholder {
				color:#999;
			}
			.{$class} .license-activate-form {
				background-color: #fff;
				border: 1px solid #ccd0d4;
				padding: 5px 30px 15px;
			}
			.{$class} .license-activate-form .button {
				margin-top: 12px;
			}
	    </style>";
	}
}