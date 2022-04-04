<?php
namespace ZiUltimate\Elements\ReadingProgressBar;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ReadingProgressBar
 *
 * @package ZiUltimate\Elements
 */
class ReadingProgressBar extends UltimateElements {
	public function get_type() {
		return 'zu_readingpgbar';
	}

	public function get_name() {
		return __( 'Reading Progress Bar', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'reading', 'progress', 'bar', 'progress bar' ];
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
			$description = 'With this tool you can encourage your visitors to scroll through the entire page and continue reading your content.';
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
            'rpgb_pos',
            [
                'type'      => 'select',
                'default'   => 'top',
                'title'     => esc_html__('Position'),
                'options'   => [
                    [
                        'name'  => __('Top'),
                        'id'    => 'top'
                    ],
                    [
                        'name'  => __('Bottom'),
                        'id'    => 'bottom'
                    ]
                ]
            ]
        );

        $options->add_option(
			'rpgb_height',
			[
				'type'      => 'slider',
				'content'   => 'px',
				'min'       => 0,
				'max'       => 20,
				'default'   => 5,
				'step'      => 1,
				'title'     => __('Height'),
				'css_style' => [
					[
						'selector'  => '{{ELEMENT}} .zu-reading-progress-bar',
						'value'     => 'height: {{VALUE}}px'
					],
					[
						'selector'  => '{{ELEMENT}} .zu-reading-progress-bar-fill',
						'value'     => 'height: {{VALUE}}px'
					]
				]
			]
		);

		$options->add_option(
			'rpgb_bg_clr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Background Color' ),
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}} .zu-reading-progress-bar",
						'value' 	=> 'background-color: {{VALUE}}'
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
			'rpgb_fill_bg_clr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __( 'Fill Color' ),
				'default' 	=> '#fc5611',
				'css_style' => [
					[
						'selector' 	=> "{{ELEMENT}} .zu-reading-progress-bar-fill",
						'value' 	=> 'background-color: {{VALUE}}'
					]
				]
			]
		);

		$options->add_option(
			'rpgb_animspeed',
			[
				'type'      => 'slider',
				'content'   => 'ms',
				'min'       => 0,
				'max'       => 1000,
				'default'   => 50,
				'step'      => 5,
				'title'     => __('Animation Speed', 'ziultimate'),
				'css_style' => [
					[
						'selector'  => '{{ELEMENT}} .zu-reading-progress-bar-fill',
						'value'     => 'transition-duration: {{VALUE}}ms'
					]
				]
			]
		);

		$options->add_option(
			'rpgb_zindex',
			[
				'type'      => 'text',
				'default'   => 999999,
				'title'     => __('Z-Index'),
				'css_style' => [
					[
						'selector'  => '{{ELEMENT}} .zu-reading-progress-bar',
						'value'     => 'z-index: {{VALUE}}'
					]
				]
			]
		);
    }

    /**
	 * Registering the style elements
	 * 
	 * @return void
	 */
	public function get_style_elements_for_editor() {
		// Register element style options
		$this->on_register_styles();

		return $this->registered_style_options;
	}

    /**
	 * Loading the styles
	 * 
	 * @return void
	 */
	public function enqueue_styles() {
		$this->enqueue_element_style( 
            Utils::get_file_url( 'dist/css/elements/ReadingProgressBar/frontend.css' ) 
        );
	}

	public function enqueue_scripts() {
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/ReadingProgressBar/editor.js' ) );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/ReadingProgressBar/frontend.js' ) );
	}

    public function render( $options ) {
		$pos = $options->get_value('rpgb_pos');
?>
		<span class="zu-reading-progress-bar zu-reading-progress-bar-<?php echo $pos; ?>">
			<span class="zu-reading-progress-bar-fill"></span>
			<span class="screen-reader-text"><?php _e('Reading Progress Bar', 'ziultimate'); ?></span>
		</span>
<?php
    }
}