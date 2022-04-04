<?php
namespace ZiUltimate\Elements\ImageComparison;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;
use ZionBuilder\WPMedia;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ImageComparison
 *
 * @package ZiUltimate\Elements
 */
class ImageComparison extends UltimateElements {
	public function get_type() {
		return 'zu_img_comparison';
	}

	public function get_name() {
		return __( 'Image Comparison', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'image', 'before', 'after', 'comparison' ];
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

	/**
	 * Registering the options fields
	 * 
	 * @return void
	 */
	public function options( $options ) {

		if( ! License::has_valid_license() ) {
			$title = $this->get_name();
			$description = 'With this tool you can build the before/after image block for your site.';
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
			'before_image',
			[
				'type'        => 'image',
				'id'          => 'before_image',
				'description' => 'Choose the desired image.',
				'title'       => esc_html__( 'Before Image', 'ziultimate' ),
				'show_size'   => true,
				'default'     => [
					'image' => Utils::get_file_url( 'assets/img/before-image.jpg' ),
				],
				'dynamic'     => [
					'enabled' => true,
				],
			]
		);

		$options->add_option(
			'after_image',
			[
				'type' 			=> 'image',
				'id' 			=> 'after_image',
				'description' 	=> 'Choose the desired image.',
				'title' 		=> esc_html__( 'After Image', 'ziultimate' ),
				'show_size' 	=> true,
				'default' 		=> [
					'image' => Utils::get_file_url( 'assets/img/after-image.jpg' ),
				],
				'dynamic' 		=> [
					'enabled' => true,
				],
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
			'width',
			[
				'type' 			=> 'number_unit',
				'title' 		=> esc_html__( 'Wrapper Width', 'ziultimate' ),
				'units' 		=> [
					'px',
					'pt',
					'rem',
					'vh',
					'vw',
					'%',
					'auto',
					'initial',
					'unset',
				],
				'min' 			=> 0,
				'css_style' 	=> [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> 'max-width: {{VALUE}}'
					]
				]
			]
		);

		$options->add_option(
			'img_hover_color',
			[
				'type' 			=> 'colorpicker',
				'title' 		=> esc_html__('Image Hover Overlay Color', 'ziultimate'),
				'default' 		=> 'rgba(0, 0, 0, 0.5)',
				'css_style' 	=> [
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-overlay:hover',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				]
			]
		);

		$options->add_option(
			'overlay_td',
			[
				'type' 		=> 'slider',
				'content' 	=> 's',
				'default' 	=> 0.5,
				'min' 		=> 0,
				'max' 		=> 10,
				'step' 		=> 0.1,
				'title' 	=> __( 'Overlay Transition Duration', 'ziultimate' ),
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-overlay',
						'value' 	=> 'transition-duration: {{VALUE}}s'
					],
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-before-label',
						'value' 	=> 'transition-duration: {{VALUE}}s'
					],
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-after-label',
						'value' 	=> 'transition-duration: {{VALUE}}s'
					],					
				]
			]
		);

		/*$options->add_option(
			'apply_params',
			[
				'type' 	=> 'html',
				'title' => esc_html__('Reload or refresh the element if preview is not showing correctly on builder editor.', 'ziultimate'),
				'content' => '<button class="apply-params" style="background: #efefef;border: none;cursor: pointer;padding: 12px;width: 100%;text-transform: uppercase;font-family: inherit;font-size: 12px;" type="button" onclick="" aria-label="Reload Element">Apply Params</button>'
			]
		);*/

		/**
		 * Group - Comparison Handle
		 * 
		 * @return void
		 */
		$handle = $options->add_group(
			'handle',
			[
				'type' 			=> 'panel_accordion',
				'title' 		=> esc_html__('Comparison Handle', 'ziultimate')
			]
		);

		$handle->add_option(
			'mhover',
			[
				'type'    	=> 'checkbox_switch',
				'title' 	=> esc_html__( 'Move on Hover', 'ziultimate' ),
				'default' 	=> false,
				'layout'  	=> 'inline'
			]
		);

		$handle->add_option(
			'orientation',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__( 'Orientation', 'zicoustomelements' ),
				'default' 	=> 'horizontal',
				'options' 	=> [
					[
						'name' => 'Horizontal',
						'id'   => 'horizontal',
					],
					[
						'name' => 'Vertical',
						'id'   => 'vertical',
					]
				],
			]
		);

		$handle->add_option(
			'circle_pos',
			[
				'type' 		=> 'slider',
				'title' 	=> __( 'Circle Position (Top or Bottom)', 'ziultimate' ),
				'content'	=> '%',
				'min' 		=> 0,
				'default' 	=> 50,
				'max'		=> 100,
				'step' 		=> 1,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-horizontal .twentytwenty-handle',
						'value' 	=> 'top: {{VALUE}}%'
					]
				],
				'dependency' => [
					[
						'option' => 'orientation',
						'value' => [ 'horizontal' ]
					]
				]
			]
		);

		$handle->add_option(
			'circle_vpos',
			[
				'type' 		=> 'slider',
				'title' 	=> __( 'Circle Position (Left or Right)', 'ziultimate' ),
				'content'	=> '%',
				'min' 		=> 0,
				'default' 	=> 50,
				'max'		=> 100,
				'step' 		=> 1,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-vertical .twentytwenty-handle',
						'value' 	=> 'left: {{VALUE}}%'
					]
				],
				'dependency' => [
					[
						'option' => 'orientation',
						'value' => [ 'vertical' ]
					]
				]
			]
		);

		$handle->add_option(
			'initial_offset',
			[
				'type' 		=> 'slider',
				'title' 	=> esc_html__( 'Initial Offset', 'ziultimate' ),
				'content'	=> '',
				'min' 		=> 0,
				'default' 	=> 0.7,
				'max'		=> 1,
				'step' 		=> 0.1
			]
		);

		$handle->add_option(
			'bar_width',
			[
				'type' 		=> 'slider',
				'title' 	=> esc_html__( 'Handle Size', 'ziultimate' ),
				'content'	=> 'px',
				'min' 		=> 0,
				'default' 	=> 3,
				'max'		=> 20,
				'step' 		=> 1,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--vertical-bar-width: {{VALUE}}px'
					]
				]
			]
		);

		$handle->add_option(
			'bar_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__('Handle Color', 'ziultimate'),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--handle-color: {{VALUE}}'
					]
				]
			]
		);

		$handle->add_option(
			'bar_shadow_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__('Shadow Color', 'ziultimate'),
				'default' 	=> 'rgba(51, 51, 51, 0.5)',
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--handle-shadow-color: {{VALUE}}'
					]
				]
			]
		);

		/**
		 * Group - Circle & Arrows
		 * 
		 * @return void
		 */
		$circle = $options->add_group(
			'circle_grp',
			[
				'type' 	=> 'panel_accordion',
				'title' => esc_html__('Circle and Arrows', 'ziultimate')
			]
		);

		$circle->add_option(
			'disable_circle',
			[
				'type'    => 'checkbox_switch',
				'title'   => esc_html__( 'Disable Circle and Arrows', 'ziultimate' ),
				'default' => false,
				'layout'  => 'inline'
			]
		);

		$circle->add_option(
			'border_radius',
			[
				'type' 		=> 'number_unit',
				'title' 	=> esc_html__( 'Border Radius' ),
				'units' 	=> [
					'px',
					'unset',
				],
				'min' 		=> 0,
				'css_style' => [
					[
						'selector' => '{{ELEMENT}} .twentytwenty-handle',
						'value' 	=> 'border-radius: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' => 'disable_circle',
						'value' => [ false ]
					]
				]
			]
		);

		$circle->add_option(
			'circle_bg_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__('Background Color'),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-handle',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' => 'disable_circle',
						'value' => [ false ]
					]
				]
			]
		);

		$circle->add_option(
			'arrow_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__('Arrow Color', 'ziultimate'),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--arrow-color: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' => 'disable_circle',
						'value' => [ false ]
					]
				]
			]
		);

		/**
		 * Label
		 * 
		 * @return void
		 */
		$tg = $options->add_group(
			'text_tg',
			[
				'type' 			=> 'panel_accordion',
				'title' 		=> esc_html__('Label', 'ziultimate')
			]
		);

		$tg->add_option(
			'before_text',
			[
				'type' 		=> 'text',
				'title' 	=> esc_html__('Before Image Text', 'ziultimate'),
				'placeholder' => esc_html__('Before'),
				'dynamic'     => [
					'enabled' => true,
				]
			]
		);

		$tg->add_option(
			'after_text',
			[
				'type' 		=> 'text',
				'title' 	=> esc_html__('After Image Text', 'ziultimate'),
				'placeholder' => esc_html__('After'),
				'dynamic'     => [
					'enabled' => true,
				]
			]
		);

		$tg->add_option(
			'label_pos',
			[
				'type' 		=> 'slider',
				'title' 	=> __( 'Text Position (Vertically)', 'ziultimate' ),
				'content'	=> '%',
				'min' 		=> 0,
				'default' 	=> 50,
				'max'		=> 100,
				'step' 		=> 1,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-before-label:before',
						'value' 	=> 'top: {{VALUE}}%'
					],
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-after-label:before',
						'value' 	=> 'top: {{VALUE}}%'
					]
				]
			]
		);

		$tg->add_option(
			'ba_text_bg',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__('Background Color of Square Boxes', 'ziultimate'),
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-before-label:before',
						'value' 	=> 'background-color: {{VALUE}}'
					],
					[
						'selector' 	=> '{{ELEMENT}} .twentytwenty-after-label:before',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				]
			]
		);

		$this->attach_typography_options($tg, 'bftext', '{{ELEMENT}} .twentytwenty-before-label:before, {{ELEMENT}} .twentytwenty-after-label:before', ['text_align']);
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
			'zuimgc-event-script', 
			Utils::get_file_url( 'assets/js/jquery.event.js' ),
			array(),
			'1.0',
			true 
		);
		wp_enqueue_script(
			'zuimgc-twentytwenty-script', 
			Utils::get_file_url( 'assets/js/jquery.twentytwenty.js' ),
			array(),
			'1.0.1',
			true
		);

		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/ImageComparison/editor.js' ) );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/ImageComparison/frontend.js' ) );
	}

	/**
	 * Registering the styles
	 * 
	 * @return void
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			'zuimgc-twentytwenty-style', 
			Utils::get_file_url('dist/css/elements/ImageComparison/twentytwenty.css'),
			array(),
			'1.0.1',
			'all'
		);
	}

	/**
	 * Adding some extra attributes
	 * 
	 * @return void
	 */
	public function before_render( $options ) {
		$before_text = $options->get_value('before_text');
		$after_text = $options->get_value('after_text');
		$disable_circle = $options->get_value('disable_circle', false);
		$data_atts = [
			'before_label' 	=> str_replace( "'", '&apos;', $before_text ),
			'after_label' 	=> str_replace( "'", '&apos;', $after_text ),
			'orientation' 	=> $options->get_value( 'orientation', 'horizontal'),
			'mhover' 		=> $options->get_value( 'mhover', false ),
			'initial_offset' => $options->get_value( 'initial_offset', 0.7 ),
			'width' 		=> $options->get_value('width', 'auto')
		];

		$this->render_attributes->add( 'wrapper', 'class', $disable_circle ? 'disable-handle-circle' : '' );
		$this->render_attributes->add( 'wrapper', 'data-zu-imgc', wp_json_encode( $data_atts ) );
	}

	/**
	 * Rendering the layout
	 * 
	 * @return void
	 */
	public function render( $options ) {
		$default_bimg = Utils::get_file_url( 'assets/img/before-image.jpg' );
		$default_aimg = Utils::get_file_url( 'assets/img/after-image.jpg' );

		$bimg  = $options->get_value(
			'before_image',
			[
				'image' => $default_bimg
			]
		);

		$aimg  = $options->get_value(
			'after_image',
			[
				'image' => $default_aimg,
			]
		);

		$combined_image_attr = $this->render_attributes->get_combined_attributes_as_key_value( 'before_image_styles', [ 'srcset' => '', 'class' => 'skip-lazy' ] );
		$before_image = WPMedia::get_imge(
			$bimg,
			$combined_image_attr
		);

		$combined_image_attr = $this->render_attributes->get_combined_attributes_as_key_value( 'after_image_styles', [ 'srcset' => '', 'class' => 'skip-lazy' ] );
		$after_image = WPMedia::get_imge(
			$aimg,
			$combined_image_attr
		);
?>
	<div class="zu-imgc-wrap">
		<?php if( $bimg['image'] == $default_bimg && $aimg['image'] == $default_aimg ) { ?>
			<img loading="lazy" src="<?php echo $bimg['image'];?>" width="640" height="427" class="skip-lazy">
			<img loading="lazy" src="<?php echo $aimg['image'];?>" width="640" height="427" class="skip-lazy">
		<?php } else { ?>
			<?php echo $before_image; echo $after_image; ?>
		<?php } ?>
	</div>
<?php
	}
}