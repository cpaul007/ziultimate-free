<?php
namespace ZiUltimate\Elements\InfiniteScroll;

use ZiUltimate\UltimateElements;
use ZiUltimate\Admin\License;
use ZiUltimate\Utils;
use ZionBuilder\Options\BaseSchema;
use ZionBuilderPro\Plugin;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class InfiniteScroll
 *
 * @package ZiUltimate\Elements
 */
class InfiniteScroll extends UltimateElements {
	
	public function get_type() {
		return 'zu_infinite_scroll';
	}

	public function get_name() {
		return __( 'Infinite Scroll', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'infinite', 'infinite scroll', 'scroll' ];
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
			$description = 'It automatically adds the next page, saving users from a full page load.';
			$options->add_option(
				'el',
				[
					'type' 		=> 'html',
					'content' 	=> self::getHTMLContent($title, $description)
				]
			);

			return;
		}

		/**
		 * Important Note section
		 */
		$options->add_option(
			'note',
			[
				'type' 		=> 'html',
				'title' 	=> esc_html__( 'Note' ),
				'content' 	=> '<p>' . esc_html__('Live preview is disabled for Builder Editor.', 'ziultimate') . 
								'</p><hr style="border:1px solid #e5e5e5;"/>'
			]
		);


		/**
		 * Default config
		 */
		$options->add_option(
			'provider_selector',
			[
				'type' 		=> 'text',
				'title' 	=> esc_html__('Repeater Provider Selector', 'ziultimate'),
				'default' 	=> '.zu-infinite-scroll-container'
			]
		);

		$options->add_option(
			'consumer_selector',
			[
				'type' 		=> 'text',
				'title' 	=> esc_html__('Repeater Consumer Selector', 'ziultimate'),
				'default' 	=> '.zb-column'
			]
		);

