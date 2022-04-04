<?php
namespace ZiUltimate\Admin;

use ZiUltimate\Requirements;
use ZionBuilder\Whitelabel;
use ZionBuilder\Plugin;
use ZionBuilder\Permissions;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class AdminMenuBar
 *
 * @package ZiUltimate\Admin
 */
class AdminMenuBar {
	function __construct() {
		add_action( 'admin_bar_menu', [ $this, 'zu_add_link_to_admin_bar' ], 999 );
	}

	public function zu_add_link_to_admin_bar( $admin_bar ) {
		$menu_name = __( 'ZiUltimate', "ziultimate" );
		$menu_slug = 'ziultimate';

		$zuwl = get_option('zuwl');
		if( $zuwl ) {
			$menu_name = ! empty( $zuwl['menu_name'] ) ? esc_html( $zuwl['menu_name'] ) : $menu_name;
			$menu_slug = ! empty( $zuwl['menu_slug'] ) ? esc_html( $zuwl['menu_slug'] ) : $menu_slug;
		}

		// ziultimate parent menu
		$admin_bar->add_node([
			'id'    => 'zu-parent-item',
			'title' => $menu_name,
			'href' 	=> esc_url( admin_url( 'admin.php?page=' . $menu_slug ) ),
		]);

		//* General Elements sub menu item
		$admin_bar->add_node([
			'parent' => 'zu-parent-item',
			'id'     => 'zu-general-elements',
			'title'  => esc_html__( 'General Elements', 'ziultimate' ),
			'href'   => esc_url( admin_url( 'admin.php?page=' . $menu_slug ) ),
		]);

		if( Requirements::passed_pro_plugin_requirements() ) {
			//* Templates sub menu item
			$this->generate_templates_menu( $admin_bar );

			//* Theme Builder sub menu item
			$admin_bar->add_node([
				'parent' => 'zu-parent-item',
				'id'     => Whitelabel::get_id() . '-theme-builder',
				'title'  => esc_html( Whitelabel::get_title() . ' Theme Builder' ),
				'href'   => esc_url( admin_url( 'admin.php?page=' . sprintf( '%s-theme-builder', WhiteLabel::get_id() ) ) ),
			]);
		}
	}

	private function generate_templates_menu( $admin_bar )
	{
		$templates 	= Plugin::instance()->templates->get_templates_by_type('template');
		$blocks 	= Plugin::instance()->templates->get_templates_by_type('block');

		if( ! empty( $templates ) || ! empty( $blocks ) ) 
		{
			$admin_bar->add_node([
				'parent' => 'zu-parent-item',
				'id'     => Whitelabel::get_id() . '-templates',
				'title'  => esc_html( Whitelabel::get_title() . ' Templates' ),
				'href'   => esc_url( admin_url( 'admin.php?page=' . Whitelabel::get_id() . '#/templates/template' ) ),
			]);
		}
		
		if( $templates ) 
		{
			foreach( $templates as $template ) {
				$admin_bar->add_node([
					'parent' => Whitelabel::get_id() . '-templates',
					'id'     => Whitelabel::get_id() . '-tpl-' . $template->ID,
					'title'  => esc_attr( $template->post_title ),
					'href'   => esc_url( admin_url( 'post.php?post_id=' . $template->ID . '&action=zion_builder_active' ) ),
				]);
			}
		}

		if( $blocks ) 
		{
			foreach( $blocks as $block ) {
				$admin_bar->add_node([
					'parent' => Whitelabel::get_id() . '-templates',
					'id'     => Whitelabel::get_id() . '-block-' . $block->ID,
					'title'  => esc_attr( $block->post_title ),
					'href'   => esc_url( admin_url( 'post.php?post_id=' . $block->ID . '&action=zion_builder_active' ) ),
				]);
			}
		}
	}
}