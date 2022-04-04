<?php
namespace ZiUltimate\Elements\ReadingTime;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ReadingTime
 *
 * @package ZiUltimate\Elements
 */
class ReadingTime extends UltimateElements {
	public function get_type() {
		return 'zu_reading_time';
	}

	public function get_name() {
		return __( 'Reading Time', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'reading', 'time', 'reading time' ];
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
			$description = __('Do you want to display estimated post reading time in your WordPress blog posts? An estimated reading time encourages users to read a blog post instead of clicking away.', 'ziultimate');
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
			'rt_prefix',
			[
				'type'      => 'text',
				'title'     => esc_html__('Prefix Label', 'ziultimate'),
				'description' => esc_html__('This text appears before the reading time.', 'ziultimate'),
				'placeholder' => 'Reading Time:',
				'dynamic'     => [
					'enabled' => true,
				]
			]
		);

		$options->add_option(
			'rt_suffix',
			[
				'type'      => 'text',
				'title'     => esc_html__('Suffix Label', 'ziultimate'),
				'description' => esc_html__('This text appears after the reading time.', 'ziultimate'),
				'placeholder' => 'mins read',
				'dynamic'     => [
					'enabled' => true,
				]
			]
		);

		$options->add_option(
			'rt_suffix_singular',
			[
				'type'      => 'text',
				'title'     => esc_html__('Suffix Singular Label', 'ziultimate'),
				'description' => esc_html__('This text appears after the reading time, when reading time is 1 minute.', 'ziultimate'),
				'placeholder' => 'min read',
				'dynamic'     => [
					'enabled' => true,
				]
			]
		);

		$options->add_option(
			'rt_wpm',
			[
				'type'      => 'text',
				'title'     => esc_html__('Words Per Minute', 'ziultimate'),
				'description' => esc_html__('Default is 300, the average reading speed for adults.', 'ziultimate'),
				'default' 	=> 300,
				'dynamic'     => [
					'enabled' => true,
				]
			]
		);

		$options->add_option(
			'rt_incl_shortcodes',
			[
				'type'      => 'checkbox_switch',
				'title'     => esc_html__('Include shortcodes in the reading time', 'ziultimate'),
				'default' 	=> false
			]
		);

		$options->add_option(
			'rt_excl_images',
			[
				'type'      => 'checkbox_switch',
				'title'     => esc_html__('Exclude images from the reading time', 'ziultimate'),
				'default' 	=> false
			]
		);
    }
	

	/**
	 * Registering the styles
	 */
	public function on_register_styles() {
		$this->register_style_options_element(
			'rt_label_styles',
			[
				'title'    => esc_html__( 'Prefix Label', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .zu-rt-label',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'suffix_label_styles',
			[
				'title'    => esc_html__( 'Suffix Label', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .zu-rt-suffix-text',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'reading_time_styles',
			[
				'title'    => esc_html__( 'Reading Time', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .zu-reading-time',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}

	/**
	 * Rendering the layout
	 */
    public function render( $options ) {
		$suffix_text = '';
		$label 				= $options->get_value('rt_prefix');
		$suffix 			= $options->get_value('rt_suffix');
		$suffix_singular 	= $options->get_value('rt_suffix_singular');
		$wpm 				= $options->get_value('rt_wpm', 300);
		$include_shortcodes = $options->get_value('rt_incl_shortcodes', false);
		$exclude_images 	= $options->get_value('rt_excl_images', false);

		$post_content = get_post_field( 'post_content', get_the_ID() );
		$zion_data = get_post_meta( get_the_ID(), '_zionbuilder_page_elements', true);
		if( $zion_data ) {
			$post_content .= $zion_data;
		}

		$number_of_images = substr_count( strtolower( $post_content ), '<img ' );

		if ( ! $include_shortcodes ) {
			$post_content = strip_shortcodes( $post_content );
		}

		$post_content = wp_strip_all_tags( $post_content );
		$word_count = count( preg_split( '/\s+/', $post_content ) );

		if ( ! $exclude_images ) {
			$words_for_images = $this->ru_calculate_words_form_images( $number_of_images, $wpm );
			$word_count += $words_for_images;
		}

		$reading_time = $word_count / absint( $wpm );

		if( $label ) {
			printf('<span class="zu-rt-label">%s</span>&nbsp;', $label );
		}

		if( $suffix || $suffix_singular ) {
			$suffix_text = sprintf('&nbsp;<span class="zu-rt-suffix-text">%s</span>', ( ( 1 > $reading_time ) ? $suffix_singular : $suffix ) );
		}

		if ( 1 > $reading_time ) {
			$reading_time = __( '< 1', 'ziultimate' );
		} else {
			$reading_time = ceil( $reading_time );
		}

		printf('<span class="zu-reading-time">%s</span>', $reading_time);
		echo $suffix_text;
    }

	/**
	 * Adds additional reading time for images
	 *
	 * Based on calculations by Medium. https://blog.medium.com/read-time-and-you-bc2048ab620c
	 *
	 */
	public function ru_calculate_words_form_images( $total_images, $wpm ) {
		$time_for_images = 0;
		// For the first image add 12 seconds, second image add 11, ..., for image 10+ add 3 seconds.
		for ( $i = 1; $i <= $total_images; $i++ ) {
			if ( $i >= 10 ) {
				$time_for_images += 3 * (int) $wpm / 60;
			} else {
				$time_for_images += ( 12 - ( $i - 1 ) ) * (int) $wpm / 60;
			}
		}

		return $time_for_images;
	}
}