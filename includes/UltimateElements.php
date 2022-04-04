<?php

namespace ZiUltimate;

use ZionBuilder\Elements\Element;
use ZionBuilder\Options\BaseSchema;
use ZionBuilder\Plugin;
use ZionBuilder\Editor\Preview;
use ZiUltimate\Admin\License;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class UltimateElements extends Element {

	function get_label_text() {
		return 'ZU';
	}

	function get_label_color() {
		return '#868585';
	}

	function zu_elements_category() {
		return 'ziultimate';
	}

	function attach_padding_options( $options, $prefix, $selector, $exclude_options = array() ) {
		if( ! in_array( 'pad_top', $exclude_options )) {
			$options->add_option(
				$prefix . '_pad_top',
				[
					'type' 		=> 'number_unit',
					//'title'     => __( 'Top', 'ziultimate' ),
					'label-position' => 'left',
					'label-icon' => 'padding-top',
					'label-title' 	=> esc_html__( 'Padding Top', 'zionbuilder' ),
					'width' 	=> 50,
					'units' 	=> BaseSchema::get_units(),
					'css_style' => [
						[
							'selector' => $selector,
							'value'    => 'padding-top: {{VALUE}}',
						],
					],
				]
			);
		}

		if( ! in_array( 'pad_right', $exclude_options )) {
			$options->add_option(
				$prefix . '_pad_right',
				[
					'type' 		=> 'number_unit',
					//'title'     => __( 'Right', 'ziultimate' ),
					'label-position' => 'right',
					'label-icon' => 'padding-right',
					'label-title' 	=> esc_html__( 'Padding Right', 'zionbuilder' ),
					'width' 	=> 50,
					'units' 	=> BaseSchema::get_units(),
					'css_style' => [
						[
							'selector' => $selector,
							'value'    => 'padding-right: {{VALUE}}',
						],
					],
				]
			);
		}

		if( ! in_array( 'pad_bottom', $exclude_options )) {
			$options->add_option(
				$prefix . '_pad_bottom',
				[
					'type' 		=> 'number_unit',
					//'title'     => __( 'Bottom', 'ziultimate' ),
					'label-position' => 'left',
					'label-icon' => 'padding-bottom',
					'label-title' 	=> esc_html__( 'Padding Bottom', 'zionbuilder' ),
					'width' 	=> 50,
					'units' 	=> BaseSchema::get_units(),
					'css_style' => [
						[
							'selector' => $selector,
							'value'    => 'padding-bottom: {{VALUE}}',
						],
					],
				]
			);
		}

		if( ! in_array( 'pad_left', $exclude_options )) {
			$options->add_option(
				$prefix . '_pad_left',
				[
					'type' 		=> 'number_unit',
					//'title'     => __( 'Left', 'ziultimate' ),
					'label-position' => 'right',
					'label-icon' => 'padding-left',
					'label-title' 	=> esc_html__( 'Padding Left', 'zionbuilder' ),
					'width' 	=> 50,
					'units' 	=> BaseSchema::get_units(),
					'css_style' => [
						[
							'selector' => $selector,
							'value'    => 'padding-left: {{VALUE}}',
						],
					],
				]
			);
		}
	}

	function attach_margin_options( $options, $prefix, $selector, $exclude_options = array() ) {
		if( ! in_array( 'mrg_top', $exclude_options )) {
			$options->add_option(
				$prefix . '_pad_top',
				[
					'type' 		=> 'number_unit',
					//'title'     => __( 'Top', 'ziultimate' ),
					'label-position' => 'left',
					'label-icon' => 'margin-top',
					'label-title' 	=> esc_html__( 'Margin Top', 'zionbuilder' ),
					'width' 	=> 50,
					'units' 	=> BaseSchema::get_units(),
					'css_style' => [
						[
							'selector' => $selector,
							'value'    => 'margin-top: {{VALUE}}',
						],
					],
				]
			);
		}

		if( ! in_array( 'mrg_right', $exclude_options )) {
			$options->add_option(
				$prefix . '_pad_right',
				[
					'type' 		=> 'number_unit',
					//'title'     => __( 'Right', 'ziultimate' ),
					'label-position' => 'right',
					'label-icon' => 'margin-right',
					'label-title' 	=> esc_html__( 'Margin Right', 'zionbuilder' ),
					'width' 	=> 50,
					'units' 	=> BaseSchema::get_units(),
					'css_style' => [
						[
							'selector' => $selector,
							'value'    => 'margin-right: {{VALUE}}',
						],
					],
				]
			);
		}

		if( ! in_array( 'mrg_bottom', $exclude_options )) {
			$options->add_option(
				$prefix . '_pad_bottom',
				[
					'type' 		=> 'number_unit',
					//'title'     => __( 'Bottom', 'ziultimate' ),
					'label-position' => 'left',
					'label-icon' => 'margin-bottom',
					'label-title' 	=> esc_html__( 'Margin Bottom', 'zionbuilder' ),
					'width' 	=> 50,
					'units' 	=> BaseSchema::get_units(),
					'css_style' => [
						[
							'selector' => $selector,
							'value'    => 'margin-bottom: {{VALUE}}',
						],
					],
				]
			);
		}

		if( ! in_array( 'mrg_left', $exclude_options )) {
			$options->add_option(
				$prefix . '_pad_left',
				[
					'type' 		=> 'number_unit',
					//'title'     => __( 'Left', 'ziultimate' ),
					'label-position' => 'right',
					'label-icon' => 'margin-left',
					'label-title' 	=> esc_html__( 'Margin Left', 'zionbuilder' ),
					'width' 	=> 50,
					'units' 	=> BaseSchema::get_units(),
					'css_style' => [
						[
							'selector' => $selector,
							'value'    => 'margin-left: {{VALUE}}',
						],
					],
				]
			);
		}
	}


	function attach_typography_options( $options, $prefix, $selector, $exclude_options = array() ) {
		if( ! in_array( 'text_align', $exclude_options )) {
			$options->add_option(
				$prefix . '_text-align',
				[
					'type' 			=> 'text_align',
					'title' 		=> esc_html__( 'Align', 'zionbuilder' ),
					'description' 	=> esc_html__( 'Select the desired text align.', 'zionbuilder' ),
					'css_style' 	=> [
						[
							'selector' 	=> $selector,
							'value' 	=> 'text-align: {{VALUE}}'
						],
					]
				]
			);
		}

		if( ! in_array( 'font_family', $exclude_options )) {
			$options->add_option(
				$prefix . '_font-family',
				[
					'title' 		=> esc_html__( 'Font Family', 'zionbuilder' ),
					'type'			=> 'select',
					'data_source'	=> 'fonts',
					'width' 		=> 50,
					'style_type' 	=> 'font-select',
					'css_style' 	=> [
						[
							'selector' 	=> $selector,
							'value' 	=> 'font-family: {{VALUE}}'
						],
					]
				]
			);
		}

		if( ! in_array( 'font_weight', $exclude_options )) {
			$options->add_option(
				$prefix . '_font-weight',
				[
					'title' 		=> esc_html__( 'Font Weight', 'zionbuilder' ),
					'description' 	=> esc_html__( 'Font weight allows you to set the text thickness.', 'zionbuilder' ),
					'type' 			=> 'select',
					'default' 		=> '400',
					'width' 		=> 50,
					'options' 		=> [
						[
							'id'   => '100',
							'name' => '100',
						],
						[
							'id'   => '200',
							'name' => '200',
						],
						[
							'id'   => '300',
							'name' => '300',
						],
						[
							'id'   => '400',
							'name' => '400',
						],
						[
							'id'   => '500',
							'name' => '500',
						],
						[
							'id'   => '600',
							'name' => '600',
						],
						[
							'id'   => '700',
							'name' => '700',
						],
						[
							'id'   => '800',
							'name' => '800',
						],
						[
							'id'   => '900',
							'name' => '900',
						],
						[
							'id'   => 'bolder',
							'name' => esc_html__( 'Bolder', 'zionbuilder' ),
						],
						[
							'id'   => 'lighter',
							'name' => esc_html__( 'Lighter', 'zionbuilder' ),
						],
						[
							'id'   => 'inherit',
							'name' => esc_html__( 'Inherit', 'zionbuilder' ),
						],
						[
							'id'   => 'initial',
							'name' => esc_html__( 'Initial', 'zionbuilder' ),
						],
						[
							'id'   => 'unset',
							'name' => esc_html__( 'Unset', 'zionbuilder' ),
						],
					],
					'css_style' 	=> [
						[
							'selector' 	=> $selector,
							'value' 	=> 'font-weight: {{VALUE}}'
						],
					]
				]
			);
		}

		if( ! in_array( 'font_color', $exclude_options )) {
			$options->add_option(
				$prefix . '_color',
				[
					'title' => esc_html__( 'Font Color', 'zionbuilder' ),
					'type'  => 'colorpicker',
					'width' => 50,
					'css_style' 	=> [
						[
							'selector' 	=> $selector,
							'value' 	=> 'color: {{VALUE}}'
						],
					]
				]
			);
		}

		if( ! in_array( 'font_size', $exclude_options )) {
			$options->add_option(
				$prefix . '_font-size',
				[
					'title'       => esc_html__( 'Font size', 'zionbuilder' ),
					'description' => esc_html__( 'The font size option sets the size of the font in various units', 'zionbuilder' ),
					'type'        => 'number_unit',
					'width'       => 50,
					'min'         => 0,
					'units'       => BaseSchema::get_units(),
					'css_style' 	=> [
						[
							'selector' 	=> $selector,
							'value' 	=> 'font-size: {{VALUE}}'
						],
					]
				]
			);
		}
		
		if( ! in_array( 'line_height', $exclude_options )) {
			$options->add_option(
				$prefix . '_line-height',
				[
					'type'        => 'number_unit',
					'title'       => esc_html__( 'Line height', 'zionbuilder' ),
					'description' => esc_html__( 'Line height sets the distance between lines of text.', 'zionbuilder' ),
					'width'       => 50,
					'min'         => 0,
					'units'       => BaseSchema::get_units(),
					'css_style' 	=> [
						[
							'selector' 	=> $selector,
							'value' 	=> 'line-height: {{VALUE}}'
						],
					]
				]
			);
		}

		if( ! in_array( 'letter_spacing', $exclude_options )) {
			$options->add_option(
				$prefix . '_letter-spacing',
				[
					'type'        => 'number_unit',
					'title'       => esc_html__( 'Letter Spacing', 'zionbuilder' ),
					'description' => esc_html__( 'Letter spacings sets the width between letters.', 'zionbuilder' ),
					'width'       => 50,
					'units'       => BaseSchema::get_units(),
					'css_style' 	=> [
						[
							'selector' 	=> $selector,
							'value' 	=> 'letter-spacing: {{VALUE}}'
						],
					]
				]
			);
		}

		if( ! in_array( 'text_decoration', $exclude_options )) {
			$options->add_option(
				$prefix . '_text-decoration',
				[
					'type'          => 'checkbox_group',
					'title'      	=> esc_html__( 'Text Decoration', 'zionbuilder' ),
					'direction'     => 'horizontal',
					'display-style' => 'buttons',
					'width'         => 50,
					'columns'       => 3,
					'options'       => [
						[
							'icon' => 'italic',
							'id'   => 'italic',
						],
						[
							'icon' => 'underline',
							'id'   => 'underline',
						],
						[
							'icon' => 'strikethrough',
							'id'   => 'line-through',
						],
					],
					'css_style' 	=> [
						[
							'selector' 	=> $selector,
							'value' 	=> 'text-decoration: {{VALUE}}'
						],
					]
				]
			);
		}

		if( ! in_array( 'text_transform', $exclude_options )) {
			$options->add_option(
				$prefix . '_text-transform',
				[
					'type'    => 'custom_selector',
					'title'      	=> esc_html__( 'Text Transform', 'zionbuilder' ),
					'columns' => 3,
					'width'   => 50,
					'options' => [
						[
							'id'   => 'uppercase',
							'icon' => 'uppercase',
							'name' => esc_html__( 'uppercase', 'zionbuilder' ),
						],
						[
							'id'   => 'lowercase',
							'icon' => 'lowercase',
							'name' => esc_html__( 'lowercase', 'zionbuilder' ),
						],
						[
							'id'   => 'capitalize',
							'icon' => 'capitalize',
							'name' => esc_html__( 'capitalize', 'zionbuilder' ),
						],
					],
					'css_style' 	=> [
						[
							'selector' 	=> $selector,
							'value' 	=> 'text-transform: {{VALUE}}'
						],
					]
				]
			);
		}
	}

	public static function elVal() {
		$elval = get_option('ziultimate_el');
		if( ! empty( $elval ) && License::has_valid_license() ) {
			return $elval;
		}
	}

	public static function getHTMLContent($title, $description) {
		ob_start();
		?>
		<div class="znpb-option__upgrade-to-pro">
			<div class="znpb-option__upgrade-to-pro-container">
				<h4><?php echo $title; ?></h4>
				<p><?php echo $description; ?></p>
				<a href="<?php echo add_query_arg( [ 'tab' => 'license', 'page' => 'ziultimate' ],admin_url('admin.php') ); ?>" target="_blank">
				<?php _e( 'Click here to activate you license' ); ?>
				</a>
				<a href="https://ziultimate.com/" target="_blank" class="znpb-button znpb-get-pro__cta znpb-button--secondary znpb-option__upgrade-to-pro-button"
				><?php _e( 'Purchase Now' ); ?></a>
			</div>
		</div>
		<?php

		return ob_get_clean();
	}

	// Get all forms of Contact Form 7 plugin
	public function getCF7Forms() {
		$options = [
			[
				'id' 	=> -1,
				'name' 	=> esc_html__( 'Select a form', 'ziultimate' ),
			]
		];

		if ( class_exists( 'WPCF7' ) ) {
			$args = array(
				'posts_per_page' 	=> -1,
				'orderby' 			=> 'date',
				'order' 			=> 'DESC',
				'post_type' 		=> 'wpcf7_contact_form',
				'post_status' 		=> 'publish'
			);

			$forms = new \WP_Query($args);
			if( $forms->have_posts() ) {
				$i=1;
				foreach ($forms->posts as $form){
					$options[$i]['id'] = $form->ID;
					$options[$i]['name'] = wp_kses_post($form->post_title );
					$i++;
				}
			} else {
				$options[0]['id'] = -1;
				$options[0]['name'] = esc_html__( 'No forms found!', 'ziultimate' );
			}
		}

		return $options;
	}

	public function getFluentForms() {
		$options = [
			[
				'id' => -1,
				'name' => esc_html__('Select a form', 'ziultimate')
			]
		];

		if ( function_exists( 'wpFluentForm' ) ) {
			global $wpdb;
			$result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fluentform_forms" );
			if ( $result ) {
				$i=1;
				foreach ( $result as $form ) {
					$options[$i]['id'] = $form->id;
					$options[$i]['name'] = esc_html__( $form->title );
					$i++;
				}
			} else {
				$options[0]['id'] = -1;
				$options[0]['name'] = esc_html__( 'No forms found!', 'ziultimate' );
			}
		}

		return $options;
	}

	public function getGravityForms() {
		$options = [
			[
				'id' => -1,
				'name' => esc_html__('Select a form', 'ziultimate')
			]
		];

		if ( class_exists( 'GFForms' ) ) {
			$forms = \RGFormsModel::get_forms( null, 'title' );
			if ( count( $forms ) ) {
				$i=1;
				foreach ( $forms as $form ) {
					$options[ $i ]['id'] = $form->id;
					$options[ $i ]['name'] = $form->title;
					$i++;
				}
			} else {
				$options[0]['id'] = -1;
				$options[0]['name'] = esc_html__( 'No forms found!', 'ziultimate' );
			}
		}

		return $options;
	}

	public function getWPMenus() {
		$get_menus = wp_get_nav_menus();
		$options = [
			[
				'id' 	=> 'sel',
				'name' 	=> __('Select Menu', "ziultimate")
			]
		];

		if ( $get_menus ) {
			$i = 1;
			foreach( $get_menus as $menu ) {
				$options[$i]['id'] = $menu->slug;
				$options[$i]['name'] = $menu->name;

				$i++;
			}
		} else {
			$options = [
				[
					'id' 	=> 'nomenu',
					'name' 	=> __('No Menus Found', "ziultimate")
				]
			];
		}

		return $options;
	}

	public function getTaxonomies() {
		$options 	= [ [ 'id' => 'category', 'name' => __('Category') ] ];
		$taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ) );

		if ( ! empty( $taxonomies ) ) {
			$i = 1;
			foreach ( $taxonomies as $taxonomy ) {
				$options[$i]['id'] = $taxonomy;
				$options[$i]['name'] = get_taxonomy( $taxonomy )->labels->name;

				$i++;
			}
		}

		return $options;
	}
	
	public function get_thumbnail_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = $img_sizes =array();

		foreach( get_intermediate_image_sizes() as $s ) {
			$sizes[ $s ] = array( 0, 0 );
			if( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[ $s ][0] = get_option( $s . '_size_w' );
				$sizes[ $s ][1] = get_option( $s . '_size_h' );
			} else {
				if( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
					$sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'], );
			}
		}

		$i = 0;
		foreach( $sizes as $size => $atts ) {
			$size_title = ucwords(str_replace("-"," ", $size));
			$img_sizes[$i]['id'] = $size;
			$img_sizes[$i]['name'] =  $size_title . ' (' . implode( 'x', $atts ) . ')';
			$i++;
		}

		$img_sizes[$i]['id'] = 'full';
		$img_sizes[$i]['name'] =  esc_html__('Full');


		return $img_sizes;
	}

	public static function isBuilderEditor() {
		if( isset( $_SERVER['HTTP_REFERER'] ) && strstr( $_SERVER['HTTP_REFERER'], 'zion_builder_active' ) && ! isset($_GET['preview']) ) {
			return true;
		} elseif( Plugin::$instance->editor->preview->is_preview_mode() ) {
			return true;
		} else {
			return false;
		}
	}
}