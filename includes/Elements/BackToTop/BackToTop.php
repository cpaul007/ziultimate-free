<?php
namespace ZiUltimate\Elements\BackToTop;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class BackToTop
 *
 * @package ZiUltimate\Elements
 */
class BackToTop extends UltimateElements {
	
	public function get_type() {
		return 'zu_back_to_top';
	}

	public function get_name() {
		return __( 'Back To Top', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'back', 'top', 'back to top' ];
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
	 * Creating the settings fields
	 * 
	 * @return void
	 */
	public function options( $options ) {
		if( ! License::has_valid_license() ) {
			$title = $this->get_name();
			$description = 'With this tool you can build the back to top button or smooth scrolling effect for hash link.';
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
            'use_for',
            [
                'type'      => 'custom_selector',
                'title'     => esc_html__('Using for', 'ziultimate'),
                'default'   => 'back',
                'options'   => [
                    [
                        'name'  => esc_html__('Back to top'),
                        'id'    => 'back'
                    ],
                    [
                        'name'  => esc_html__('Jump to section'),
                        'id'    => 'scroll'
                    ]
                ]
            ]
        );

        $options->add_option(
            'hide_back_button',
            [
                'type'      => 'checkbox_switch',
                'title'     => esc_html__('Disable back to top button preview for editor?', 'ziultimate'),
                'default'   => false,
                'layout'    => 'inline',
                'dependency' => [
                    [
                        'option'    => 'use_for',
                        'value'     => [ 'back' ]
                    ]
                ]
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
            'section_selector',
            [
                'type'      => 'text',
                'title'     => esc_html__('Enter Target Section Selector', 'ziultimate'),
                'description' => esc_html__('Enter HTML TAG or CSS Classname or ID. eg. html,body,.my-section, #my-div etc', 'ziultimate'),
                'dependency' => [
                    [
                        'option'    => 'use_for',
                        'value'     => [ 'scroll' ]
                    ]
                ]
            ]
        );

		$options->add_option(
			'offset',
			[
				'type' 		=> 'slider',
				'content' 	=> 'px',
				'default' 	=> 0,
				'min' 		=> -1000,
				'max' 		=> 1000,
				'step' 		=> 10,
				'title' 	=> esc_html__('Offset', 'ziultimate'),
				'dependency' => [
					[
						'option' 	=> 'use_for',
                        'value'     => [ 'scroll' ]
					]
				]
			]
		);

		$options->add_option(
			'fade_td',
			[
				'type' 		=> 'slider',
				'content' 	=> 's',
				'default' 	=> 0.3,
				'min' 		=> 0,
				'max' 		=> 10,
				'step' 		=> 0.1,
				'title' 	=> esc_html__('Transition Duration for Fade Effect', 'ziultimate'),
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}.backtotop',
						'value' 	=> 'transition-duration: {{VALUE}}s'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'use_for',
                        'value'     => [ 'back' ]
					]
				]
			]
		);

		$options->add_option(
			'backbtn_visibility',
			[
				'type' 		=> 'slider',
				'content' 	=> 'px',
				'default' 	=> 10,
				'min' 		=> 0,
				'max' 		=> 1000,
				'step' 		=> 10,
				'title' 	=> esc_html__('Visible after scroll', 'ziultimate'),
				'dependency' => [
					[
						'option' 	=> 'use_for',
                        'value'     => [ 'back' ]
					]
				]
			]
		);

		$options->add_option(
			'speed',
			[
				'type' 		=> 'slider',
				'content' 	=> 'ms',
				'default' 	=> 450,
				'min' 		=> 100,
				'max' 		=> 10000,
				'step' 		=> 50,
				'title' 	=> esc_html__('Scrolling Speed', 'ziultimate')
			]
		);

		$options->add_option(
			'easing',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('Easing'),
				'default' 	=> 'swing',
				'options' 	=> [
					[
						'name' 		=> __('Linear'),
						'id' 		=> 'linear'
					],
					[
						'name' 		=> __('Swing'),
						'id' 		=> 'swing'
					]
				]
			]
		);
    }

	/**
	 * Loading the scripts
	 */
	public function enqueue_scripts() {
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/BackToTop/editor.js' ) );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/BackToTop/frontend.js' ) );
	}

	/**
	 * Loading the css file
	 */
	public function enqueue_styles() {
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/BackToTop/frontend.css' ) );
	}

    public function before_render( $options ) {
        $use_for = $options->get_value('use_for', 'back') == 'back' ? 'backtotop' : 'jump-to-section';

		$data = array();
		if( $use_for == 'backtotop' ) {
			$data['visibility'] = $options->get_value( 'backbtn_visibility' );
			if( absint( $data['visibility'] ) <= 0 ) {
				$use_for .= ' backtotop-visible';
			}
		} else {
			$data['offset'] = $options->get_value( 'offset' );
			$data['selector'] = $options->get_value( 'section_selector' );
		}

		$data['speed'] = $options->get_value( 'speed' );
		$data['easing'] = $options->get_value( 'easing' );

		$this->render_attributes->add( 'wrapper', 'data-zuscroll-config', wp_json_encode( $data ) );

        $this->render_attributes->add( 'wrapper', 'class', $use_for );
    }

	/**
	 * Rendering the layout
	 */
    public function render( $options) {
		if( self::isBuilderEditor() && $options->get_value('hide_back_button') ) {
			return;
		}

        $children = $this->get_children_for_render();
        if( $children ) {
            echo $children;
		}
    }
}