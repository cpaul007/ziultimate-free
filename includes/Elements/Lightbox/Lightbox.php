<?php
namespace ZiUltimate\Elements\Lightbox;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;
use ZionBuilder\Options\BaseSchema;
use ZionBuilder\Options\Schemas\StyleOptions;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class Lightbox
 *
 * @package ZiUltimate\Elements
 */
class Lightbox extends UltimateElements {
	
	public function get_type() {
		return 'zu_lightbox';
	}

	public function get_name() {
		return __( 'Lightbox', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'lightbox', 'modal', 'popup' ];
	}

	/*public function get_label() {
		return [
			'text'  => $this->get_label_text(),
			'color' => $this->get_label_color(),
		];
	}*/

	public function get_category() {
		return $this->zu_elements_category();
	}

	public function is_wrapper() {
		return true;
	}

	/**
	 * Registering the options
	 * 
	 * @return void
	 */
	public function options( $options ) {
		if( ! License::has_valid_license() ) {
			$title = $this->get_name();
			$description = 'You can build the modal for static or repeater content.';
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
			'preview',
			[
				'type' 		=> 'custom_selector',
				'title'		=> esc_html__('Preview', 'ziultimate'),
				'default' 	=> 'open',
				'options' 	=> [
					[
						'name' 	=> esc_html__('Open'),
						'id' 	=> 'open'
					],
					[
						'name' 	=> esc_html__('Close'),
						'id' 	=> 'close'
					],
					[
						'name' 	=> esc_html__('Hide', 'ziultimate'),
						'id' 	=> 'hidden'
					]
				],
				'render_attribute' => [
					[
						'attribute' => 'class',
						'value'     => 'zu-lightbox--{{VALUE}}',
					],
				],
			]
		);

		$options->add_option(
			'trigger_type',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__( 'Eventlistener', 'ziultimate'),
				'default' 	=> 'click',
				'options' 	=> [
					[
						'name' 	=> esc_html__('click'),
						'id' 	=> 'click'
					],
					[
						'name' 	=> esc_html__('hover'),
						'id' 	=> 'mouseover'
					]
				]
			]
		);

		$options->add_option(
			'trigger_selector',
			[
				'type' 			=> 'text',
				'title' 		=> __('Trigger Selector', 'ziultimate'),
				'description' 	=> esc_html__('You can enter multiple selectors with comma.', 'ziultimate'),
				'dynamic'		=> [
					'enabled' => true
				]
			]
		);

		$options->add_option(
			'disable_scroll',
			[
				'type' 		=> 'custom_selector',
				'title'		=> esc_html__('Disable Scroll', 'ziultimate'),
				'default' 	=> 'yes',
				'options' 	=> [
					[
						'name' 	=> esc_html__('Yes'),
						'id' 	=> 'yes'
					],
					[
						'name' 	=> esc_html__('No'),
						'id' 	=> 'no'
					]
				]
			]
		);

		$options->add_option(
			'divider',
			[
				'type' 		=> 'html',
				'title' 	=> '',
				'content' 	=> ''
			]
		);

		$options->add_option(
			'lb_container',
			[
				'type' 		=> 'html',
				'title' 	=> esc_html__('Lightbox Container', 'ziultimate'),
				'content' 	=> '<hr style="border: 1px solid #e5e5e5"/>'
			]
		);