		$options->add_option(
			'history',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('History', 'ziultimate'),
				'description' 	=> esc_html__('Changes page URL and browser history. Default is disabled.', 'ziultimate'),
				'default' 	=> 'disabled',
				'options' 	=> [
					[
						'name' 	=> __('Disabled', 'ziultimate'),
						'id' 	=> 'disabled'
					],
					[
						'name' 	=> __('Push', 'ziultimate'),
						'id' 	=> 'push'
					],
					[
						'name' 	=> __('Replace', 'ziultimate'),
						'id' 	=> 'replace'
					]
				]
			]
		);

		$options->add_option(
			'outlayer',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('Extra Support', 'ziultimate'),
				'default' 	=> 'none',
				'options' 	=> [
					[
						'name' 	=> __('None', 'ziultimate'),
						'id' 	=> 'none'
					],
					[
						'name' 	=> __('isotope', 'ziultimate'),
						'id' 	=> 'iso'
					],
					[
						'name' 	=> __('masonry', 'ziultimate'),
						'id' 	=> 'msnry'
					]
				]
			]
		);

		$options->add_option(
			'layout_mode',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('Layout Mode', 'ziultimate'),
				'default' 	=> 'fitRows',
				'options' 	=> [
					[
						'name' 	=> __('Fit Rows', 'ziultimate'),
						'id' 	=> 'fitRows'
					],
					[
						'name' 	=> __('Masonry', 'ziultimate'),
						'id' 	=> 'masonry'
					]
				],
				'dependency' 	=> [
					[
						'option' 	=> 'outlayer',
						'value' 	=> [ 'iso' ]
					]
				]
			]
		);

		$options->add_option(
			'msnry_animation',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('Animation', 'ziultimate'),
				'default' 	=> 'zoom',
				'options' 	=> [
					[
						'name' 	=> __('Zoom', 'ziultimate'),
						'id' 	=> 'zoom'
					],
					[
						'name' 	=> __('Fade In', 'ziultimate'),
						'id' 	=> 'fadein'
					],
					[
						'name' 	=> __('Move Up', 'ziultimate'),
						'id' 	=> 'moveup'
					]
				],
				'dependency' 	=> [
					[
						'option' 	=> 'outlayer',
						'value' 	=> [ 'msnry' ]
					]
				]
			]
		);

		$options->add_option(
			'td',
			[
				'type' 		=> 'slider',
				'content' 	=> 's',
				'default' 	=> 0.4,
				'min' 		=> 0,
				'max' 		=> 10,
				'step' 		=> 0.1,
				'title' 	=> esc_html__('Transition Duration', 'ziultimate'),
				'dependency' 	=> [
					[
						'option' 	=> 'outlayer',
						'value' 	=> [ 'msnry' ]
					]
				]
			]
		);


		/*$options->add_option(
			'column_width',
			[
				'type' 		=> 'number_unit',
				'units' 	=> BaseSchema::get_units(),
				'default' 	=> '32%',
				'width' 	=> 50,
				'title' 	=> esc_html__('Columns Width', 'ziultimate'),
				'dependency' 	=> [
					[
						'option' 	=> 'outlayer',
						'value' 	=> [ 'iso', 'msnry' ]
					]
				],
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .column-width',
						'value' 	=> 'width: {{VALUE}}'
					]
				],
				'responsive_options' => true,
			]
		);

		$options->add_option(
			'gutter_size',
			[
				'type' 		=> 'number_unit',
				'units' 	=> BaseSchema::get_units(),
				'default' 	=> '2%',
				'width' 	=> 50,
				'title' 	=> esc_html__('Gutter Size', 'ziultimate'),
				'dependency' 	=> [
					[
						'option' 	=> 'outlayer',
						'value' 	=> [ 'iso', 'msnry' ]
					]
				],
				'css_style' => [
					[
						'selector' 	=> '{{ELEMENT}} .gutter-size',
						'value' 	=> 'width: {{VALUE}}'
					]
				],
				'responsive_options' => true,
			]
		);*/



		/**
		 * Trigger Events settings
		 */
		$events = $options->add_group(
			'events',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Trigger Event Settings'),
				'collapsed' => true
			]
		);

		$events->add_option(
			'trigger_event',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Add "Load More" Button?', 'ziultimate'),
				'description' 	=> esc_html__('It enables a button to load content on click.', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

		$events->add_option(
			'scrollThreshold',
			[
				'type' 		=> 'slider',
				'content' 	=> 'px',
				'default' 	=> 50,
				'min' 		=> 0,
				'max' 		=> 10000,
				'step' 		=> 1,
				'title' 	=> esc_html__('Scroll Threshold', 'ziultimate'),
				'description' 	=> esc_html__( 'trigger scrollThreshold event when viewport is < 50px from bottom of scroll area', 'ziultimate' ),
				'dependency' 	=> [
					[
						'option'	=> 'trigger_event',
						'value' 	=> [ false ]
					]
				]
			]
		);

		$events->add_option(
			'button_type',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('Button Source', 'ziultimate'),
				'default' 	=> 'inbuilt',
				'options' 	=> [
					[
						'name' 	=> __('Inbuilt Button', 'ziultimate'),
						'id' 	=> 'inbuilt'
					],
					[
						'name' 	=> __('Create Custom Button', 'ziultimate'),
						'id' 	=> 'custom'
					],
				],
				'dependency' 	=> [
					[
						'option'	=> 'trigger_event',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$events->add_option(
			'button_text',
			[
				'type' 		=> 'text',
				'title' 	=> esc_html__('Button Text', 'ziultimate'),
				'default' 	=> esc_html__( 'Load More', 'ziultimate' ),
				'dependency' 	=> [
					[
						'option'	=> 'trigger_event',
						'value' 	=> [ true ]
					],
					[
						'option'	=> 'button_type',
						'value' 	=> [ 'inbuilt' ]
					]
				],
				'dynamic' 	=> [
					'enabled' 	=> true
				]
			]
		);

		$events->add_option(
			'btn_icon',
			[
				'type'       => 'icon_library',
				'id'         => 'icon',
				'title'      => esc_html__( 'Icon for Button', 'zionbuilder' ),
				'dependency' 	=> [
					[
						'option'	=> 'trigger_event',
						'value' 	=> [ true ]
					],
					[
						'option'	=> 'button_type',
						'value' 	=> [ 'inbuilt' ]
					]
				],
				'dynamic' 	=> [
					'enabled' 	=> true
				]
			]
		);

		$events->add_option(
			'button_selector',
			[
				'type' 		=> 'text',
				'title' 	=> esc_html__('Enter Button Selector', 'ziultimate'),
				'placeholder' 	=> esc_html__( '.view-more', 'ziultimate' ),
				'dependency' 	=> [
					[
						'option'	=> 'trigger_event',
						'value' 	=> [ true ]
					],
					[
						'option'	=> 'button_type',
						'value' 	=> [ 'custom' ]
					]
				],
				'dynamic' 	=> [
					'enabled' 	=> true
				]
			]
		);

		$events->add_option(
			'hide_ldm_btn',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Hide button for builder editor only', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline',
				'dependency' 	=> [
					[
						'option'	=> 'trigger_event',
						'value' 	=> [ true ]
					],
					[
						'option'	=> 'button_type',
						'value' 	=> [ 'inbuilt' ]
					]
				]
			]
		);

		$events->add_option(
			'element_scroll',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Custom Scroller Element Selector', 'ziultimate'),
				'placeholder' 	=> '.sidebar',
				"description" 	=> esc_html__("Ensure this container element has overflow set to auto and has a set height.", "ziultimate" )
			]
		);


		/**
		 * Loading spinner settings
		 */
		$spinner = $options->add_group(
			'loading',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Loading Spinner Settings', 'ziultimate'),
				'collapsed' => true
			]
		);

		$spinner->add_option(
			'loader',
			[
				'type' 		=> 'select',
				'title' 	=> esc_html__('Loading Spinner', 'ziultimate'),
				'default' 	=> 'ellipsis',
				'options' 	=> [
					[
						'name' 	=> __('Ellipsis', 'ziultimate'),
						'id' 	=> 'ellipsis'
					],
					[
						'name' 	=> __('Ring', 'ziultimate'),
						'id' 	=> 'ring'
					],
					[
						'name' 	=> __('Ripple', 'ziultimate'),
						'id' 	=> 'ripple'
					],
					[
						'name' 	=> __('Roller', 'ziultimate'),
						'id' 	=> 'roller'
					],
					[
						'name' 	=> __('Roller 2', 'ziultimate'),
						'id' 	=> 'roller2'
					],
					[
						'name' 	=> __('Spinner', 'ziultimate'),
						'id' 	=> 'spinner'
					]
				]
			]
		);

		$spinner->add_option(
			'spinner_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__('Color', 'ziultimate'),
				'default' 	=> '#555555',
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}}",
						'value' 	=> "--spinner-color: {{VALUE}}"
					]
				]
			]
		);

		$spinner->add_option(
			'hide_loader',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Hide for builder editor only', 'ziultimate'),
				'description' => esc_html__('It will always show when "no more content" and "load more" button both are hidden.', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);



		/**
		 * No more content to load
		 * Add custom text
		 */
		$endmsg = $options->add_group(
			'end_status',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Finished Message', 'ziultimate'),
				'collapsed' => true
			]
		);

		$endmsg->add_option(
			'show_end_msg',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Display no more content text?', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

		$endmsg->add_option(
			'hide_msg_builder',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Hide for builder editor only', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline',
				'dependency' 	=> [
					[
						'option'	=> 'show_end_msg',
						'value' 	=> [ true ]
					]
				]
			]
		);

		$endmsg->add_option(
			'end_text',
			[
				'type' 		=> 'textarea',
				'title' 	=> esc_html__('Enter Text', 'ziultimate'),
				'default' 	=> esc_html__('No more content to load', 'ziultimate'),
				'dependency' 	=> [
					[
						'option'	=> 'show_end_msg',
						'value' 	=> [ true ]
					]
				]
			]
		);
	}

	public function get_query() {
		$active_repeater_provider = Plugin::instance()->repeater->get_active_provider();

		if ($active_repeater_provider) {
			$query_config = $active_repeater_provider->get_query();

			return isset( $query_config['query'] ) ? $query_config['query'] : false;
		}

		if ( isset( $provider_data['query'] ) ) {
			return $provider_data['query'];
		}

		return false;
	}

	protected function can_render() {
		if( ! License::has_valid_license() )
			return false;
		
		$query = $this->get_query();
		if ( $query && (int) $query->max_num_pages === 1 ) {
			return false;
		}

		return true;
	}

	private function get_next_posts_link( $support ) {
		global $paged;

		$query = $this->get_query();

		$max_page = $query->max_num_pages;

		if ( ! $paged ) {
			$paged = 1;
		}

		$nextpage = (int) $paged + 1;
		$label = __( 'Next Page &raquo;' );

		if( $nextpage <= $max_page ) {
			echo '<nav class="pagination infinite-scroll-pagination">';
			echo '<span class="pagination__current screen-reader-text">Page '. $paged .'</span>';
			echo '<a href="' . \next_posts( $max_page, false ) . '" class="pagination__next">' . 
					preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label ) . 
				'</a>';
			echo '</nav>';
		}
	}

	/**
	 * Loaing the Scripts
	 */
	public function enqueue_scripts() {
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/InfiniteScroll/editor.js' ) );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/InfiniteScroll/frontend.js' ) );
		wp_enqueue_script('zu-infinitescroll', Utils::get_file_url( 'assets/js/infinite-scroll.pkgd.min.js' ), array(), '1.4', true );
	}

	/**
	 * Loading the Scripts
	 */
	public function enqueue_styles() {
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/InfiniteScroll/frontend.css' ) );
	}

	/**
	 * Registering the styles
	 */
	public function on_register_styles() {
		$this->register_style_options_element(
			'no_more_posts_styles',
			[
				'title'    => esc_html__( 'Finished Message', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .no-more-posts',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'button_styles',
			[
				'title'    => esc_html__( 'Load More Button', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .zu-load-more',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'icon_styles',
			[
				'title'    => esc_html__( 'Load More Button Icon', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .zu-lm-button__icon',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}

	public function before_render( $options ) {
		$history = $options->get_value('history', 'disabled');
		$load_more = $options->get_value('trigger_event', false);
		$element_scroll = $options->get_value( 'element_scroll', false );
		$button_type = $options->get_value('button_type', 'inbuilt' );
		$button_selector = $options->get_value('button_selector', false );
		$outlayer = $options->get_value( 'outlayer' );
		$data = [
			'provider_selector' => $options->get_value('provider_selector'),
			'consumer_selector' => $options->get_value('consumer_selector'),
			'history' 			=> ($history == 'disabled') ? false : $history,
			'scrollThreshold' 	=> $options->get_value( 'scrollThreshold', 50),
			'disable_scroll' 	=> ! empty( $load_more ) ? true : false,
			'element_scroll' 	=> ! empty( $element_scroll ) ? $element_scroll : false,
			'button_selector'	=> ( ! empty( $button_selector ) && $button_type == 'custom' ) ? $button_selector : false,
			'outlayer' 			=> $outlayer,
			'layout_mode'		=> $options->get_value('layout_mode', 'fitRows'),
			'msnry_animation' 	=> $options->get_value('msnry_animation', 'zoom'),
			'td' 				=> $options->get_value('td', '0.4') . 's',
		];

		if( $outlayer != 'none' ) {
			$this->render_attributes->add( 'wrapper', 'class', 'outlayer-enabled' );
		}

		$this->render_attributes->add( 'wrapper', 'data-infscroll-config', wp_json_encode( $data ) );
	}

	public function render( $options ) {
		$support = $options->get_value( 'outlayer' );
		$this->get_next_posts_link( $support );
	?>
		<div class="page-load-status">
			<?php
				$loader = $options->get_value('loader');

				if( $loader == 'ellipsis' ):
			?>
				<div class="lds-ellipsis infinite-scroll-request">
					<span></span><span></span><span></span><span></span>
				</div>
			<?php endif; ?>

			<?php if( $loader == 'ring' ): ?>
				<div class="lds-ring infinite-scroll-request">
					<span></span><span></span><span></span><span></span>
				</div>
			<?php endif; ?>

			<?php if( $loader == 'ripple' ): ?>
				<div class="lds-ripple infinite-scroll-request">
					<span></span><span></span>
				</div>
			<?php endif; ?>

			<?php if( $loader == 'roller' ): ?>
				<div class="lds-roller infinite-scroll-request">
					<span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
				</div>
			<?php endif; ?>

			<?php if( $loader == 'roller2' ): ?>
				<div class="lds-default infinite-scroll-request">
					<span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
				</div>
			<?php endif; ?>

			<?php if( $loader == 'spinner' ): ?>
				<div class="lds-spinner infinite-scroll-request">
					<span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
				</div>
			<?php endif; ?>
			<?php 
				$show_end_msg = $options->get_value('show_end_msg', false );
				if( $show_end_msg ):
					$end_msg = $options->get_value('end_text');
			?>
				<p class="infinite-scroll-last no-more-posts"><?php echo $end_msg; ?></p>
			<?php endif; ?>
		</div>
	<?php
		$load_more = $options->get_value('trigger_event', false );
		$button_type = $options->get_value('button_type', 'inbuilt' );
		if( ! empty( $load_more ) && $button_type == 'inbuilt' ) {
			$icon_html 			= '';
			$button_text_html 	= '';
			$button_text 		= $options->get_value('button_text', false);
			$icon 				= $options->get_value( 'btn_icon', false );
			$combined_button_attr = $this->render_attributes->get_combined_attributes( 'button_styles', [ 'class' => 'zu-load-more', 'aria-label' => __('Load More', 'ziultimate') ] );
			$combined_icon_attr   = $this->render_attributes->get_combined_attributes( 'icon_styles', [ 'class' => 'zu-lm-button__icon' ] );

			if ( ! empty( $icon ) ) {
				$this->attach_icon_attributes( 'icon', $icon );
				$icon_html = $this->get_render_tag(
					'span',
					'icon',
					'',
					$combined_icon_attr
				);
			}

			if( ! empty( $button_text ) ) {
				$button_text_html = $this->get_render_tag(
					'span',
					'button_text',
					$button_text,
					[
						'class' => 'zu-load-more-button__text',
					]
				);
			}

			$this->render_tag(
				'button',
				'button',
				[ $button_text_html, $icon_html ],
				$combined_button_attr
			);
		}
	}
}

/*add_action( 'zionbuilder/element/before_render', function( $element_instance, $extra_render_data ) {
	if ( class_exists( 'ZionBuilderPro\Plugin' ) && Repeater::is_repeater_provider( $element_instance ) ) {
		if ( ! empty( $element_instance->content ) && is_array( $element_instance->content ) ) {
			foreach ( $element_instance->content as $child_content_data ) {
				if( $child_content_data['element_type'] == 'zu_infinite_scroll' ) {
					$element_instance->render_attributes->add( 
						'wrapper', 
						'class', 
						'zu-infinite-scroll-container ' . $child_content_data['uid']
					);
					break;
				}
			}
		}
	}
}, 10, 2 );*/