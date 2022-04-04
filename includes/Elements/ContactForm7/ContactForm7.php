<?php

namespace ZiUltimate\Elements\ContactForm7;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;
use ZionBuilder\Options\BaseSchema;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ContactForm7
 *
 * @package ZiUltimate\Elements
 */
class ContactForm7 extends UltimateElements {
	
	public function get_type() {
		return 'zu_cf7_styler';
	}

	public function get_name() {
		return __( 'Contact Form 7 Styler', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'cf7', 'contact', 'form', 'contactform', 'styler' ];
	}

	public function get_element_icon() {
		return 'element-form';
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

	public function options( $options ) {
		if( ! License::has_valid_license() ) {
			$title = $this->get_name();
			$description = 'You can directly customize your form style on builder editor.';
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
			'source_type',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__( 'Source Type', 'zicoustomelements' ),
				'default' 	=> 'static',
				'options' 	=> [
					[
						'name' => 'Static',
						'id'   => 'static',
					],
					[
						'name' => 'Custom Field',
						'id'   => 'dynamic',
					]
				],
			]
		);

		$options->add_option(
			'cf_form',
			[
				'type' 			=> 'select',
				'default' 		=> -1,
				'title' 		=> __( 'Contact Form 7', 'ziultimate' ),
				'description' 	=> __( "Make sure that you build atleast one contact form.", 'ziultimate' ),
				'options' 		=> $this->getCF7Forms(),
				'dependency'		=> [
					[
						'option' 	=> 'source_type',
						'value' 	=> [ 'static' ]
					]
				]
			]
		);

		$options->add_option(
			'cf7_dymc_form',
			[
				'type' 			=> 'text',
				'title' 		=> __( 'Setup Form ID', 'ziultimate' ),
				'description' 	=> __( "Make sure that it returns the contact form 7 ID.", 'ziultimate' ),
				'dependency'		=> [
					[
						'option' 	=> 'source_type',
						'value' 	=> [ 'dynamic' ]
					]
				],
				'dynamic'     	=> [
					'enabled' => true,
				]
			]
		);

		$options->add_option(
			'show_title',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> __('Show Form Ttile', 'ziultimate' ),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

		$options->add_option(
			'title_tag',
			[
				'type' 		=> 'select',
				'title'		=> __('Form Title Tag', 'ziultimate'),
				'default' 	=> 'h4',
				'options' 	=> [
					[
						'name' 	=> 'H1',
						'id' 	=> 'h1'
					],
					[
						'name' 	=> 'H2',
						'id' 	=> 'h2'
					],
					[
						'name' 	=> 'H3',
						'id' 	=> 'h3'
					],
					[
						'name' 	=> 'H4',
						'id' 	=> 'h4'
					],
					[
						'name' 	=> 'H5',
						'id' 	=> 'h5'
					],
					[
						'name' 	=> 'H6',
						'id' 	=> 'h6'
					],
					[
						'name' 	=> 'DIV',
						'id' 	=> 'div'
					],
					[
						'name' 	=> 'P',
						'id' 	=> 'p'
					]
				],
				'dependency' 	=> [
					[
						'option' 	=> 'show_title',
						'value' 	=> [true]
					]
				]
			]
		);

		$options->add_option(
			'cf7_textarea',
			[
				'type' 			=> 'number_unit',
				'units' 		=> BaseSchema::get_units(),
				'title' 		=> __( 'Textarea Height', 'ziultimate' ),
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .wpcf7 .wpcf7-form-control.wpcf7-textarea',
						'value'    => 'height: {{VALUE}}!important',
					],
				],
			]
		);
		
	}

	public function render( $options ) {
		
		$show_form_title = $options->get_value('show_title', false);

		$source_type = $options->get_value('source_type', 'static');
		if( $source_type == 'dynamic' ) {
			$cf7_id = $options->get_value('cf7_dymc_form');
		} else {
			$cf7_id = $options->get_value('cf_form', false);
		}

		if( ! $cf7_id || $cf7_id <= 0 ) {
			echo '<h5 class="form-missing">' . __("Select a form", 'ziultimate') . '</h5>';
			return;
		}

		if( $show_form_title ) {
			$tag = $options->get_value('title_tag', 'h4');
			$title = get_post_field( 'post_title', $cf7_id );
			$this->render_tag(
				$tag,
				'form_title',
				$title,
				[
					'class' => 'wpcf7-form-title'
				]
			);
		}

		echo do_shortcode('[contact-form-7 id='. $cf7_id .']');
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'cf7_form_title',
			[
				'title'                   => esc_html__( 'Form Title', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .wpcf7-form-title',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'cf7_label',
			[
				'title'                   => esc_html__( 'Form Labels', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .wpcf7-form label',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'cf7_input_field',
			[
				'title'                   => esc_html__( 'Input fields', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .wpcf7-form-control:not(.wpcf7-select):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):not(.wpcf7-submit):not(.wpcf7-acceptance)',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'cf7_select_field',
			[
				'title'                   => esc_html__( 'Select fields', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .wpcf7-form-control.wpcf7-select',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'cf7_submit_button',
			[
				'title'                   => esc_html__( 'Submit Button', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .wpcf7-submit',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'cf7_input_error',
			[
				'title'                   => esc_html__( 'Input Fields Error Message', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .wpcf7-not-valid-tip',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'cf7_invalid_error',
			[
				'title'                   => esc_html__( 'Validation Error Message', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} form.invalid .wpcf7-response-output, {{ELEMENT}} .wpcf7 form.unaccepted .wpcf7-response-output',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'cf7_suc_msg',
			[
				'title'                   => esc_html__( 'Success Message', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .wpcf7-mail-sent-ok',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}
}