		$options->add_option(
			'lb_bg_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__('Background Color'),
				'sync' 		=> '_styles.lightbox_styles.styles.%%RESPONSIVE_DEVICE%%.default.background-color'
			]
		);

		$options->add_option(
			'width',
			[
				'type' 		=> 'number_unit',
				'min' 		=> 0,
				'units' 	=> [
					'px',
					'pt',
					'rem',
					'vh',
					'vw',
					'%',
				],
				'width' 	=> 50,
				'title' 	=> esc_html__('Width'),
				'sync' 		=> '_styles.lightbox_styles.styles.%%RESPONSIVE_DEVICE%%.default.width',
				'show_responsive_buttons' => true,
			]
		);

		$options->add_option(
			'height',
			[
				'type' 		=> 'number_unit',
				'min' 		=> 0,
				'units' 	=> StyleOptions::get_units(),
				'width' 	=> 50,
				'title' 	=> esc_html__('Height'),
				'sync' 		=> '_styles.lightbox_styles.styles.%%RESPONSIVE_DEVICE%%.default.height',
				'show_responsive_buttons' => true,
			]
		);

		$options->add_option(
			'zuel_lb',
			[
				'type' 		=> 'text',
				'default' 	=> 'zu' . self::elVal(),
				'css_class' => 'znpb-checkbox-switch-wrapper__checkbox'
			]
		);

		$options->add_option(
			'hr_position',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__('Align Items', 'ziultimate'),
				'default' 	=> 'center',
				'options' 	=> [
					[
						'id'   => 'flex-start',
						'name' => 'flex-start',
					],
					[
						'id'   => 'center',
						'name' => 'center',
					],
					[
						'id'   => 'flex-end',
						'name' => 'flex-end',
					],
				],
				'sync' => '_styles.wrapper.styles.%%RESPONSIVE_DEVICE%%.default.align-items',
			]
		);

		$options->add_option(
			'vr_position',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__('Justify Content', 'ziultimate'),
				'default' 	=> 'center',
				'options' 	=> [
					[
						'id'   => 'flex-start',
						'name' => 'flex-start',
					],
					[
						'id'   => 'center',
						'name' => 'center',
					],
					[
						'id'   => 'flex-end',
						'name' => 'flex-end',
					],
				],
				'sync' => '_styles.wrapper.styles.%%RESPONSIVE_DEVICE%%.default.justify-content',
			]
		);

		$options->add_option(
			'divider_2',
			[
				'type' 		=> 'html',
				'title' 	=> '',
				'content' 	=> ''
			]
		);


		/******************
		 * Backdrop group
		 ******************/
		$backdrop = $options->add_group(
			'backdrop',
			[
				'type' 		=> 'accordion_menu',
				'title' 	=> esc_html__( 'Backdrop Options', 'ziultimate' ),
			]
		);

		$backdrop->add_option(
			'disable_backdrop',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Disable backdrop', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

		$backdrop->add_option(
			'backdrop_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__('Backdrop Color'),
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}.zu-lightbox--has-backdrop',
						'value' 	=> 'background: {{VALUE}}'
					]
				]
			]
		);

		$backdrop->add_option(
			'backdrop_close_lightbox',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__( 'Close lightbox when clicking on the backdrop?', 'ziultimate'),
				'default' 	=> 'yes',
				'options' 	=> [
					[
						'name' 	=> esc_html__('Yes'),
						'id' 	=> 'yes'
					],
					[
						'name' 	=> esc_html__('no'),
						'id' 	=> 'no'
					]
				]
			]
		);


		/*******************
		 * Close Button
		 ******************/
		$icon = $options->add_group(
			'closebtn_group',
			[
				'type' 		=> 'accordion_menu',
				'title' 	=> esc_html__('Close/FullScreen Button Options', 'ziultimate'),
			]
		);

		$icon->add_option(
			'button_position',
			[
				'type' 		=> 'html',
				'title' 	=> esc_html__('Close Button Position', 'ziultimate'),
				'content' 	=> '<hr style="border: 1px solid #e5e5e5"/>'
			]
		);

		$icon->add_option(
			'top',
			[
				'type'        => 'number_unit',
				'title'       => __( 'Top', 'zionbuilder-pro' ),
				'default' 	  => '7px',
				'width'       => '25',
				'units'       => [
					'px',
					'pt',
					'rem',
					'vh',
					'%',
					'auto',
				],
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}} .zu-lightbox-close",
						'value' 	=> 'top: {{VALUE}}'
					]
				]
			]
		);

		$icon->add_option(
			'bottom',
			[
				'type'        => 'number_unit',
				'title'       => __( 'Bottom', 'zionbuilder-pro' ),
				'placeholder' => '0px',
				'width'       => '25',
				'units'       => [
					'px',
					'pt',
					'rem',
					'vh',
					'%',
					'auto',
				],
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}} .zu-lightbox-close",
						'value' 	=> 'bottom: {{VALUE}}'
					]
				]
			]
		);

		$icon->add_option(
			'left',
			[
				'type'        => 'number_unit',
				'title'       => __( 'Left', 'zionbuilder-pro' ),
				'placeholder' => '0px',
				'width'       => '25',
				'units'       => [
					'px',
					'pt',
					'rem',
					'vh',
					'%',
					'auto',
				],
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}} .zu-lightbox-close",
						'value' 	=> 'left: {{VALUE}}'
					]
				]
			]
		);

		$icon->add_option(
			'right',
			[
				'type'        => 'number_unit',
				'title'       => __( 'Right', 'zionbuilder-pro' ),
				'default' 	  => '7px',
				'width'       => '25',
				'units'       => [
					'px',
					'pt',
					'rem',
					'vh',
					'%',
					'auto',
				],
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}} .zu-lightbox-close",
						'value' 	=> 'right: {{VALUE}}'
					]
				]
			]
		);
		
		$icon->add_option(
			'closebtn_option',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__('Close Button', 'ziultimate'),
				'default' 	=> 'inbuilt',
				'options' 	=> [
					[
						'name' 	=> esc_html__('Inbuilt'),
						'id' 	=> 'inbuilt'
					],
					[
						'name' 	=> esc_html__('Custom'),
						'id' 	=> 'custom'
					]
				]
			]
		);

		$icon->add_option(
			'close_icon',
			[
				'type'       => 'icon_library',
				'id'         => 'icon',
				'default'    => [
					'family'  => 'Font Awesome 5 Free Solid',
					'name'    => 'times',
					'unicode' => 'uf00d',
				],
				'dependency' 	=> [
					[
						'option' 	=> 'closebtn_option',
						'value' 	=> ['inbuilt']
					]
				]
			]
		);

		$icon->add_option(
			'icon_bg_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Background' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.icon_wrapper_styles.styles.%%RESPONSIVE_DEVICE%%.default.background-color',
				'dependency' 	=> [
					[
						'option' 	=> 'closebtn_option',
						'value' 	=> ['inbuilt']
					]
				]
			]
		);

		$icon->add_option(
			'icon_bg_hcolor',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Hover Background' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.icon_wrapper_styles.styles.%%RESPONSIVE_DEVICE%%.:hover.background-color',
				'dependency' 	=> [
					[
						'option' 	=> 'closebtn_option',
						'value' 	=> ['inbuilt']
					]
				]
			]
		);

		$icon->add_option(
			'icon_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Color' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.icon_styles.styles.%%RESPONSIVE_DEVICE%%.default.color',
				'dependency' => [
					[
						'option' 	=> 'closebtn_option',
						'value' 	=> ['inbuilt']
					]
				]
			]
		);

		$icon->add_option(
			'icon_hcolor',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Hover Color' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.icon_styles.styles.%%RESPONSIVE_DEVICE%%.:hover.color',
				'dependency' => [
					[
						'option' 	=> 'closebtn_option',
						'value' 	=> ['inbuilt']
					]
				]
			]
		);

		$icon->add_option(
			'icon_size',
			[
				'title' 	=> esc_html__( 'Size', 'zionbuilder' ),
				'type' 		=> 'number_unit',
				'units' 	=> BaseSchema::get_units(),
				'sync' 		=> '_styles.icon_styles.styles.%%RESPONSIVE_DEVICE%%.default.font-size',
				'dependency' => [
					[
						'option' 	=> 'closebtn_option',
						'value' 	=> ['inbuilt']
					]
				]
			]
		);

		$icon->add_option(
			'custom_cb_selector',
			[
				'type' 			=> 'text',
				'title' 		=> __('Close Button Selector', 'ziultimate'),
				'placeholder' 	=> esc_html__('Enter ID or CSS class name.', 'ziultimate'),
				'dynamic'		=> [
					'enabled' => true
				],
				'dependency' => [
					[
						'option' 	=> 'closebtn_option',
						'value' 	=> ['custom']
					]
				]
			]
		);


		$icon->add_option(
			'divider_3',
			[
				'type' 		=> 'html',
				'title' 	=> '',
				'content' 	=> ''
			]
		);

		/*******************
		 * FullScreen Button
		 ******************/
		$icon->add_option(
			'fs_section',
			[
				'type' 		=> 'html',
				'title' 	=> esc_html__('FullScreen Button', 'ziultimate'),
				'content' 	=> '<hr style="border: 1px solid #e5e5e5"/><br/>It will turn off when slider enabled.'
			]
		);

		$icon->add_option(
			'has_fullscreen_button',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Add FullScreen Button', 'ziultimate' ),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

		$icon->add_option(
			'fs_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Color' ),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}} .zu-lightbox-fullscreen",
						'value' 	=> 'color: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'has_fullscreen_button',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$icon->add_option(
			'fs_hcolor',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Hover Color' ),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}} .zu-lightbox-fullscreen:hover ",
						'value' 	=> 'color: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'has_fullscreen_button',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$icon->add_option(
			'fs_size',
			[
				'type' 		=> 'number_unit',
				'title' 	=> esc_html__( 'Size', 'zionbuilder' ),
				'width' 	=> 50,
				'units' 	=> BaseSchema::get_units(),
				'responsive_options' => true,
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}} .zu-lightbox-fullscreen",
						'value' 	=> 'font-size: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'has_fullscreen_button',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$icon->add_option(
			'fs_gap',
			[
				'type' 		=> 'number_unit',
				'title' 	=> esc_html__( 'Gap', 'zionbuilder' ),
				'width' 	=> 50,
				'default' 	=> '14px',
				'units' 	=> ['px', 'rem', 'pt', 'vh', '%'],
				'responsive_options' => true,
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}} .zu-lightbox-fullscreen",
						'value' 	=> 'margin-right: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'has_fullscreen_button',
						'value' 	=> [ true ]
					]
				]
			]
		);

		/******************
		 * Slider Group
		 ******************/
		$slider = $options->add_group(
			'slider_group',
			[
				'type'      => 'accordion_menu',
				'title'     => __( 'Slider Options', 'zionbuilder' )
			]
		);

		$slider->add_option(
			'enabled_slider',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Enable slider', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

		$slider->add_option(
			'used_repeater',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__('Are you using the repeater?', 'ziultimate'),
				'default' 	=> 'yes',
				'options' 	=> [
					[
						'name' 	=> esc_html__('Yes'),
						'id' 	=> 'yes'
					],
					[
						'name' 	=> esc_html__('No'),
						'id' 	=> 'no'
					]
				],
				'dependency' 	=> [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'outer_container',
			[
				'type' 		=> 'text',
				'title' 	=> esc_html__('Outer Container Selector', 'ziultimate'),
				'placeholder' => esc_html__('Enter the CSS class name or ID', 'ziultimate'),
				'dependency' => [
					[
						'option' 	=> 'used_repeater',
						'value' 	=> [ 'no' ]
					],
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'divider_2',
			[
				'type' 		=> 'html',
				'title' 	=> '',
				'content' 	=> '',
				'dependency' 	=> [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'prev_text',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Prev Button', 'ziultimate'),
				'placeholder' 	=> esc_html__('Prev'),
				'width' 		=> 50,
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'next_text',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Next Button', 'ziultimate'),
				'placeholder' 	=> esc_html__('Next'),
				'width' 		=> 50,
				'dependency' 	=> [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'prev_icon',
			[
				'type' 		=> 'icon_library',
				'id' 		=> 'prev_icon',
				'title' 	=> esc_html__('Icon', 'ziultimate'),
				'width' 	=> 50,
				'default'    => [
					'family'  => 'Font Awesome 5 Free Solid',
					'name'    => 'chevron-left',
					'unicode' => 'uf053',
				],
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'next_icon',
			[
				'type' 		=> 'icon_library',
				'id' 		=> 'next_icon',
				'title' 	=> esc_html__('Icon', 'ziultimate'),
				'width' 	=> 50,
				'default'   => [
					'family'  => 'Font Awesome 5 Free Solid',
					'name'    => 'chevron-right',
					'unicode' => 'uf054',
				],
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'btn_width',
			[
				'title' 	=> esc_html__( 'Width', 'zionbuilder' ),
				'type' 		=> 'number_unit',
				'min' 		=> 0,
				'default' 	=> '35px',
				'units' 	=> [
					'px',
					'pt',
					'rem',
					'vh',
					'vw',
					'%',
				],
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .nav-buttons',
						'value' 	=> 'width: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'btn_height',
			[
				'title' 	=> esc_html__( 'Height', 'zionbuilder' ),
				'type' 		=> 'number_unit',
				'min' 		=> 0,
				'default' 	=> '65px',
				'units' 	=> StyleOptions::get_units(),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .nav-buttons',
						'value' 	=> 'height: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'btn_text_size',
			[
				'title' 	=> esc_html__( 'Text Size', 'zionbuilder' ),
				'type' 		=> 'number_unit',
				'units' 	=> BaseSchema::get_units(),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .nav-buttons .btn-text',
						'value' 	=> 'font-size: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'btn_icon_size',
			[
				'title' 	=> esc_html__( 'Icon Size', 'zionbuilder' ),
				'type' 		=> 'number_unit',
				'units' 	=> BaseSchema::get_units(),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .nav-buttons .btn-icon',
						'value' 	=> 'font-size: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'ti_gap',
			[
				'title' 	=> esc_html__( 'Gap between Icon & Text', 'zionbuilder' ),
				'type' 		=> 'number_unit',
				'units' 	=> BaseSchema::get_units(),
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .nav-buttons .prev-text',
						'value' 	=> 'margin-left: {{VALUE}}'
					],
					[
						'selector' 	=> '{{ELEMENT}} .nav-buttons .next-text',
						'value' 	=> 'margin-right: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'btns_divider',
			[
				'type' 		=> 'html',
				'title' 	=> '',
				'content' 	=> '',
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		/*****************
		 * Colors section
		 ***************/
		$slider->add_option(
			'btn_styles',
			[
				'type' 		=> 'html',
				'title' 	=> esc_html__('Buttons Colors', 'ziultimate'),
				'content' 	=> '<hr style="border: 1px solid #e5e5e5"/>',
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'btn_bg_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Background' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.nav_styles.styles.%%RESPONSIVE_DEVICE%%.default.background-color',
				'dependency' 	=> [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'btn_bg_hcolor',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Hover Background' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.nav_styles.styles.%%RESPONSIVE_DEVICE%%.:hover.background-color',
				'dependency' 	=> [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'btn_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Color' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.nav_styles.styles.%%RESPONSIVE_DEVICE%%.default.color',
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'btn_hcolor',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Hover Color' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.nav_styles.styles.%%RESPONSIVE_DEVICE%%.:hover.color',
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		/*****************
		 * Buttons Position
		 ***************/
		$slider->add_option(
			'prev_btn_pos',
			[
				'type' 		=> 'html',
				'title' 	=> esc_html__('Previous Button Position', 'ziultimate'),
				'content' 	=> '<hr style="border: 1px solid #e5e5e5"/>',
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'prev_pos_top',
			[
				'type' 			=> 'number_unit',
				'title' 		=> __( 'Top' ),
				'units' 		=> BaseSchema::get_units(),
				'width' 		=> 25,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .nav-buttons.prev',
						'value'    => 'top: {{VALUE}}',
					],
				],
			]
		);

		$slider->add_option(
			'prev_pos_btm',
			[
				'type' 			=> 'number_unit',
				'title' 		=> __( 'Bottom' ),
				'units' 		=> BaseSchema::get_units(),
				'width' 		=> 25,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .nav-buttons.prev',
						'value'    => 'bottom: {{VALUE}}',
					],
				],
			]
		);

		$slider->add_option(
			'prev_pos_left',
			[
				'type' 			=> 'number_unit',
				'title' 		=> __( 'Left' ),
				'units' 		=> BaseSchema::get_units(),
				'width' 		=> 25,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .nav-buttons.prev',
						'value'    => 'left: {{VALUE}}',
					],
				],
			]
		);

		$slider->add_option(
			'prev_pos_right',
			[
				'type' 			=> 'number_unit',
				'title' 		=> __( 'Right' ),
				'units' 		=> BaseSchema::get_units(),
				'width' 		=> 25,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .nav-buttons.prev',
						'value'    => 'right: {{VALUE}}',
					],
				],
			]
		);

		$slider->add_option(
			'next_btn_pos',
			[
				'type' 		=> 'html',
				'title' 	=> esc_html__('Next Button Position', 'ziultimate'),
				'content' 	=> '<hr style="border: 1px solid #e5e5e5"/>',
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'next_pos_top',
			[
				'type' 			=> 'number_unit',
				'title' 		=> __( 'Top' ),
				'units' 		=> BaseSchema::get_units(),
				'width' 		=> 25,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .nav-buttons.next',
						'value'    => 'top: {{VALUE}}',
					],
				],
			]
		);

		$slider->add_option(
			'next_pos_btm',
			[
				'type' 			=> 'number_unit',
				'title' 		=> __( 'Bottom' ),
				'units' 		=> BaseSchema::get_units(),
				'width' 		=> 25,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .nav-buttons.next',
						'value'    => 'bottom: {{VALUE}}',
					],
				],
			]
		);

		$slider->add_option(
			'next_pos_left',
			[
				'type' 			=> 'number_unit',
				'title' 		=> __( 'Left' ),
				'units' 		=> BaseSchema::get_units(),
				'width' 		=> 25,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .nav-buttons.next',
						'value'    => 'left: {{VALUE}}',
					],
				],
			]
		);

		$slider->add_option(
			'next_pos_right',
			[
				'type' 			=> 'number_unit',
				'title' 		=> __( 'Right' ),
				'units' 		=> BaseSchema::get_units(),
				'width' 		=> 25,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .nav-buttons.next',
						'value'    => 'right: {{VALUE}}',
					],
				],
			]
		);

		$slider->add_option(
			'animation_divider',
			[
				'type' 		=> 'html',
				'title' 	=> '',
				'content' 	=> '',
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		/*******************
		 * Animation section
		 ******************/
		$slider->add_option(
			'animation_section',
			[
				'type' 		=> 'html',
				'title' 	=> esc_html__('Animation', 'ziultimate'),
				'content' 	=> '<hr style="border: 1px solid #e5e5e5"/>',
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'slide_duration',
			[
				'type' 		=> 'slider',
				'title' 	=> esc_html__('Duration', 'ziultimate'),
				'content' 	=> 'ms',
				'min' 		=> 0,
				'max' 		=> 90000,
				'default' 	=> 750,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--zulb-animation-duration: {{VALUE}}ms'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$slider->add_option(
			'slide_delay',
			[
				'type' 		=> 'slider',
				'title' 	=> esc_html__('Delay', 'ziultimate'),
				'content' 	=> 'ms',
				'min' 		=> 0,
				'max' 		=> 90000,
				'default' 	=> 100,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--zulb-animation-delay: {{VALUE}}ms'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'enabled_slider',
						'value' 	=> [ true ]
					]
				]
			]
		);



		/******************
		 * Animation Group
		 ******************/
		$animation_group = $options->add_group(
			'animation-group',
			[
				'type'      => 'accordion_menu',
				'title'     => __( 'Animations options', 'zionbuilder' )
			]
		);

		$animation_group->add_option(
			'_appear_animation',
			[
				'type'             => 'radio_image',
				'description'      => __( 'Set the desired appear animation when the element becomes visible in the viewport.' ),
				'title'            => __( 'Appear animation', 'zionbuilder' ),
				'default'          => '',
				'columns'          => 3,
				'use_search'       => true,
				'search_text'      => __( 'Search animation', 'zionbuilder' ),
				'options'          => [
					[
						'name'  => esc_html__( 'none', 'zionbuilder' ),
						'value' => '',
						'class' => 'znpb-no-animation-placeholder',
					],
					[
						'name'  => esc_html__( 'bounce', 'zionbuilder' ),
						'value' => 'bounce',
						'class' => 'bounce',
					],
					[
						'name'  => esc_html__( 'pulse', 'zionbuilder' ),
						'value' => 'pulse',
						'class' => 'pulse',
					],
					[
						'name'  => esc_html__( 'rubber Band', 'zionbuilder' ),
						'value' => 'rubberBand',
						'class' => 'rubberBand',
					],
					[
						'name'  => esc_html__( 'shake', 'zionbuilder' ),
						'value' => 'shake',
						'class' => 'shake',
					],
					[
						'name'  => esc_html__( 'head Shake', 'zionbuilder' ),
						'value' => 'headShake',
						'class' => 'headShake',
					],
					[
						'name'  => esc_html__( 'swing', 'zionbuilder' ),
						'value' => 'swing',
						'class' => 'swing',
					],
					[
						'name'  => esc_html__( 'tada', 'zionbuilder' ),
						'value' => 'tada',
						'class' => 'tada',
					],
					[
						'name'  => esc_html__( 'wobble', 'zionbuilder' ),
						'value' => 'wobble',
						'class' => 'wobble',
					],
					[
						'name'  => esc_html__( 'jello', 'zionbuilder' ),
						'value' => 'jello',
						'class' => 'jello',
					],
					[
						'name'  => esc_html__( 'heart Beat', 'zionbuilder' ),
						'value' => 'heartBeat',
						'class' => 'heartBeat',
					],
					[
						'name'  => esc_html__( 'bounce In', 'zionbuilder' ),
						'value' => 'bounceIn',
						'class' => 'bounceIn',
					],
					[
						'name'  => esc_html__( 'bounce In Down', 'zionbuilder' ),
						'value' => 'bounceInDown',
						'class' => 'bounceInDown',
					],
					[
						'name'  => esc_html__( 'bounce In Left', 'zionbuilder' ),
						'value' => 'bounceInLeft',
						'class' => 'bounceInLeft',
					],
					[
						'name'  => esc_html__( 'bounce In Right', 'zionbuilder' ),
						'value' => 'bounceInRight',
						'class' => 'bounceInRight',
					],
					[
						'name'  => esc_html__( 'bounce In Up', 'zionbuilder' ),
						'value' => 'bounceInUp',
						'class' => 'bounceInUp',
					],
					[
						'name'  => esc_html__( 'fade In', 'zionbuilder' ),
						'value' => 'fadeIn',
						'class' => 'fadeIn',
					],
					[
						'name'  => esc_html__( 'fade In Down', 'zionbuilder' ),
						'value' => 'fadeInDown',
						'class' => 'fadeInDown',
					],
					[
						'name'  => esc_html__( 'fade In Down Big', 'zionbuilder' ),
						'value' => 'fadeInDownBig',
						'class' => 'fadeInDownBig',
					],
					[
						'name'  => esc_html__( 'fade In Left', 'zionbuilder' ),
						'value' => 'fadeInLeft',
						'class' => 'fadeInLeft',
					],
					[
						'name'  => esc_html__( 'fade In Right', 'zionbuilder' ),
						'value' => 'fadeInRight',
						'class' => 'fadeInRight',
					],
					[
						'name'  => esc_html__( 'fade In Left Big', 'zionbuilder' ),
						'value' => 'fadeInLeftBig',
						'class' => 'fadeInLeftBig',
					],
					[
						'name'  => esc_html__( 'fade In Up', 'zionbuilder' ),
						'value' => 'fadeInUp',
						'class' => 'fadeInUp',
					],
					[
						'name'  => esc_html__( 'fade In Up Big', 'zionbuilder' ),
						'value' => 'fadeInUpBig',
						'class' => 'fadeInUpBig',
					],
					[
						'name'  => esc_html__( 'light Speed In', 'zionbuilder' ),
						'value' => 'lightSpeedIn',
						'class' => 'lightSpeedIn',
					],
					[
						'name'  => esc_html__( 'roll In', 'zionbuilder' ),
						'value' => 'rollIn',
						'class' => 'rollIn',
					],
					[
						'name'  => esc_html__( 'zoom In', 'zionbuilder' ),
						'value' => 'zoomIn',
						'class' => 'zoomIn',
					],
					[
						'name'  => esc_html__( 'zoom In Down', 'zionbuilder' ),
						'value' => 'zoomInDown',
						'class' => 'zoomInDown',
					],
					[
						'name'  => esc_html__( 'zoom In Left', 'zionbuilder' ),
						'value' => 'zoomInLeft',
						'class' => 'zoomInLeft',
					],
					[
						'name'  => esc_html__( 'zoom In Right', 'zionbuilder' ),
						'value' => 'zoomInRight',
						'class' => 'zoomInRight',
					],
					[
						'name'  => esc_html__( 'zoom In Up', 'zionbuilder' ),
						'value' => 'zoomInUp',
						'class' => 'zoomInUp',
					],
					[
						'name'  => esc_html__( 'slide In Down', 'zionbuilder' ),
						'value' => 'slideInDown',
						'class' => 'slideInDown',
					],
					[
						'name'  => esc_html__( 'Slide In Left', 'zionbuilder' ),
						'value' => 'slideInLeft',
						'class' => 'slideInLeft',
					],
					[
						'name'  => esc_html__( 'slide In Right', 'zionbuilder' ),
						'value' => 'slideInRight',
						'class' => 'slideInRight',
					],
					[
						'name'  => esc_html__( 'slide In Up', 'zionbuilder' ),
						'value' => 'slideInUp',
						'class' => 'slideInUp',
					]
				]
			]
		);

		$animation_group->add_option(
			'_appear_duration',
			[
				'type'        => 'dynamic_slider',
				'description' => esc_html__( 'Set the desired appear animation duration (in miliseconds).' ),
				'title'       => esc_html__( 'Appear duration', 'zionbuilder' ),
				'default'     => '1000ms',
				'content'     => 'ms',
				'dependency'  => [
					[
						'option' => '_appear_animation',
						'type'   => 'not_in',
						'value'  => [ '' ],
					],
				],
				'options'     => [
					[
						'min'        => 0,
						'max'        => 100,
						'step'       => 1,
						'shift_step' => 5,
						'unit'       => 's',
					],
					[
						'min'        => 0,
						'max'        => 10000,
						'step'       => 10,
						'shift_step' => 100,
						'unit'       => 'ms',
					],
				],
				'sync'        => '_styles.lightbox_styles.styles.%%RESPONSIVE_DEVICE%%.default.animation-duration',
			]
		);

		$animation_group->add_option(
			'_appear_delay',
			[
				'type'        => 'dynamic_slider',
				'description' => esc_html__( 'Set the desired appear animation delay (in miliseconds).', 'zionbuilder' ),
				'title'       => esc_html__( 'Appear delay', 'zionbuilder' ),
				'default'     => '0ms',
				'dependency'  => [
					[
						'option' => '_appear_animation',
						'type'   => 'not_in',
						'value'  => [ '' ],
					],
				],
				'options'     => [
					[
						'min'        => 0,
						'max'        => 100,
						'step'       => 1,
						'shift_step' => 5,
						'unit'       => 's',
					],
					[
						'min'        => 0,
						'max'        => 10000,
						'step'       => 10,
						'shift_step' => 100,
						'unit'       => 'ms',
					],
				],
				'sync'        => '_styles.lightbox_styles.styles.%%RESPONSIVE_DEVICE%%.default.animation-delay',
			]
		);
	}

	protected function can_render() {
		if( ! License::has_valid_license() )
			return false;

		return true;
	}

	/**
	 * Loading the styles
	 * 
	 * @return void
	 */
	public function enqueue_styles() {
		$hasAnimation = $this->options->get_value( '_appear_animation', false );
		$enabled_slider = $this->options->get_value( 'enabled_slider', false );
		if( ( ! empty( $hasAnimation ) && $hasAnimation != '' ) || ! empty( $enabled_slider )) {
			wp_enqueue_style( 'zion-frontend-animations' );
		}

		$this->enqueue_editor_style( Utils::get_file_url( 'dist/css/elements/Lightbox/editor.css' ) );
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/Lightbox/frontend.css' ) );
	}

	/**
	 * Loading the js files
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/Lightbox/editor.js' ) );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/Lightbox/frontend.js' ) );
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'lightbox_styles',
			[
				'title'    => esc_html__( 'Lightbox Container Styles', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .zu-lightbox-container',
			]
		);

		$this->register_style_options_element(
			'icon_wrapper_styles',
			[
				'title'    => esc_html__( 'Icon Wrapper Styles', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .zu-lightbox-close',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'icon_styles',
			[
				'title'    => esc_html__( 'Close Icon Styles', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .zu-lightbox-close__icon'
			]
		);

		$this->register_style_options_element(
			'nav_styles',
			[
				'title'    => esc_html__( 'Prev/Next Buttons Styles', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .zulb-slider-navigation .nav-buttons',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}

	public function before_render( $options )
	{
		$enabled_slider 	= $options->get_value( 'enabled_slider', false );
		$used_repeater 		= $options->get_value( 'used_repeater', 'yes' );
		$outer_container 	= $options->get_value( 'outer_container', false );

		$selector = false;
		if( $used_repeater == 'no' && ! empty( $outer_container ) ) {
			$selector = $outer_container;
		}
		
		$data = [
			'event' 			=> $options->get_value( 'trigger_type', 'click' ),
			'selectors' 		=> $options->get_value( 'trigger_selector' ),
			'animation' 		=> $options->get_value( '_appear_animation', false ),
			'closebtn' 			=> $options->get_value( 'custom_cb_selector', '.zu-lightbox-close__icon' ),
			'disable_scroll' 	=> $options->get_value( 'disable_scroll', 'yes' ),
			'enabled_slider' 	=> ! empty( $enabled_slider ) ? true : false,
			'used_repeater' 	=> $used_repeater,
			'container_selector' => $selector,
			'isBuilder' 		=> self::isBuilderEditor()
		];

		$this->render_attributes->add( 'wrapper', 'class', 'zu-lightbox' );
		$this->render_attributes->add( 'wrapper', 'data-zulightbox-config', wp_json_encode( $data ) );

		$disable_backdrop = $options->get_value( 'disable_backdrop', false );
		if( empty( $disable_backdrop ) || $disable_backdrop == false ) {
			$this->render_attributes->add( 'wrapper', 'class', 'zu-lightbox--has-backdrop');
		}
	}

	/**
	 * Rendering the layout
	 * 
	 * @return void
	 */
	public function render( $options ) {
		$icon_html 			= '';
		$closebtn_option 	= $options->get_value( 'closebtn_option', 'inbuilt' );
		$fullscreen_btn 	= $options->get_value( 'has_fullscreen_button', false );
		$icon 				= $options->get_value( 'close_icon', false );
		$enabled_slider 	= $options->get_value( 'enabled_slider', false );
		$combined_icon_attr = $this->render_attributes->get_combined_attributes( 'icon_styles', [ 'class' => 'zu-lightbox-close__icon', 'role' => 'button', 'aria-label' => 'close' ] );

		if( ! empty( $fullscreen_btn ) && empty( $enabled_slider ) ) {
			$icon_html = '<span data-znpbiconfam="Font Awesome 5 Free Solid" data-znpbicon="'.json_decode('"\\uf065"') .'" class="zu-lightbox-expand__icon zu-lightbox-fullscreen" role="button" aria-label="fullscreen"></span><span data-znpbiconfam="Font Awesome 5 Free Solid" data-znpbicon="'.json_decode('"\\uf066"') .'" class="zu-lightbox-compress__icon zulb-hide-icon zu-lightbox-fullscreen" role="button" aria-label="close fullscreen"></span>';
		}

		if ( ! empty( $icon ) && $closebtn_option == 'inbuilt') {
			$this->attach_icon_attributes( 'icon', $icon );
			$icon_html .= $this->get_render_tag(
				'span',
				'icon',
				'',
				$combined_icon_attr
			);
		}

		echo '<div class="zu-lightbox-container">';
		
		$this->render_children();

		if( $closebtn_option == 'inbuilt' || ! empty( $fullscreen_btn ) ) {
			$this->render_tag( 'div', 'close_button', $icon_html, [ 'class' => [ 'zu-lightbox-close' ] ] );
		}

		echo '</div>';

		if( ! empty( $enabled_slider ) ) {
			$prev_icon_html = '';
			$next_icon_html = '';
			
			$prev_text = ! empty( $options->get_value('prev_text') ) ? sprintf( '<span class="prev-text btn-text">%s</span>', esc_html( $options->get_value('prev_text') ) ) : '';
			$next_text = ! empty( $options->get_value('next_text') ) ? sprintf( '<span class="next-text btn-text">%s</span>', esc_html( $options->get_value('next_text') ) ) : '';
			
			$prev_icon = $options->get_value( 'prev_icon', false );
			$next_icon = $options->get_value( 'next_icon', false );

			$prev_icon_attr = $this->render_attributes->get_combined_attributes( 'prev_icon_styles', [ 'class' => 'zu-lightbox-prev__icon btn-icon', 'role' => 'button' ]);
			$next_icon_attr = $this->render_attributes->get_combined_attributes( 'next_icon_styles', [ 'class' => 'zu-lightbox-next__icon btn-icon', 'role' => 'button' ]);
			
			if( ! empty( $prev_icon ) ) {
				$this->attach_icon_attributes( 'prev_icon', $prev_icon );
				$prev_icon_html .= $this->get_render_tag(
					'span',
					'prev_icon',
					'',
					$prev_icon_attr
				);
			}

			if( ! empty( $next_icon ) ) {
				$this->attach_icon_attributes( 'next_icon', $next_icon );
				$next_icon_html .= $this->get_render_tag(
					'span',
					'next_icon',
					'',
					$next_icon_attr
				);
			}

			$this->render_tag(
				'nav',
				'slider_navigation',
				'<span class="nav-buttons prev">' . $prev_icon_html . $prev_text . '</span>
				<span class="nav-buttons next">'. $next_icon_html . $next_text . '</span>',
				[
					'class' => 'zulb-slider-navigation'
				]
			);
		}
	}
}