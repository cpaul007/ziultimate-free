<?php
namespace ZiUltimate\Elements\Breadcrumbs;

use ZiUltimate\UltimateElements;
use ZiUltimate\Admin\License;
use ZiUltimate\Utils;
use ZionBuilder\Options\BaseSchema;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class Breadcrumbs
 *
 * @package ZiUltimate\Elements
 */
class Breadcrumbs extends UltimateElements {
	
	public function get_type() {
		return 'zu_breadcrumbs';
	}

	public function get_name() {
		return __( 'Breadcrumbs', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'breadcrumbs', 'yoast', 'seopress' ];
	}

	public function get_category() {
		return $this->zu_elements_category();
	}

	public function options( $options ) {
		if( ! License::has_valid_license() ) {
			$title = $this->get_name();
			$description = 'Creating the breadcrumbs.';
			$options->add_option(
				'el',
				[
					'type' 		=> 'html',
					'content' 	=> self::getHTMLContent($title, $description)
				]
			);

			return;
		}

		$options->add_option(
			'note',
			[
				'type' 		=> 'html',
				'title' 	=> esc_html__( 'Note' ),
				'content' 	=> '<p>' . esc_html__('To display breadcrumbs, you need to enable breadcrumbs in respective SEO plugins settings page.', 'ziultimate') . 
								'</p><hr style="border:1px solid #e5e5e5;"/>'
			]
		);

		$options->add_option(
			'type',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('Source Type', 'ziultimate'),
				'description' => esc_html__('Select your active SEO plugin.', 'ziultimate'),
				'default' 	=> 'blocky',
				'options' 	=> [
					[
						'name' 	=> __('Blocksy Theme', 'ziultimate'),
						'id' 	=> 'blocky'
					],
					[
						'name' 	=> __('Breadcrumb NavXT', 'ziultimate'),
						'id' 	=> 'navxt'
					],
					[
						'name' 	=> __('Rankmath', 'ziultimate'),
						'id' 	=> 'rankmath'
					],
					[
						'name' 	=> __('SEOPress', 'ziultimate'),
						'id' 	=> 'seopress'
					],
					[
						'name' 	=> __('Yoast', 'ziultimate'),
						'id' 	=> 'yoast'
					]
				]
			]
		);

		$options->add_option(
			'font_size',
			[
				'title'			=> esc_html__( 'Font size', 'zionbuilder' ),
				'description'	=> esc_html__( 'Will not work with blocky breadcrumbs', 'ziultimate' ),
				'type'			=> 'number_unit',
				'min'			=> 0,
				'units'			=> BaseSchema::get_units(),
				'sync'			=> '_styles.wrapper.styles.%%RESPONSIVE_DEVICE%%.default.font-size',
				'show_responsive_buttons' => true
			]
		);

		$options->add_option(
			'align',
			[
				'type'                    => 'text_align',
				'title'                   => __( 'Align', 'zionbuilder' ),
				'description'             => __( 'Select the desired alignment.', 'zionbuilder' ),
				'sync'                    => '_styles.wrapper.styles.%%RESPONSIVE_DEVICE%%.default.text-align',
				'show_responsive_buttons' => true
			]
		);

