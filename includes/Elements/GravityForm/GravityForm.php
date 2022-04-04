<?php
namespace ZiUltimate\Elements\GravityForm;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;
use ZionBuilder\Options\BaseSchema;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class GravityForm
 *
 * @package ZiUltimate\Elements
 */
class GravityForm extends UltimateElements {
	
	public function get_type() {
		return 'zu_gf_styler';
	}

	public function get_name() {
		return __( 'Gravity Form Styler', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'gf', 'gravity', 'form', 'styler' ];
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

	/**
	 * Registering the options form
	 * 
	 * @return void
	 */
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
				'title' 	=> esc_html__( 'Source Type', 'ziultimate' ),
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
			'gf_form',
			[
				'type' 			=> 'select',
				'default' 		=> -1,
				'title' 		=> __( 'Gravity Form', 'ziultimate' ),
				'description' 	=> __( "Make sure that you build at least one gravity form.", 'ziultimate' ),
				'options' 		=> $this->getGravityForms(),
				'dependency'		=> [
					[
						'option' 	=> 'source_type',
						'value' 	=> [ 'static' ]
					]
				]
			]
		);

		$options->add_option(
			'gf_dymc_form',
			[
				'type' 			=> 'text',
				'title' 		=> __( 'Setup Form ID', 'ziultimate' ),
				'description' 	=> __( "Make sure that it returns the form ID.", 'ziultimate' ),
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
			'gform_title',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> __('Show title?', 'ziultimate'),
				'default' 	=> true,
				'layout' 	=> 'inline',
			]
		);

		$options->add_option(
			'gform_desc',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> __('Show description?', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline',
			]
		);

