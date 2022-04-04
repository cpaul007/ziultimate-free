<?php

namespace ZiUltimate\Elements\AnimatedBurger;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class AnimatedBurger
 *
 * @package ZiUltimate\Elements
 */
class AnimatedBurger extends UltimateElements {
	public function get_type() {
		return 'zu_burger';
	}

	public function get_name() {
		return __( 'Animated Burger', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'animated burger', 'burger', 'hamburger', 'menu icon' ];
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
	 * Registering the options fields
	 */
	public function options( $options ) {
		if( ! License::has_valid_license() ) {
			$title = $this->get_name();
			$description = 'With it you can build animated button.';
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
			'varient_type',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> __('Animation Type', 'ziultimate'),
				'default' 	=> 'standard',
				'options' 	=> [
					[
						'name' 	=> 'Standard',
						'id' 	=> 'standard'
					],
					[
						'name' 	=> 'Reverse',
						'id' 	=> 'r'
					]
				]
			]
		);

		$options->add_option(
			'varient',
			[
				'type' 		=> 'select',
				'title' 	=> __('Effect', 'ziultimate'),
				'description' 	=> __('See the demo here https://jonsuh.com/hamburgers/', 'ziultimate'),
				'default' 	=> 'spin',
				'options' 	=> [
					[
						'name' 	=> 'Arrow',
						'id' 	=> 'arrow'
					],
					[
						'name' 	=> 'Arrow Alt',
						'id' 	=> 'arrowalt'
					],
					[
						'name' 	=> 'Arrow Turn',
						'id' 	=> 'arrowturn'
					],
					[
						'name' 	=> 'Boring',
						'id' 	=> 'boring'
					],
					[
						'name' 	=> 'Collapse',
						'id' 	=> 'collapse'
					],
					[
						'name' 	=> 'Elastic',
						'id' 	=> 'elastic'
					],
					[
						'name' 	=> 'Emphatic',
						'id' 	=> 'emphatic'
					],
					[
						'name' 	=> 'Minus',
						'id' 	=> 'minus'
					],
					[
						'name' 	=> 'Slider',
						'id' 	=> 'slider'
					],
					[
						'name' 	=> 'Squeeze',
						'id' 	=> 'squeeze'
					],
					[
						'name' 	=> 'Spin',
						'id' 	=> 'spin'
					],
					[
						'name' 	=> 'Stand',
						'id' 	=> 'stand'
					],
					[
						'name' 	=> 'Spring',
						'id' 	=> 'spring'
					],
					[
						'name' 	=> 'Vortex',
						'id' 	=> 'vortex'
					],					
					[
						'name' 	=> '3DX',
						'id' 	=> '3dx'
					],
					[
						'name' 	=> '3DY',
						'id' 	=> '3dy'
					]
				]
			]
		);

		$options->add_option(
			'layer_width',
			[
				'type' 		=> 'number_unit',
				'units' 	=> ['px'],
				'width' 	=> 33.3,
				'default' 	=> '40px',
				'title' 	=> __( 'Lines Width', 'ziultimate' ),
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger',
						'value' 	=> '--hamburger-layer-width: {{VALUE}}'
					]
				]
			]
		);

		$options->add_option(
			'layer_height',
			[
				'type' 		=> 'number_unit',
				'units' 	=> ['px'],
				'default' 	=> '4px',
				'title' 	=> __( 'Line Height', 'ziultimate' ),
				'width' 	=> 33.3,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger',
						'value' 	=> '--hamburger-layer-height: {{VALUE}}'
					]
				]
			]
		);

		$options->add_option(
			'layer_spacing',
			[
				'type' 		=> 'number_unit',
				'units' 	=> ['px'],
				'default' 	=> '6px',
				'title' 	=> __( 'Gap', 'ziultimate' ),
				'description' => esc_html__('Set up the gaps between the lines.', 'ziultimate'),
				'width' 	=> 33.3,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger',
						'value' 	=> '--hamburger-layer-spacing: {{VALUE}}'
					]
				]
			]
		);

		$options->add_option(
			'top_line_width',
			[
				'type' 		=> 'number_unit',
				'units' 	=> ['px'],
				'width' 	=> 50,
				'title' 	=> __( '1st Line Width', 'ziultimate' ),
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger .hamburger-inner:before',
						'value' 	=> 'width: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' => 'varient',
						'value' => [ 'arrow', 'arrowalt', 'arrowturn', 'boring', 'spring', 'stand','collapse', 'slider', 'minus' ]
					]
				]
			]
		);

		$options->add_option(
			'bottom_line_width',
			[
				'type' 		=> 'number_unit',
				'units' 	=> ['px'],
				'width' 	=> 50,
				'title' 	=> __( '3rd Line Width', 'ziultimate' ),
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger .hamburger-inner:after',
						'value' 	=> 'width: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' => 'varient',
						'value' => [ 'arrow', 'arrowalt', 'arrowturn', 'boring', 'spring', 'stand','collapse', 'slider', 'minus' ]
					]
				]
			]
		);

		$options->add_option(
			'layer_brdrad',
			[
				'type' 		=> 'number_unit',
				'units' 	=> ['px'],
				'default' 	=> '4px',
				'title' 	=> __( 'Border Radius', 'ziultimate' ),
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger',
						'value' 	=> '--hamburger-layer-border-radius: {{VALUE}}'
					]
				]
			]
		);

		$options->add_option(
			'line_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __('Line Color'),
				'width' 	=> 33.3,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger-inner',
						'value' 	=> 'background-color: {{VALUE}}'
					],
					[
						'selector' 	=> '{{ELEMENT}} .hamburger-inner:after',
						'value' 	=> 'background-color: {{VALUE}}'
					],
					[
						'selector' 	=> '{{ELEMENT}} .hamburger-inner:before',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				]
			]
		);

		$options->add_option(
			'line_hcolor',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __('Hover Color'),
				'width' 	=> 33.3,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}:hover .hamburger .hamburger-inner',
						'value' 	=> 'background-color: {{VALUE}}'
					],
					[
						'selector' 	=> '{{ELEMENT}}:hover .hamburger .hamburger-inner:after',
						'value' 	=> 'background-color: {{VALUE}}'
					],
					[
						'selector' 	=> '{{ELEMENT}}:hover .hamburger .hamburger-inner:before',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				]
			]
		);

		$options->add_option(
			'line_acolor',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __('Active Color'),
				'width' 	=> 33.3,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger.is-active .hamburger-inner',
						'value' 	=> 'background-color: {{VALUE}}'
					],
					[
						'selector' 	=> '{{ELEMENT}} .hamburger.is-active .hamburger-inner:after',
						'value' 	=> 'background-color: {{VALUE}}'
					],
					[
						'selector' 	=> '{{ELEMENT}} .hamburger.is-active .hamburger-inner:before',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				]
			]
		);

		$options->add_option(
			'line_align',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> __('Lines Alignment', 'ziultimate'),
				'default' 	=> 'left',
				'options' 	=> [
					[
						'name' 	=> __('Left'),
						'id' 	=> 'left'
					],
					[
						'name' 	=> __('Right'),
						'id' 	=> 'right'
					]
				],
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger .hamburger-inner',
						'value' 	=> '{{VALUE}}: 0px'
					],
					[
						'selector' 	=> '{{ELEMENT}} .hamburger-inner:before',
						'value' 	=> '{{VALUE}}: 0px'
					],
					[
						'selector' 	=> '{{ELEMENT}} .hamburger-inner:after',
						'value' 	=> '{{VALUE}}: 0px'
					]
				],
				'dependency' => [
					[
						'option' => 'varient',
						'value' => [ 'arrow', 'arrowalt', 'arrowturn', 'boring', 'spring', 'stand','collapse', 'slider', 'minus' ]
					]
				]
			]
		);

		$options->add_option(
			'aria_label',
			[
				'type' 		=> 'text',
				'title' 	=> __('Aria Label'),
				'placeholder' => 'Menu'
			]
		);

		$options->add_option(
			'el_valid',
			[
				'type' 		=> 'text',
				'default' 	=> 'zu' . self::elVal(),
				'css_class' => 'znpb-checkbox-switch-wrapper__checkbox'
			]
		);

		$options->add_option(
			'is_active',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Active on Page Load', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

		/***************************
		 * Menu Text Group
		 ***************************/
		$text = $options->add_group(
			'menu_panel',
			[
				'type' 			=> 'panel_accordion',
				'title' 		=> __('Menu Text', 'ziultimate'),
				'collapsed' 	=> true
			]
		);

		$text->add_option(
			'menu_text',
			[
				'type' 		=> 'text',
				'title' 	=> __('Text'),
				'placeholder' => 'Menu',
				'dynamic'     => [
					'enabled' => true,
				]
			]
		);

		$text->add_option(
			'menu_text_pos',
			[
				'type' 		=> 'select',
				'title' 	=> __('Position'),
				'default' 	=> 'row',
				'options' 	=> [
					[
						'name' 	=> __('Right side of the icon', 'ziultimate'),
						'id' 	=> 'row'
					],
					[
						'name' 	=> __('Left side of the icon', 'ziultimate'),
						'id' 	=> 'row-reverse'
					]
				],
				'css_style' => [
					[
						'selector' => '{{ELEMENT}} .hamburger',
						'value' 	=> 'flex-direction: {{VALUE}}'
					]
				]
			]
		);

		$text->add_option(
			'space',
			[
				'type' 		=> 'slider',
				'title' 	=> esc_html__( 'Space between icon & text', 'ziultimate' ),
				'content'	=> 'px',
				'min' 		=> 0,
				'default' 	=> 5,
				'max'		=> 20,
				'step' 		=> 1,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger-label',
						'value' 	=> 'margin-left: {{VALUE}}px'
					]
				],
				'dependency' => [
					[
						'option' => 'menu_text_pos',
						'value' => [ 'row' ]
					]
				]
			]
		);

		$text->add_option(
			'space_right',
			[
				'type' 		=> 'slider',
				'title' 	=> esc_html__( 'Space between text & icon', 'ziultimate' ),
				'content'	=> 'px',
				'min' 		=> 0,
				'default' 	=> 0,
				'max'		=> 20,
				'step' 		=> 1,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .hamburger-label',
						'value' 	=> 'margin-right: {{VALUE}}px'
					]
				],
				'dependency' => [
					[
						'option' => 'menu_text_pos',
						'value' => [ 'row-reverse' ]
					]
				]
			]
		);

		$this->attach_typography_options($text, 'menu_text', '{{ELEMENT}} .hamburger-label', ['text_align']);

		$text->add_option(
			'menu_text_hclr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __('Hover Color'),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}:hover .hamburger-label',
						'value' 	=> 'color: {{VALUE}}'
					]
				]
			]
		);

		$text->add_option(
			'menu_text_aclr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __('Active Color'),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .is-active .hamburger-label',
						'value' 	=> 'color: {{VALUE}}'
					]
				]
			]
		);

		/***************************
		 * Menu Text Group
		 ***************************/
		$dropdown = $options->add_group(
			'dropdown_group',
			[
				'type' 			=> 'panel_accordion',
				'title' 		=> __('Dropdown', 'ziultimate'),
				'collapsed' 	=> true
			]
		);

		$dropdown->add_option(
			'has_dropdown',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__('Add dropdown', 'ziultimate'),
				'default' 	=> 'no',
				'width' 	=> 50,
				'options' 	=> [
					[
						'name' 	=> esc_html__('Yes'),
						'id' 	=> 'yes',
					],
					[
						'name' 	=> esc_html__('No'),
						'id' 	=> 'no',
					]
				]
			]
		);

		$dropdown->add_option(
			'preview_dropdown',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__('In-Builder Preview', 'ziultimate'),
				'width' 	=> 50,
				'default' 	=> 'yes',
				'options' 	=> [
					[
						'name' 	=> esc_html__('Yes'),
						'id' 	=> 'yes',
					],
					[
						'name' 	=> esc_html__('No'),
						'id' 	=> 'no',
					]
				]
			]
		);

		$dropdown->add_option(
			'dropdown_bg',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__('Background Color'),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .zu-burger-sub-menu',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				]
			]
		);

		$dropdown->add_option(
			'dropdown_width',
			[
				'type' 		=> 'number_unit',
				'min' 		=> 0,
				'default' 	=> '300px',
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
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .zu-burger-sub-menu',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				],
				'show_responsive_buttons' => true,
			]
		);
	}

	/**
	 * Loading the css file
	 */
	public function enqueue_scripts() {
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/AnimatedBurger/editor.js' ) );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/AnimatedBurger/animatedburger.js' ) );
	}

	/**
	 * Loading the scripts
	 */
	public function enqueue_styles() {
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/AnimatedBurger/animatedburger.css' ) );
	}

	/**
	 * Registering the styles
	 */
	public function on_register_styles() {
		$this->register_style_options_element(
			'dropdown_styles',
			[
				'title'    => esc_html__( 'Dropdown Styles', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .zu-burger-sub-menu',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}

	/**
	 * Rendering the layout
	 */
	public function render( $options ) {
		$aria_label = $options->get_value('aria_label', false);
		$varient 	= $options->get_value('varient', 'spin');
		$menu_text 	= $options->get_value('menu_text', false);
		$has_dropdown = $options->get_value('has_dropdown', 'no');

		$class = '';
		$isActive = $options->get_value('is_active', false);
		if( $isActive ) {
			$class = ' is-active';
		}

		$anim_type = ( $options->get_value('varient_type') == 'r' ) ? '-r' : '';

		if( in_array( $varient, ['boring', 'minus', 'squeeze'] ) )
			$anim_type = '';
	?>
		<button class="hamburger hamburger--<?php echo $varient . $anim_type; ?><?php echo $class; ?>" type="button" aria-label="<?php echo $aria_label; ?>" aria-controls="navigation">
			<span class="hamburger-box">
				<span class="hamburger-inner"></span>
			</span>
			<?php if( $menu_text ): ?>
				<span class="hamburger-label"><?php echo $menu_text; ?></span>
			<?php endif; ?>
		</button>
	<?php
		//* dropdown
		if( $has_dropdown != 'no' ) 
		{
			echo '<div class="zu-burger-sub-menu">';
			$this->render_children();
			echo '</div>';
		}
	}
}