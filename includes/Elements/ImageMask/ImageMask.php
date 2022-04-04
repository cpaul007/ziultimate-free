<?php
namespace ZiUltimate\Elements\ImageMask;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;
use ZionBuilder\Options\BaseSchema;
use ZionBuilder\WPMedia;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ImageMask
 *
 * @package ZiUltimate\Elements
 */
class ImageMask extends UltimateElements {
	
	public function get_type() {
		return 'zu_image_mask';
	}

	public function get_name() {
		return __( 'Image Mask', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'photo', 'image', 'mask', 'media' ];
	}

	public function get_element_icon() {
		return 'element-image';
	}

	public function get_category() {
		return $this->zu_elements_category();
	}

	public function options( $options ) {
		if( ! License::has_valid_license() ) {
			$title = $this->get_name();
			$description = 'You can add the shape to your image.';
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
			'image',
			[
				'type'        => 'image',
				'id'          => 'image',
				'description' => 'Choose the desired image.',
				'title'       => esc_html__( 'Image', 'zionbuilder' ),
				'show_size'   => true,
				'default'     => [
					'image' => Utils::get_file_url( 'assets/img/no-image.jpg' ),
				],
				'dynamic'     => [
					'enabled' => true,
				]
			]
		);

		$path = Utils::get_file_url_from_path( '/ziultimate/includes/Elements/ImageMask/shapes' );
		$shapes = [];
		for ($i=0; $i < 64 ; $i++) { 
			$shapes[$i]['name'] = "Shape " . ($i + 1);
			$shapes[$i]['id'] = "shape-" . ( $i + 1 );
		}

		$options->add_option(
			'mask_shape',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__( 'Mask Shape', 'ziultimate' ),
				'default' 	=> 'shape-1',
				'options' 	=> $shapes,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}.zu-image-mask',
						'value' 	=> "-webkit-mask-image: url({$path}/{{VALUE}}.svg); mask-image: url({$path}/{{VALUE}}.svg);"
					]
				]
			]
		);

		$options->add_option(
			'repeat',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__( 'Repeat', 'ziultimate' ),
				'default' 	=> 'no-repeat',
				'options' 	=> [
					[
						'name' => esc_html__('No Repeat', 'ziultimate' ),
						'id'   => 'no-repeat',
					],
					[
						'name' => esc_html__('Repeat', 'ziultimate' ),
						'id'   => 'Repeat',
					]
				],
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}.zu-image-mask',
						'value' 	=> "-webkit-mask-repeat: {{VALUE}}"
					]
				]
			]
		);

		$options->add_option(
			'position',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__( 'Position', 'ziultimate' ),
				'default' 	=> 'center',
				'options' 	=> [
					[
						'name' => esc_html__('Top', 'ziultimate' ),
						'id'   => 'top',
					],
					[
						'name' => esc_html__('Bottom', 'ziultimate' ),
						'id'   => 'bottom',
					],
					[
						'name' => esc_html__('Center', 'ziultimate' ),
						'id'   => 'center',
					],
					[
						'name' => esc_html__('Left', 'ziultimate' ),
						'id'   => 'left',
					],
					[
						'name' => esc_html__('Right', 'ziultimate' ),
						'id'   => 'right',
					],
					[
						'name' => esc_html__('Custom', 'ziultimate' ),
						'id'   => 'unset',
					],
				],
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}.zu-image-mask',
						'value' 	=> "-webkit-mask-position: {{VALUE}}"
					]
				]
			]
		);

		$options->add_option(
			'x_offset',
			[
				'type' 						=> 'dynamic_slider',
				'title' 					=> esc_html__( 'X Offset', 'zionbuilder' ),
				'show_responsive_buttons' 	=> true,
				'options' 					=> [
					[
						'unit' => 'px',
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
					[
						'unit' => '%',
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'dependency' 				=> [
					[
						'option' 	=> 'position',
						'value' 	=> [ 'unset' ]
					]
				],
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}.zu-image-mask',
						'value' 	=> "-webkit-mask-position-x: {{VALUE}}"
					]
				]
			]
		);

		$options->add_option(
			'y_offset',
			[
				'type' 						=> 'dynamic_slider',
				'title' 					=> esc_html__( 'Y Offset', 'zionbuilder' ),
				'show_responsive_buttons' 	=> true,
				'options' 					=> [
					[
						'unit' => 'px',
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
					[
						'unit' => '%',
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'dependency' 				=> [
					[
						'option' 	=> 'position',
						'value' 	=> [ 'custom' ]
					]
				],
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}.zu-image-mask',
						'value' 	=> "-webkit-mask-position-y: {{VALUE}}"
					]
				]
			]
		);

		$options->add_option(
			'size',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__( 'Size', 'ziultimate' ),
				'default' 	=> 'contain',
				'options' 	=> [
					[
						'name' => esc_html__('Auto', 'ziultimate' ),
						'id'   => 'auto',
					],
					[
						'name' => esc_html__('Contain', 'ziultimate' ),
						'id'   => 'contain',
					],
					[
						'name' => esc_html__('Cover', 'ziultimate' ),
						'id'   => 'cover',
					],
					[
						'name' => esc_html__('Custom', 'ziultimate' ),
						'id'   => 'custom',
					],
				],
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}.zu-image-mask',
						'value' 	=> "-webkit-mask-size: {{VALUE}}"
					]
				]
			]
		);

		$options->add_option(
			'custom_size',
			[
				'type' 						=> 'dynamic_slider',
				'title' 					=> esc_html__( 'Set Size', 'ziultimate' ),
				'show_responsive_buttons' 	=> true,
				'options' 					=> [
					[
						'unit' => 'px',
						'min'  => 0,
						'max'  => 999,
						'step' => 1,
					],
					[
						'unit' => '%',
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'dependency' 				=> [
					[
						'option' 	=> 'size',
						'value' 	=> [ 'custom' ]
					]
				],
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}.zu-image-mask',
						'value' 	=> "-webkit-mask-size: {{VALUE}}"
					]
				]
			]
		);

		$options->add_option(
			'zuel_imgmask',
			[
				'type' 		=> 'text',
				'default' 	=> 'zu' . self::elVal(),
				'css_class' => 'znpb-checkbox-switch-wrapper__checkbox'
			]
		);

		$options->add_option(
			'direction',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__( 'Direction', 'ziultimate' ),
				'default' 	=> 'none',
				'options' 	=> [
					[
						'name' => esc_html__('None', 'ziultimate' ),
						'id'   => 'none',
					],
					[
						'name' => esc_html__('Horizontal', 'ziultimate' ),
						'id'   => 'horizontal',
					],
					[
						'name' => esc_html__('Vertical', 'ziultimate' ),
						'id'   => 'vertical',
					]
				]
			]
		);
	}

	/**
	 * Loading the CSS files
	 * 
	 * @return void
	 */
	public function enqueue_styles() {
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/ImageMask/frontend.css' ) );
	}

	/**
	 * Loading the js files
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/ImageMask/editor.js' ) );
	}

	/**
	 * Get style elements
	 *
	 * Returns a list of elements/tags that for which you
	 * want to show style options
	 *
	 * @return void
	 */
	public function on_register_styles() {
		$this->register_style_options_element(
			'image_styles',
			[
				'title'      => esc_html__( 'Image styles', 'zionbuilder' ),
				'selector'   => '{{ELEMENT}} img',
				'render_tag' => 'image_styles',
			]
		);
	}

	public function before_render( $options ) {
		$class = 'zu-image-mask ';
		$class .= 'zu-image-mask-direction-' . $options->get_value('direction', 'none');
		$this->render_attributes->add( 'wrapper', 'class', $class );
	}

	public function render( $options ) {
		$image  = $options->get_value(
			'image',
			[
				'image' => Utils::get_file_url( 'assets/img/no-image.jpg' ),
			]
		);

		// Don't proceed if we don't have an image
		if ( empty( $image['image'] ) ) {
			return;
		}

		$attachment_id 	= attachment_url_to_postid( esc_attr( $image['image'] ) );
		$combined_image_attr = $this->render_attributes->get_combined_attributes_as_key_value( 'image_styles', [ 'class' => 'wp-image-' . $attachment_id ] );

		echo WPMedia::get_imge(
			$image,
			$combined_image_attr
		);
	}
}

/*add_filter( 'zionbuilder/admin/initial_data', 'zu_image_mask_shapes_path' );
function zu_image_mask_shapes_path( $data ) {
	return $data['img_mask_path'] = Utils::get_file_url_from_path( '/ziultimate/includes/elements/ImageMask/shapes' );
}*/