<?php
namespace ZiUltimate\Elements\TableOfContents;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZionBuilder\Options\BaseSchema;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class TableOfContents
 *
 * @package ZiUltimate\Elements
 */
class TableOfContents extends UltimateElements {
	
	public function get_type() {
		return 'zu_toc';
	}

	public function get_name() {
		return __( 'Table of Contents', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'toc', 'table of contents' ];
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
	 * Creating the settings fields
	 * 
	 * @return void
	 */
	public function options( $options ) {
		$options->add_option(
			'contentSelector',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Content Selector', 'ziultimate' ),
				'default' 		=> '.zb-el-zionText',
				'description' 	=> esc_html__('Where to grab the headings to build the table of contents.', 'ziultimate')
			]
		);

		$options->add_option(
			'headingSelector',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__( 'Heading Selector', 'ziultimate' ),
				'default' 		=> 'h2, h3, h4',
				'description' 	=> esc_html__('Which headings to grab inside of the content selector element.', 'ziultimate')
			]
		);

		$options->add_option(
			'ignoreSelector',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__( 'Ignore Selector', 'ziultimate' ),
				'default' 		=> '.toc-ignore',
				'description' 	=> esc_html__('Headings that match the ignore selector will be skipped.', 'ziultimate')
			]
		);

		$options->add_option(
			'collapseDepth',
			[
				'type' 			=> 'slider',
				'content' 		=> ' ',
				'min' 			=> 0,
				'max' 			=> 6,
				'step' 			=> 1,
				'title' 		=> esc_html__( 'Collapse Depth for Sub Items', 'ziultimate' ),
				'default' 		=> 6,
				'description' 	=> __(
					'How many heading levels should not be collapsed. For example, number 6 will show everything since there are only 6 heading levels and number 0 will collapse them all. The sections that are hidden will open and close as you scroll to headings within them.', 
					'ziultimate'
				)
			]
		);

		$ss = $options->add_group(
			'smooth_scrolling_group',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__( 'Smooth Scrolling', 'ziultimate' ),
				'collapsed' => true
			]
		);

		$ss->add_option(
			'scrollSmooth',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__( 'Enable the smooth scrolling effect?', 'ziultimate' ),
				'default' 	=> true,
				'layout' 	=> 'inline'
			]
		);

		$ss->add_option(
			'scrollSmoothDuration',
			[
				'type' 			=> 'slider',
				'content' 		=> 'ms',
				'min' 			=> 0,
				'max' 			=> 10000,
				'step' 			=> 10,
				'title' 		=> esc_html__( 'Transition Duration', 'ziultimate' ),
				'default' 		=> 420,
			]
		);

		$ss->add_option(
			'scrollSmoothOffset',
			[
				'type' 			=> 'slider',
				'content' 		=> 'px',
				'min' 			=> 0,
				'max' 			=> 1000,
				'step' 			=> 5,
				'title' 		=> esc_html__( 'Smooth Scroll Offset', 'ziultimate' ),
				'default' 		=> 0,
			]
		);

		$ss->add_option(
			'headingsOffset',
			[
				'type' 			=> 'slider',
				'content' 		=> 'px',
				'min' 			=> 1,
				'max' 			=> 200,
				'step' 			=> 1,
				'title' 		=> esc_html__( 'Headings Offset', 'ziultimate' ),
				'description' 	=> esc_html__('To handle fixed headers with toc, just pass the header offsets as options to toc. For example, the options needed for a 40px tall fixed header would be 40. Default value is 1.', 'ziultimate'),
				'default' 		=> 1,
			]
		);

		/**
		 * List Items
		 */
		$items = $options->add_group(
			'list_items',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__( 'List Items', 'ziultimate' ),
				'collapsed' => true
			]
		);

		$items->add_option(
			'indent',
			[
				'type' 		=> 'slider',
				'content' 	=> 'px',
				'min' 		=> 0,
				'max' 		=> 50,
				'step' 		=> 1,
				'default' 	=> 15,
				'title' 	=> esc_html__('Sub Items Indent', 'ziultimate'),
				'responsive_options' => true,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .zutoc-list li li',
						'value' 	=> 'padding-left: {{VALUE}}px'
					]
				]
			]
		);

		$items->add_option(
			'list_type_style',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('List Type Style', 'ziultimate'),
				'options' 	=> [
					[
						'name' 		=> esc_html__('Counter', 'ziultimate'),
						'id' 		=> 'counter'
					],
					[
						'name' 		=> esc_html__('Icon'),
						'id' 		=> 'icon'
					],
					[
						'name' 		=> esc_html__('Only Text', 'ziultimate'),
						'id' 		=> 'text'
					]
				],
				'default' => 'text'
			]
		);

		$list_types = [
			[
				'name' 	=> esc_html__( 'None', 'ziultimate' ),
				'id' 	=> 'none'
			],
			[
				'name' 	=> esc_html__('Circle', 'ziultimate'),
				'id' 	=> 'circle'
			],
			[
				'name' 	=> esc_html__('Decimal', 'ziultimate'),
				'id' 	=> 'decimal'
			],
			[
				'name' 	=> esc_html__('Decimal - Leading Zero', 'ziultimate'),
				'id' 	=> 'decimal-leading-zero'
			],
			[
				'name' 	=> esc_html__('Disc', 'ziultimate'),
				'id' 	=> 'disc'
			],
			[
				'name' 	=> esc_html__('Upper Alpha', 'ziultimate'),
				'id' 	=> 'upper-alpha'
			],
			[
				'name' 	=> esc_html__('Lower Alpha', 'ziultimate'),
				'id' 	=> 'lower-alpha'
			],
			[
				'name' 	=> esc_html__('Upper Roman', 'ziultimate'),
				'id' 	=> 'upper-roman'
			],
			[
				'name' 	=> esc_html__('Lower Roman', 'ziultimate'),
				'id' 	=> 'lower-roman'
			]
		];

		$items->add_option(
			'parent_counter',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__('Enable Parent Counter?', 'ziultimate'),
				'options' 	=> [
					[
						'name' 		=> esc_html__('Yes', 'ziultimate'),
						'id' 		=> 'yes'
					],
					[
						'name' 		=> esc_html__('No'),
						'id' 		=> 'no'
					]
				],
				'default' => 'yes',
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['counter']
					]
				]
			]
		);

		$items->add_option(
			'parent_counter_sep',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Parent Counter Separator', 'ziultimate' ),
				'default' 		=> '.',
				'css_style' 	=> [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--parent-list-type-sep: "{{VALUE}}"'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> [ 'counter' ]
					],
					[
						'option' 	=> 'parent_counter',
						'value' 	=> [ 'yes' ]
					]
				]
			]
		);

		$items->add_option(
			'list_types_sep',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Counter Separator', 'ziultimate' ),
				'default' 		=> '.',
				'css_style' 	=> [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--list-type-sep: "{{VALUE}}"'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['counter']
					]
				]
			]
		);

		$items->add_option(
			'list_type',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('List Type for Top Level', 'ziultimate'),
				'default'	=> 'none',
				'options' 	=> $list_types,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--zutoc-list-type: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['counter']
					]
				]
			]
		);

		$items->add_option(
			'list_type_lvl2',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('List Type for 2nd Level', 'ziultimate'),
				'default'	=> 'none',
				'options' 	=> $list_types,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--zutoc-lvl2-list-type: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['counter']
					]
				]
			]
		);

		$items->add_option(
			'list_type_lvl3',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('List Type for 3rd Level', 'ziultimate'),
				'default'	=> 'none',
				'options' 	=> $list_types,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--zutoc-lvl3-list-type: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['counter']
					]
				]
			]
		);

		$items->add_option(
			'list_type_lvl4',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('List Type for 4th Level', 'ziultimate'),
				'default'	=> 'none',
				'options' 	=> $list_types,
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}}',
						'value' 	=> '--zutoc-lvl4-list-type: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['counter']
					]
				]
			]
		);

		$items->add_option(
			'list_type_icon',
			[
				'type' 		=> 'icon_library',
				'id' 		=> 'icon',
				'title' 	=> __('Icon'),
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['icon']
					]
				]
			]
		);

		$items->add_option(
			'icon_size',
			[
				'type' 			=> 'slider',
				'content' 		=> 'px',
				'min' 			=> 10,
				'max' 			=> 100,
				'step' 			=> 1,
				'title' 		=> esc_html__( 'Icon Size', 'ziultimate' ),
				'default' 		=> 12,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector'	=> '{{ELEMENT}} li span.list-icon',
						'value' 	=> 'font-size: {{VALUE}}px'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['icon']
					]
				]
			]
		);

		$items->add_option(
			'icon_color',
			[
				'type' 			=> 'colorpicker',
				'title' 		=> esc_html__( 'Icon Color', 'ziultimate' ),
				'width' 		=> 50,
				'css_style' 	=> [
					[
						'selector'	=> '{{ELEMENT}} li span.list-icon',
						'value' 	=> 'color: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['icon']
					]
				]
			]
		);

		$items->add_option(
			'icon_hover_color',
			[
				'type' 			=> 'colorpicker',
				'title' 		=> esc_html__( 'Icon Hover Color', 'ziultimate' ),
				'width' 		=> 50,
				'css_style' 	=> [
					[
						'selector'	=> '{{ELEMENT}} .zutoc-list li > a:hover span.list-icon',
						'value' 	=> 'color: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['icon']
					]
				]
			]
		);

		$items->add_option(
			'icon_gap',
			[
				'type' 			=> 'slider',
				'content' 		=> 'px',
				'min' 			=> 0,
				'max' 			=> 20,
				'step' 			=> 1,
				'title' 		=> esc_html__( 'Space between icon and text', 'ziultimate' ),
				'default' 		=> 8,
				'responsive_options' => true,
				'css_style' 	=> [
					[
						'selector'	=> '{{ELEMENT}} li span.list-icon',
						'value' 	=> 'margin-right: {{VALUE}}px'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'list_type_style',
						'value' 	=> ['icon']
					]
				]
			]
		);

		$items->add_option(
			'separator',
			[
				'type' 		=> 'html',
				'title' 	=> '',
				'content' 	=> '<hr style="border-color: #e8e8e8;border-style:solid;">'
			]
		);

		$items->add_option(
			'enable_indicator',
			[
				'type' 		=> 'custom_selector',
				'title' 	=> esc_html__( 'Enable Vertical Line Indicator?', 'ziultimate' ),
				'options' 	=> [
					[
						'name' 		=> esc_html__('Yes'),
						'id' 		=> 'yes'
					],
					[
						'name' 		=> esc_html__('No'),
						'id' 		=> 'no'
					]
				],
				'default' 	=> 'yes'
			]
		);

		$items->add_option(
			'brd_color',
			[
				'type' 			=> 'colorpicker',
				'title' 		=> esc_html__( 'Border Color', 'ziultimate' ),
				'width' 		=> 50,
				'css_style' 	=> [
					[
						'selector'	=> '{{ELEMENT}} .zu-toc-vertical-line .toc-link::after',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'enable_indicator',
						'value' 	=> ['yes']
					]
				]
			]
		);

		$items->add_option(
			'actv_brd_color',
			[
				'type' 			=> 'colorpicker',
				'title' 		=> esc_html__( 'Active Border Color', 'ziultimate' ),
				'width' 		=> 50,
				'css_style' 	=> [
					[
						'selector'	=> '{{ELEMENT}} .zu-toc-vertical-line .is-active-link::after',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'enable_indicator',
						'value' 	=> ['yes']
					]
				]
			]
		);

		$items->add_option(
			'border_width',
			[
				'type' 			=> 'slider',
				'content' 		=> 'px',
				'min' 			=> 0,
				'max' 			=> 20,
				'default' 		=> 2,
				'responsive_options' => true,
				'title' 		=> esc_html__( 'Border Width', 'ziultimate' ),
				'css_style' 	=> [
					[
						'selector'	=> '{{ELEMENT}} .zu-toc-vertical-line .toc-link::after',
						'value' 	=> 'width: {{VALUE}}px'
					]
				],
				'dependency' => [
					[
						'option' 	=> 'enable_indicator',
						'value' 	=> ['yes']
					]
				]
			]
		);
	}

	public function enqueue_scripts() {
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/TableOfContents/editor.js' ) );

		wp_enqueue_script('tocbot-min', Utils::get_file_url( 'dist/js/elements/TableOfContents/tocbot.min.js' ), array(), '4.12.0', true );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/TableOfContents/frontend.js' ) );
	}

	public function enqueue_styles() {
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/TableOfContents/frontend.css' ) );
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'li_styles',
			[
				'title'                   => esc_html__( 'Initial Li Tags', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zutoc-list > li',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'active_li_styles',
			[
				'title'                   => esc_html__( 'Active Li Tag', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zutoc-list > li.is-active-li',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'li_anchor_styles',
			[
				'title'                   => esc_html__( 'List Item Links', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zutoc-list li > a',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'ali_anchor_styles',
			[
				'title'                   => esc_html__( 'Active Item Link', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zutoc-list li.is-active-li > a',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'top_level_styles',
			[
				'title'                   => esc_html__( 'Top Level Items(H2)', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zutoc-list .node-name--H2',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'sec_level_styles',
			[
				'title'                   => esc_html__( '2nd Level Items(H3)', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zutoc-list .node-name--H3',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'third_level_styles',
			[
				'title'                   => esc_html__( '3rd Level Items(H4)', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zutoc-list .node-name--H4',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'fourth_level_styles',
			[
				'title'                   => esc_html__( '4th Level Items(H5)', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zutoc-list .node-name--H5',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'fifth_level_styles',
			[
				'title'                   => esc_html__( '5th Level Items(H6)', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zutoc-list .node-name--H6',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'counters_styles',
			[
				'title'                   => esc_html__( 'Counters', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zu-toc-list-type-counter .toc-link::before',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'icon_styles',
			[
				'title'                   => esc_html__( 'Icon Styles', 'ziultimate' ),
				'selector'                => '{{ELEMENT}} .zu-toc-list-type-icon li span.list-icon',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}

	/**
	 * Rendering the layout
	 */
	public function render( $options ) {
		$style = $options->get_value( 'list_type_style', 'text' );
		$isParentSep = $options->get_value( 'parent_counter', 'yes' );
		$parentSepStyle = ( $isParentSep == 'yes' ) ? ' zu-toc-parent-sep' : '';
		$hasIndicator = ( $options->get_value( 'enable_indicator', 'yes' ) == 'yes' ) ? ' zu-toc-vertical-line' : '';
		$data = [
			'tocSelector' 			=> '.zu-toc-container',
			'contentSelector' 		=> $options->get_value( 'contentSelector', '.zb-el-zionText' ),
			'headingSelector' 		=> $options->get_value( 'headingSelector', 'h2,h3,h4' ),
			'ignoreSelector' 		=> $options->get_value( 'ignoreSelector', '.toc-ignore' ),
			'collapseDepth'  		=> $options->get_value( 'collapseDepth', 6 ),
			'scrollSmooth'  		=> $options->get_value( 'scrollSmooth', true ),
			'scrollSmoothDuration' 	=> absint( $options->get_value( 'scrollSmoothDuration', 420 ) ),
			'scrollSmoothOffset' 	=> $options->get_value( 'scrollSmoothOffset', 0 ),
			'headingsOffset' 		=> $options->get_value( 'headingsOffset', 1 ),
			'nocontent' 			=> esc_html__('Nothing found', 'ziultimate'),
			'icon_html'				=> false
		];

		$icon = $options->get_value( 'list_type_icon', false );

		if ( ! empty( $icon ) && $style == 'icon' ) {
			$this->attach_icon_attributes( 'icon', $icon );
			$combined_icon_attr = $this->render_attributes->get_combined_attributes( 'icon_styles', [ 'class' => 'list-icon' ] );
			$data['icon_html'] = $this->get_render_tag(
				'span',
				'icon',
				'',
				$combined_icon_attr
			);
		}

		$this->render_tag(
			'div',
			'toc_container',
			(! self::isBuilderEditor() ) ? '&nbsp;' : $this->tocContentForBuilderEditor(),
			[
				'class' => 'zu-toc-container zu-toc-list-type-' . $style . $parentSepStyle . $hasIndicator,
				'data-zutoc-config' => wp_json_encode( $data )
			]
		);
	}

	private function tocContentForBuilderEditor() {
		ob_start();
		?>
		<ul class="zutoc-list">
			<li class="toc-list-item">
				<a href="#get-started" class="toc-link node-name--H2 is-active-link">Primary Heading(H2)</a>
				<ul class="zutoc-list">
					<li class="toc-list-item"><a class="toc-link node-name--H3">2nd Level 1(H3)</a></li>
					<li class="toc-list-item"><a class="toc-link node-name--H3">2nd Level 2(H3)</a></li>
				</ul>
			</li>
			<li class="toc-list-item"><a class="toc-link node-name--H2">Primary Heading(H2)</a></li>
			<li class="toc-list-item">
				<a class="toc-link node-name--H2">Primary Heading(H2)</a>
				<ul class="zutoc-list">
					<li class="toc-list-item"><a class="toc-link node-name--H3">2nd Level 1(H3)</a></li>
					<li class="toc-list-item">
						<a class="toc-link node-name--H3">2nd Level 2(H3)</a>
						<ul class="zutoc-list">
							<li class="toc-list-item"><a class="toc-link node-name--H4">3rd Level 1(H4)</a></li>
							<li class="toc-list-item"><a class="toc-link node-name--H4">3rd Level 2(H4)</a></li>
							<li class="toc-list-item"><a class="toc-link node-name--H4">3rd Level 3(H4)</a>
								<ul class="zutoc-list">
									<li class="toc-list-item"><a class="toc-link node-name--H5">4th Level 1(H5)</a></li>
									<li class="toc-list-item"><a class="toc-link node-name--H5">4th Level 2(H5)</a></li>
									<li class="toc-list-item"><a class="toc-link node-name--H5">4th Level 3(H5)</a></li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
			</li>
			<li class="toc-list-item"><a class="toc-link node-name--H2">Primary Heading</a></li>
		</ul>
		<?php
			return ob_get_clean();
	}
}