		$options->add_option(
			'txt_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Text Color', 'ziultimate' ),
				'description'	=> esc_html__( 'Will not work with blocky breadcrumbs', 'ziultimate' ),
				'sync' 		=> '_styles.wrapper.styles.%%RESPONSIVE_DEVICE%%.default.color'
			]
		);

		$options->add_option(
			'link_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Link Color', 'ziultimate' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.links_style.styles.%%RESPONSIVE_DEVICE%%.default.color'
			]
		);

		$options->add_option(
			'link_hover_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Link Hover Color', 'ziultimate' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.links_style.styles.%%RESPONSIVE_DEVICE%%.:hover.color'
			]
		);

		$options->add_option(
			'sep_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Separator Color', 'ziultimate' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.separator_style.styles.%%RESPONSIVE_DEVICE%%.default.color',
				'dependency' => [
					[
						'option' 	=> 'type',
						'value' 	=> [ 'yoast', 'rankmath' ]
					]
				]
			]
		);

		$options->add_option(
			'sep_size',
			[
				'type' 		=> 'number_unit',
				'title' 	=> __( 'Separator Size', 'ziultimate' ),
				'width' 	=> 50,
				'min'		=> 0,
				'units'		=> BaseSchema::get_units(),
				'sync' 		=> '_styles.separator_style.styles.%%RESPONSIVE_DEVICE%%.default.font-size',
				'show_responsive_buttons' => true,
				'dependency' => [
					[
						'option' 	=> 'type',
						'value' 	=> [ 'yoast', 'rankmath' ]
					]
				]
			]
		);

		$options->add_option(
			'home_icon_size',
			[
				'type' 		=> 'slider',
				'title' 	=> __( 'Home Icon Size', 'ziultimate' ),
				'min'		=> 0,
				'max' 		=> 100,
				'step' 		=> 1,
				'default' 	=> 15,
				'content'	=> 'px',
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .ct-home-icon',
						'value' 	=> 'width: {{VALUE}}px'
					]
				],
				'show_responsive_buttons' => true,
				'dependency' => [
					[
						'option' 	=> 'type',
						'value' 	=> [ 'blocky' ]
					]
				]
			]
		);
	}

	/**
	 * Loading the Scripts
	 */
	public function enqueue_styles() {
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/Breadcrumbs/frontend.css' ) );
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'links_style',
			[
				'title'                   => esc_html__( 'Links style', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} a',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		// styles for yoast seo plugin
		if( function_exists( 'yoast_breadcrumb' ) ) {
			$this->register_style_options_element(
				'last_item_style',
				[
					'title'                   => esc_html__( 'Last Item', 'zionbuilder-pro' ),
					'selector'                => '{{ELEMENT}} #breadcrumbs .breadcrumb_last',
					'allow_custom_attributes' => false,
					'allow_class_assignments' => false,
				]
			);
		}

		// styles for rankmath seo plugin
		if( function_exists( 'rank_math_the_breadcrumbs' ) ) {
			$this->register_style_options_element(
				'last_item_rm',
				[
					'title'                   => esc_html__( 'Last Item', 'ziultimate' ),
					'selector'                => '{{ELEMENT}} .rank-math-breadcrumb .last',
					'allow_custom_attributes' => false,
					'allow_class_assignments' => false,
				]
			);
		}

		if( function_exists( 'seopress_display_breadcrumbs' ) ) {
			$this->register_style_options_element(
				'last_item_seopress',
				[
					'title'                   => esc_html__( 'Last Item', 'ziultimate' ),
					'selector'                => '{{ELEMENT}} .breadcrumb-item.active',
					'allow_custom_attributes' => false,
					'allow_class_assignments' => false,
				]
			);

			$this->register_style_options_element(
				'separator_seopress_style',
				[
					'title'                   => esc_html__( 'Separator', 'ziultimate' ),
					'selector'                => '{{ELEMENT}} .breadcrumb li::after',
					'allow_custom_attributes' => false,
					'allow_class_assignments' => false,
				]
			);
		}

		if( function_exists( 'yoast_breadcrumb' ) || function_exists( 'rank_math_the_breadcrumbs' ) || function_exists( 'bcn_display' ) ) {
			$this->register_style_options_element(
				'separator_style',
				[
					'title'                   => esc_html__( 'Separator', 'ziultimate' ),
					'selector'                => '{{ELEMENT}} .separator',
					'allow_custom_attributes' => false,
					'allow_class_assignments' => false,
				]
			);
		}
	}

	public function before_render( $options ) {
		$source = $options->get_value('type', 'blocky');
		$this->render_attributes->add( 'wrapper', 'class', 'zu-breadcrumbs zu-breadcrumbs-' . $source );
	}

	public function render( $options ) {
		$source = $options->get_value('type', 'blocky');

		if ( $source == 'blocky' && class_exists('Blocksy_Breadcrumbs_Builder') ) {
			if( UltimateElements::isBuilderEditor() ) {
				global $wp_actions, $post;
				$wp_actions[ 'wp' ] = 1;
				add_filter('blocksy:breadcrumbs:items-array', [ $this, 'blocksy_crumb_array'], 99 );
			}

			$breadcrumbs_builder = new \Blocksy_Breadcrumbs_Builder();
			echo $breadcrumbs_builder->render();
			if( UltimateElements::isBuilderEditor() ) {
				$wp_actions[ 'wp' ] = 0;
				remove_filter('blocksy:breadcrumbs:items-array', [ $this, 'blocksy_crumb_array'], 99 );
			}
		} elseif ( $source == 'navxt' && function_exists( 'bcn_display' ) ) {
			bcn_display();
		} elseif ( $source == 'rankmath' && function_exists( 'rank_math_the_breadcrumbs' ) ) {
			rank_math_the_breadcrumbs();
		} elseif ( $source == 'yoast' && function_exists( 'yoast_breadcrumb' ) ) {
			add_filter( 'wpseo_breadcrumb_separator', function( $separator ){
				return '<span class="separator">' . $separator . '</span>';
			});
			yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
		} elseif( $source == 'seopress' && function_exists( 'seopress_display_breadcrumbs' ) ) {
			if( UltimateElements::isBuilderEditor() ) { add_filter( 'seopress_pro_breadcrumbs_crumbs', [ $this, 'seopress_crumb_array'] ); }
			seopress_display_breadcrumbs();
			if( UltimateElements::isBuilderEditor() ) { remove_filter( 'seopress_pro_breadcrumbs_crumbs', [ $this, 'seopress_crumb_array'] ); }
		} else {
			//no data
		}
	}

	public function seopress_crumb_array( $crumbs ) {
		if( get_post_type() == 'zion_template') {
			$crumbs[] = [
				0 => get_the_title(),
				1 => get_permalink()
			];
		}

		return $crumbs;
	}

	public function blocksy_crumb_array($return) {
		if( get_post_type() == 'zion_template') {
			$return[] = [
				'name' 	=> get_the_title(),
				'url' 	=> '',
				'type' 	=> get_post_type()
			];
		}

		return $return;
	}
}