		$options->add_option(
			'gform_ajax',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> __('Enable Ajax?', 'ziultimate'),
				'default' 	=> true,
				'layout' 	=> 'inline',
			]
		);

		$options->add_option(
			'tab_index',
			[
				'type' 		=> 'text',
				'title' 	=> __('Tab Index', 'ziultimate'),
				'default' 	=> 10,
			]
		);

		$options->add_option(
			'gf_col_gap',
			[
				'type' 			=> 'number_unit',
				'min' 			=> 0,
				'default' 		=> '2%',
				'units' 		=> BaseSchema::get_units(),
				'title' 		=> __( 'Horizontal Gap', 'ziultimate' ),
				'description' 	=> __('Gap between columns', 'ziultimate'),
				'label-position' => 'left',
				'label-icon' 	=> 'horizontal',
				'label-title' 	=> esc_html__( 'Horizontal distance', 'zionbuilder' ),
				'width' 		=> 50,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .gform_wrapper.gravity-theme .gform_fields',
						'value'    => 'grid-column-gap: {{VALUE}}',
					],
					[
						'selector' => '{{ELEMENT}} .ginput_complex:not(.ginput_container_address) fieldset:not([style*="display:none"]):not(.ginput_full)',
						'value' 	=> 'padding-right: calc( {{VALUE}} / 2 )'
					],
					[
						'selector' => '{{ELEMENT}} .ginput_complex:not(.ginput_container_address) span:not([style*="display:none"]):not(.ginput_full)',
						'value' 	=> 'padding-right: calc( {{VALUE}} / 2 )'
					],
					[
						'selector' => '{{ELEMENT}} .ginput_complex:not(.ginput_container_address) fieldset:not([style*="display:none"]):not(.ginput_full)~span:not(.ginput_full)',
						'value' 	=> 'padding-left: calc( {{VALUE}} / 2 )'
					],
					[
						'selector' => '{{ELEMENT}} .ginput_complex:not(.ginput_container_address) span:not([style*="display:none"]):not(.ginput_full)~span:not(.ginput_full)',
						'value' 	=> 'padding-left: calc( {{VALUE}} / 2 )'
					],
					[
						'selector' => '{{ELEMENT}} .ginput_complex:not(.ginput_container_address) fieldset:not([style*="display:none"]):not(.ginput_full)~span:not(.ginput_full)',
						'value' 	=> 'padding-right: 0'
					],
					[
						'selector' => '{{ELEMENT}} .ginput_complex:not(.ginput_container_address) span:not([style*="display:none"]):not(.ginput_full)~span:not(.ginput_full)',
						'value' 	=> 'padding-right: 0'
					],					
				],
			]
		);

		$options->add_option(
			'gf_row_gap',
			[
				'type' 			=> 'number_unit',
				'units' 		=> BaseSchema::get_units(),
				'default' 		=> '16px',
				'title' 		=> __( 'Vertical Gap', 'ziultimate' ),
				'description' 	=> __('Gap between rows', 'ziultimate'),
				'label-position' => 'right',
				'label-icon' 	=> 'vertical',
				'label-title' 	=> esc_html__( 'Vertical distance', 'zionbuilder' ),
				'width' 		=> 50,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .gform_wrapper .gform_fields',
						'value'    => 'grid-row-gap: {{VALUE}}',
					]
				],
			]
		);

		$options->add_option(
			'gf_textarea',
			[
				'type' 			=> 'number_unit',
				'units' 		=> BaseSchema::get_units(),
				'title' 		=> __( 'Textarea Height', 'ziultimate' ),
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .gform_wrapper .gfield textarea',
						'value'    => 'height: {{VALUE}}!important',
					],
				],
			]
		);

		$options->add_option(
			'gfa_hide_labels',
			[
				'type' 			=> 'custom_selector',
				'title' 		=> __( 'Hide Sub Labels?', 'ziultimate' ),
				'default' 		=> 'block',
				'sync' 			=> '_styles.bottom_labels.styles.%%RESPONSIVE_DEVICE%%.default.display',
				'options' 		=> [
					[
						'name' 	=> __('Yes'),
						'id' 	=> 'none'
					],
					[
						'name' 	=> __('No'),
						'id' 	=> 'block'
					]
				]
			]
		);

		/*****************************
		 * Asterisk
		 *****************************/
		$asterisk = $options->add_group(
			'label_ast',
			[
				'type' 	=> 'accordion_menu',
				'title' => esc_html__('Asterisk/Required Text', 'ziultimate')
			]
		);

		$asterisk->add_option(
			'ast_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Color', 'zionbuilder' ),
				'css_style' => [
					[
						'selector' => '{{ELEMENT}} .gfield_required',
						'value'    => 'color: {{VALUE}}',
					],
				],
			]
		);

		$asterisk->add_option(
			'ast_size',
			[
				'type' 		=> 'number_unit',
				'title'     => __( 'Size', 'ziultimate' ),
				'units' 	=> BaseSchema::get_units(),
				'responsive_options' => true,
				'css_style' => [
					[
						'selector' => '{{ELEMENT}} .gfield_required',
						'value'    => 'font-size: {{VALUE}}',
					],
				],
			]
		);


		/**
		 * Radio & Checkboxes
		 */
		$cb_selector = '{{ELEMENT}} .gchoice .gfield-choice-input:after';
		$cb_checked_selector = '{{ELEMENT}} .gchoice .gfield-choice-input:checked:after';
		$cb = $options->add_group(
			'gf_cb',
			[
				'type' 	=> 'accordion_menu',
				'title' => esc_html__('Radio & Checkboxes', 'ziultimate')
			]
		);

		$cb->add_option(
			'cb_smart_ui',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> __('Enable Smart UI', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

		$cb->add_option(
			'cb_size',
			[
				'type' 		=> 'slider',
				'title' 	=> __( 'Size' ),
				'content' 	=> 'px',
				'default' 	=> 15,
				'min' 		=> 15,
				'max' 		=> 30,
				'step' 		=> 1,
				'css_style' => [
					[
						'selector' => $cb_selector,
						'value'    => 'width: {{VALUE}}px',
					],
					[
						'selector' => $cb_selector,
						'value'    => 'height: {{VALUE}}px',
					],
				],
				'dependency' 	=> [
					[
						'option' 	=> 'cb_smart_ui',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$cb->add_option(
			'cb_brd_wd',
			[
				'type' 		=> 'number_unit',
				'title' 	=> __( 'Border Width' ),
				'default' 	=> '1px',
				'min' 		=> 0,
				'units' 	=> BaseSchema::get_units(),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' => $cb_selector,
						'value'    => 'border-width: {{VALUE}}',
					],
				],
				'dependency' 	=> [
					[
						'option' 	=> 'cb_smart_ui',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$cb->add_option(
			'cb_brd_rd',
			[
				'type' 		=> 'number_unit',
				'title' 	=> __( 'Border Radius' ),
				'description' => __('This is for checkbox only.', 'ziultimate'),
				'min' 		=> 0,
				'units' 	=> BaseSchema::get_units(),
				'width' 	=> 50,
				'css_style' => [
					[
						'selector' => '{{ELEMENT}} .gchoice input[type=checkbox]:after',
						'value'    => 'border-radius: {{VALUE}}',
					]
				],
				'dependency' 	=> [
					[
						'option' 	=> 'cb_smart_ui',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$cb->add_option(
			'cb_brd_clr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Border Color' ),
				'css_style' => [
					[
						'selector' => $cb_selector,
						'value'    => 'border-color: {{VALUE}}',
					],
				],
				'dependency' 	=> [
					[
						'option' 	=> 'cb_smart_ui',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$cb->add_option(
			'cb_bg_clr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Background Color' ),
				'css_style' => [
					[
						'selector' => $cb_selector,
						'value'    => 'background-color: {{VALUE}}',
					],
				],
				'dependency' 	=> [
					[
						'option' 	=> 'cb_smart_ui',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$cb->add_option(
			'cb_chbrd_clr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Checked Border Color' ),
				'css_style' => [
					[
						'selector' => $cb_checked_selector,
						'value'    => 'border-color: {{VALUE}}',
					],
				],
				'dependency' 	=> [
					[
						'option' 	=> 'cb_smart_ui',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$cb->add_option(
			'cb_chbg_clr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Checked Background Color' ),
				'css_style' => [
					[
						'selector' => $cb_checked_selector,
						'value'    => 'background-color: {{VALUE}}',
					],
				],
				'dependency' 	=> [
					[
						'option' 	=> 'cb_smart_ui',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$cb->add_option(
			'cb_cb_size',
			[
				'type' 		=> 'slider',
				'title' 	=> __( 'White Check Mark Size', 'ziultimate' ),
				'content' 	=> 'px',
				'default' 	=> 9,
				'min' 		=> 9,
				'max' 		=> 30,
				'step' 		=> 1,
				'css_style' => [
					[
						'selector' => "{{ELEMENT}} .gchoice input[type=checkbox]:after",
						'value'    => 'background-size: {{VALUE}}px',
					]
				],
				'dependency' 	=> [
					[
						'option' 	=> 'cb_smart_ui',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$cb->add_option(
			'cb_bt_size',
			[
				'type' 		=> 'slider',
				'title' 	=> __( 'White Bullet Size (radio button)', 'ziultimate' ),
				'content' 	=> 'px',
				'default' 	=> 9,
				'min' 		=> 9,
				'max' 		=> 30,
				'step' 		=> 1,
				'css_style' => [
					[
						'selector' => "{{ELEMENT}} .gchoice input[type=radio]:after",
						'value'    => 'background-size: {{VALUE}}px',
					]
				],
				'dependency' 	=> [
					[
						'option' 	=> 'cb_smart_ui',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$cb_mrg = $cb->add_option(
			'cb_mrg',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> __( 'Margin' ),
				'dependency' 	=> [
					[
						'option' 	=> 'cb_smart_ui',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$this->attach_margin_options( $cb_mrg, 'cb_mrg', "{{ELEMENT}} .gchoice .gfield-choice-input" );

		/**
		 * Address Fields
		 */
		$address = $options->add_group(
			'label_address',
			[
				'type' 	=> 'accordion_menu',
				'title' => esc_html__('Address Fields', 'ziultimate')
			]
		);

		$address->add_option(
			'gfa_row_gap',
			[
				'type' 			=> 'number_unit',
				'units' 		=> BaseSchema::get_units(),
				'default' 		=> '8px',
				'title' 		=> __( 'Row Gap', 'ziultimate' ),
				'description' 	=> __('Gap between rows', 'ziultimate'),
				'label-position' => 'right',
				'label-icon' 	=> 'vertical',
				'label-title' 	=> esc_html__( 'Vertical distance', 'zionbuilder' ),
				'width' 		=> 50,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .ginput_container_address span:not(.ginput_full):not(:last-of-type):not(:nth-last-of-type(2))',
						'value'    => 'margin-bottom: {{VALUE}}',
					],
					[
						'selector' => '{{ELEMENT}} .ginput_full:not(:last-of-type)',
						'value'    => 'margin-bottom: {{VALUE}}',
					],
				],
			]
		);

		$address->add_option(
			'gfa_col_gap',
			[
				'type' 			=> 'number_unit',
				'min' 			=> 0,
				'default' 		=> '4px',
				'units' 		=> BaseSchema::get_units(),
				'title' 		=> __( 'Columns Gap', 'ziultimate' ),
				'description' 	=> __('Gap between columns', 'ziultimate'),
				'label-position' => 'left',
				'label-icon' 	=> 'horizontal',
				'label-title' 	=> esc_html__( 'Padding Right', 'zionbuilder' ),
				'width' 		=> 50,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector' => '{{ELEMENT}} .ginput_container_address .ginput_left',
						'value' 	=> 'padding-right: {{VALUE}}'
					],
					[
						'selector' => '{{ELEMENT}} .ginput_container_address .ginput_right',
						'value' 	=> 'padding-left: {{VALUE}}'
					],
					
				],
			]
		);
	}

	/**
	 * Loading the styles
	 * 
	 * @return void
	 */
	public function enqueue_styles() {
		$this->enqueue_editor_style( Utils::get_file_url( 'dist/css/elements/GravityForm/editor.css' ) );
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/GravityForm/gravityform.css' ) );
	}

	/**
	 * Loading the scripts
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/GravityForm/editor.js' ) );
	}

	/**
	 * Adding some attributes
	 * 
	 * @return void
	 */
	public function before_render( $options ) {
		$cb_smart_ui = $options->get_value('cb_smart_ui', false );

		if( $cb_smart_ui ) {
			$this->render_attributes->add( 'wrapper', 'class', 'zu-gf-cbui' );
		}
	}

	/**
	 * Rendering the form
	 * 
	 * @return void
	 */
	public function render( $options ) {
		$source_type = $options->get_value('source_type', 'static');

		if( $source_type == 'dynamic' ) {
			$gf_id = $options->get_value('gf_dymc_form');
		} else {
			$gf_id = $options->get_value('gf_form', false);
		}

		if( ! $gf_id || $gf_id <= 0 ) {
			echo '<h5 class="form-missing">' . __("Select a form", 'ziultimate') . '</h5>';
			return;
		}

		$title = $options->get_value('gform_title', true) ? "true" : "false";
		$desc = $options->get_value('gform_desc', false) ? "true" : "false";
		$ajax = $options->get_value('gform_ajax', true) ? "true" : "false";
		$tab_index = $options->get_value('tab_index', 10);

		if ( $this->isBuilderEditor() ) {
			$form = \GFAPI::get_form( $gf_id );
			require_once( \GFCommon::get_base_path() . '/form_display.php' );
			\GFFormDisplay::print_form_scripts( $form, $ajax );
		}

		echo do_shortcode('[gravityform id='. $gf_id .' title="' . $title . '" description="' . $desc . '" ajax="' . $ajax . '" tabindex="' . $tab_index . '"]' );
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'form_title',
			[
				'title'                   => esc_html__( 'Form Title', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gform_title',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'form_desc',
			[
				'title'                   => esc_html__( 'Form Description', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gform_description',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'form_label',
			[
				'title'                   => esc_html__( 'Form Label', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gform_wrapper .gfield_label',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'form_cb_label',
			[
				'title'                   => __( 'Radio & Checkbox Label', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gchoice label',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'bottom_labels',
			[
				'title'                   => esc_html__( 'Sub Labels', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gfield_header_item,{{ELEMENT}} .gform_fileupload_rules,{{ELEMENT}} .gform_wrapper.gravity-theme form .ginput_complex label',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$gfinp_selector = '{{ELEMENT}} .gform_wrapper .gfield textarea, {{ELEMENT}} .gform_wrapper .gfield input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])';

		$this->register_style_options_element(
			'form_input',
			[
				'title'                   => esc_html__( 'Input Fields', 'ziultimate' ),
				'selector'                => $gfinp_selector,
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'form_dropdown',
			[
				'title'                   => esc_html__( 'Dropdown Fields', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gform_wrapper .gfield select',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'form_input_desc',
			[
				'title'                   => esc_html__( 'Input Fields Description', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gfield_description',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'inp_val_err',
			[
				'title'                   => esc_html__( 'Invalid Input Fields', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gform_wrapper .gfield_error [aria-invalid=true]',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'form_sec',
			[
				'title'                   => esc_html__( 'Section', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gform_fields > li.gsection',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'form_sec_title',
			[
				'title'                   => esc_html__( 'Section Title', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gsection_title',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'form_sec_desc',
			[
				'title'                   => esc_html__( 'Section Description', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gsection_description',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);		

		$this->register_style_options_element(
			'form_submit',
			[
				'title'                   => esc_html__( 'Submit Button', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gform_button',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'top_err_msg',
			[
				'title'                   => esc_html__( 'Top Errors Message Box', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gform_validation_errors',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'top_err_text',
			[
				'title'                   => esc_html__( 'Top Errors Message Text', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gform_submission_error',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'val_err',
			[
				'title'                   => esc_html__( 'Invalid Error Message', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .validation_message',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'success_msg',
			[
				'title'                   => esc_html__( 'Success Message', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .gform_confirmation_message',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}
}