<?php
namespace ZiUltimate;

use ZionBuilder\Plugin;
use ZionBuilderPro\Repeater;
use ZiUltimate\UltimateElements;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class Helpers {
	private $zu_active_els = [];

	function __construct() {
		$this->zu_active_els = (array) get_option('ziultimate_active_els');

		if( class_exists( 'ZionBuilderPro\Plugin' ) && ! UltimateElements::isBuilderEditor() ) {
			add_action( 'zionbuilder/element/before_render', [ $this, 'zu_do_elements_before_render' ], 10, 2 );
		}

		add_filter( 'zionbuilder/element/custom_css', [ $this, 'zu_generate_own_css' ], 990, 3 ); 
	}

	public function zu_generate_own_css( $css, $options, $element ) {

		if( $element->get_type() == 'zu_free_shipping_notice' ) {
			$pb_hide = $options->get_value( 'pb_hide', false );
			$action = $options->get_value( 'after_action', 'hide' );
			$outer_wrap_sel = $options->get_value( 'outer_wrap_sel', false );
			$cta_sel = $options->get_value( 'cta_sel', false );

			$css .= 'body.' . $element->uid . ':not(.' . $element->uid . '-hide-fstxt) .' . $element->uid .' {
				height: 0!important;
				padding:0!important;
				margin:0!important;
			}
			body.' . $element->uid . ':not(.' . $element->uid . '-hide-fstxt) .' . $element->uid .' .free-shipping-content {
				opacity: 0;
			}';

			if( !empty( $outer_wrap_sel ) ) {
				$wrapper_selector = str_replace( array( '#', '.'), '', $outer_wrap_sel );

				$css .= $outer_wrap_sel .'{
					-webkit-transition: all ' . $options->get_value('anim_td', 0.15) . 's ease-in-out; 
					-moz-transition: all ' . $options->get_value('anim_td', 0.15) . 's ease-in-out; 
					transition: all ' . $options->get_value('anim_td', 0.15) . 's ease-in-out;
					position: relative;
				}
				body.' . $wrapper_selector . ':not(.' . $wrapper_selector . '-hide-fstxt) ' . $outer_wrap_sel .' {
					height: 0!important;
					max-height: 0!important;
					min-height: 0!important;
					padding:0!important;
					margin:0!important;
					border: none;
				}
				body.' . $wrapper_selector . ':not(.' . $wrapper_selector . '-hide-fstxt) ' . $outer_wrap_sel .' > * {
					opacity: 0;
				}';

				
				if( ! empty( $pb_hide ) || $action == 'hide' ) {
					$css .= 'body.' . $wrapper_selector . ' .' . $element->uid .' .fsn-progress-bar-wrap {display: none;}';
				}

				if( ! empty( $cta_sel ) ) {
					$css .= 'body.' . $wrapper_selector . ' ' . $cta_sel . '{display: none;}';
				}	
			} else {
				if( ! empty( $pb_hide ) || $action == 'hide' ) {
					$css .= 'body.' . $element->uid . ' .' . $element->uid .' .fsn-progress-bar-wrap {display: none;}';
				}

				if( ! empty( $cta_sel ) ) {
					$css .= 'body.' . $element->uid . ' ' . $cta_sel . '{display: none;}';
				}
			}
		}

		if( $element->get_type() == 'zu_lightbox' ) {
			$selectors = $options->get_value( 'trigger_selector', false );
			if( ! empty( $selectors ) ) {
				$css .=  $selectors . '{cursor: pointer}';
			}
		}

		return $css;
	}

	public function zu_do_elements_before_render( $element_instance, $extra_render_data ) {
		if ( Repeater::is_repeater_provider( $element_instance ) ) {
			$element_instance->render_attributes->add( 'wrapper', 'class', 'zu-repeater-wrapper' );

			if ( ! empty( $element_instance->content ) && is_array( $element_instance->content ) && in_array( 'infscroll', $this->zu_active_els ) ) {
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

		if( Repeater::is_repeater_consumer( $element_instance ) ) 
		{
			$element_instance->render_attributes->add(
				'wrapper', 
				'class',
				'zu-consumer-wrapper',
			);
		}
	}
}