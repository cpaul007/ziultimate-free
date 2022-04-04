<?php
namespace ZiUltimate\Elements\AnimatedHeading;

use ZiUltimate\UltimateElements;
use ZiUltimate\Admin\License;
use ZiUltimate\Utils;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class AnimatedHeading
 *
 * @package ZiUltimate\Elements
 */
class AnimatedHeading extends UltimateElements {
	
	public function get_type() {
		return 'zu_animated_heading';
	}

	public function get_name() {
		return __( 'Animated Heading', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'animated', 'heading', 'animated heading' ];
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
			$description = 'With this tool you can build the animated heading.';
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
			'builder_preview',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> __('Disable Preview on Builder Editor?', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

        $options->add_option(
            'before_text',
            [
                'type'          => 'text',
                'title'         => esc_html__('Before Text', 'ziultimate'),
                'description'   => __("This text will be placed before the animated text.", "ziultimate"),
				'dynamic' 		=> [
					'enabled' 	=> true
				]
            ]
        );

		$options->add_option(
            'animated_text',
            [
                'type'          => 'textarea',
                'title'         => esc_html__('Animated Text', 'ziultimate'),
                'description'   => __("Use pipeline('|') separator to add the multiple animated text.", "ziultimate"),
				"default" 		=> __("Animated|Rotating", "ziultimate"),
				'dynamic' 		=> [
					'enabled' 	=> true
				]
            ]
		);

		$options->add_option(
            'after_text',
            [
                'type'          => 'text',
                'title'         => esc_html__('After Text', 'ziultimate'),
                'description'   => __("This text will be placed after the animated text.", "ziultimate"),
				'dynamic' 		=> [
					'enabled' 	=> true
				]
            ]
		);

		$options->add_option(
			'tag',
			[
				'type'        => 'select',
				'title'       => esc_html__( 'HTML tag', 'zionbuilder' ),
				'default'     => 'h2',
				'options'     => [
					[
						'id'   => 'h1',
						'name' => 'H1',
					],
					[
						'id'   => 'h2',
						'name' => 'H2',
					],
					[
						'id'   => 'h3',
						'name' => 'H3',
					],
					[
						'id'   => 'h4',
						'name' => 'H4',
					],
					[
						'id'   => 'h5',
						'name' => 'H5',
					],
					[
						'id'   => 'h6',
						'name' => 'H6',
					],
					[
						'id'   => 'div',
						'name' => 'Div',
					],
				],
			]
		);

		/**
		 * Animation
		 */
		$animation = $options->add_group(
			'animation',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Animation'),
				'collapsed' => true
			]
		);

		$animation->add_option(
			'anim_type',
			[
				'type' 		=> 'select',
				'title' 	=> __('Type' , "ziultimate"),
				'options' 	=> [
					[
						'id' 	=> "clip",
						'name' 	=> __("Clip", 'ziultimate'),
					],
					[
						'id' 	=> "loading-bar",
						'name' 	=> __("Loading Bar", 'ziultimate')
					],
					[
						'id' 	=> "push",
						'name'	=> __("Push", 'ziultimate')
					],
					[
						'id' 	=> "rotate-1",
						'name'	=> __("Rotate 1", 'ziultimate')
					],
					[
						'id' 	=> "rotate-2",
						'name'	=> __("Rotate 2", 'ziultimate')
					],
					[
						'id' 	=> "rotate-3",
						'name'	=> __("Rotate 3", 'ziultimate')
					],
					[
						'id' 	=> "scale",
						'name'	=> __("Scale", 'ziultimate')
					],
					[
						'id' 	=> "slide",
						'name'	=> __("Slide", 'ziultimate')
					],	
					[
						'id' 	=> "type",
						'name'	=> __("Typing", 'ziultimate')],
					[
						'id' 	=> "zoom",
						'name'	=> __("Zoom", 'ziultimate')
					]
				],
				'default' 	=> "clip",
			]
		);

		//* Clip Animation
		$animation->add_option(
			'clip_bgclr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __('Vertical Line Color', "ziultimate"),
				'css_style' => [
					[
						'selector' 	=> '.cd-headline.clip .cd-words-wrapper::after',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				],
				'dependency' 	=> [
					[ 
						'option' 	=> 'anim_type',
						'value' 	=> [ 'clip' ]
					]
				]
			]
		);

		$animation->add_option(
			'clip_linewidth',
			[
				'type' 		=> 'slider',
				'content' 	=> 'px',
				'min' 		=> 1,
				'max' 		=> 20,
				'step' 		=> 1,
				'title' 	=> __('Vertical Line Width', "ziultimate"),
				'css_style' => [
					[
						'selector' 	=> '.cd-headline.clip .cd-words-wrapper::after',
						'value' 	=> 'width: {{VALUE}}px'
					]
				],
				'dependency' 	=> [
					[ 
						'option' 	=> 'anim_type',
						'value' 	=> [ 'clip' ]
					]
				]
			]
		);

		//* Type Animation
		$animation->add_option(
			'type_bgclr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __('Vertical Line Color(Blinking Line)', "ziultimate"),
				'css_style' => [
					[
						'selector' 	=> '.cd-headline.type .cd-words-wrapper::after',
						'value' 	=> 'background-color: {{VALUE}}'
					]
				],
				'dependency' 	=> [
					[ 
						'option' 	=> 'anim_type',
						'value' 	=> [ 'type' ]
					]
				]
			]
		);

		$animation->add_option(
			'type_linewidth',
			[
				'type' 		=> 'slider',
				'content' 	=> 'px',
				'min' 		=> 1,
				'max' 		=> 20,
				'step' 		=> 1,
				'title' 	=> __('Vertical Line Width', "ziultimate"),
				'css_style' => [
					[
						'selector' 	=> '.cd-headline.type .cd-words-wrapper::after',
						'value' 	=> 'width: {{VALUE}}px'
					]
				],
				'dependency' 	=> [
					[ 
						'option' 	=> 'anim_type',
						'value' 	=> [ 'type' ]
					]
				]
			]
		);

		//* Loading Bar Animation
		$animation->add_option(
			'ldbar_bgclr',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> __('Loading Bar Color', "ziultimate"),
				'css_style' => [
					[
						'selector' 	=> '.cd-headline.loading-bar .cd-words-wrapper::after',
						'value' 	=> 'background: {{VALUE}}'
					]
				],
				'dependency' 	=> [
					[ 
						'option' 	=> 'anim_type',
						'value' 	=> [ 'loading-bar' ]
					]
				]
			]
		);

		$animation->add_option(
			'ldbar_linewidth',
			[
				'type' 		=> 'slider',
				'content' 	=> 'px',
				'min' 		=> 1,
				'max' 		=> 20,
				'step' 		=> 1,
				'default' 	=> 3,
				'title' 	=> __('Height', "ziultimate"),
				'css_style' => [
					[
						'selector' 	=> '.cd-headline.loading-bar .cd-words-wrapper::after',
						'value' 	=> 'height: {{VALUE}}px'
					]
				],
				'dependency' 	=> [
					[ 
						'option' 	=> 'anim_type',
						'value' 	=> [ 'loading-bar' ]
					]
				]
			]
		);

		/**
		 * Transition Duration
		 */
		$delay = $options->add_group(
			'delay_time',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Transition Duration'),
				'collapsed' => true
			]
		);

		$delay->add_option(
			'delay',
			[
				'type' 		=> 'slider',
				'content'	=> 'ms',
				'default'	=> 2500,
				'min' 		=> 0,
				'max' 		=> 10000,
				'step' 		=> 50,
				'title' 	=> __( 'Delay' )
			]
		);

		$delay->add_option(
			'ldbar_delay',
			[
				'type' 		=> 'slider',
				'content'	=> 'ms',
				'default'	=> 3800,
				'min' 		=> 0,
				'max' 		=> 10000,
				'step' 		=> 50,
				'title' 	=> __( 'Delay for Loading Bar Animation Type', 'ziultimate' )
			]
		);

		$delay->add_option(
			'letters_delay',
			[
				'type' 		=> 'slider',
				'content'	=> 'ms',
				'default'	=> 50,
				'min' 		=> 0,
				'max' 		=> 10000,
				'step' 		=> 50,
				'title' 	=> __( 'Delay for Letters Type', 'ziultimate' ),
				"description" => __("This is for 'Type', 'Rotate 2', 'Rotate 3', 'Scale' animaition type.", "ziultimate")
			]
		);

		$delay->add_option(
			'typing_delay',
			[
				'type' 		=> 'slider',
				'content'	=> 'ms',
				'default'	=> 150,
				'min' 		=> 0,
				'max' 		=> 10000,
				'step' 		=> 50,
				'title' 	=> __( 'Delay for Typing Animation', 'ziultimate' )
			]
		);

		$delay->add_option(
			'typing_duration',
			[
				'type' 		=> 'slider',
				'content'	=> 'ms',
				'default'	=> 500,
				'min' 		=> 0,
				'max' 		=> 10000,
				'step' 		=> 50,
				'title' 	=> __( 'Typing Duration', 'ziultimate' )
			]
		);

		$delay->add_option(
			'clip_delay',
			[
				'type' 		=> 'slider',
				'content'	=> 'ms',
				'default'	=> 1500,
				'min' 		=> 0,
				'max' 		=> 10000,
				'step' 		=> 50,
				'title' 	=> __( 'Delay for Clip Animation', 'ziultimate' )
			]
		);

		$delay->add_option(
			'clip_duration',
			[
				'type' 		=> 'slider',
				'content'	=> 'ms',
				'default'	=> 600,
				'min' 		=> 0,
				'max' 		=> 10000,
				'step' 		=> 50,
				'title' 	=> __( 'Duration for Clip Animation Type', 'ziultimate' )
			]
		);

		/**
		 * Typography for Before & After text
		 */
		$tg = $options->add_group(
			'tg_ba',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Typography of Before and After Text'),
				'collapsed' => true
			]
		);

		$this->attach_typography_options( $tg, 'tg_ba', "{{ELEMENT}} .baft-text", [ 'text-align' ]);

		/**
		 * Typography for Before & After text
		 */
		$atg = $options->add_group(
			'tg_animtext',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Typography of Animated Text'),
				'collapsed' => true
			]
		);

		$this->attach_typography_options( $atg, 'tg_animtxt', "{{ELEMENT}} .cd-words-wrapper", [ 'text-align' ]);
    }

	/**
	 * Loaing the CSS
	 */
	public function enqueue_styles() {
		$this->enqueue_element_style( Utils::get_file_url('dist/css/elements/AnimatedHeading/frontend.css' ) );
	}

	/**
	 * Loading the scripts
	 */
	public function enqueue_scripts() {
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/AnimatedHeading/editor.js' ) );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/AnimatedHeading/frontend.js' ) );
	}

    public function render( $options ) {
		$before_text = $options->get_value('before_text');
		$after_text = $options->get_value('after_text');
		$animated_text = $options->get_value('animated_text');

		if( strpos($animated_text, "|") > 0 ) {
			$animated_text = explode( '|', $animated_text );
			$animated_text = implode("</b><b>", $animated_text);
		}

		$beforeText = !empty($before_text) ? '<span class="baft-text">' . $before_text . '</span> ' : '';
		$afterText = !empty($after_text) ? ' <span class="baft-text">' . $after_text . '</span>' : '';

		$class = "zu-animh-wrap ";
		$anim = $options->get_value('anim_type', 'clip');
		$preview = $options->get_value('builder_preview', false);

		if( in_array($anim, ['type', 'rotate-2', 'rotate-3', 'scale']))
			$class .= "letters ";

		if( $preview )
			$class .= $anim . '-no';
		else
			$class .= 'cd-headline ' . $anim;

		if( $anim == "clip" )
			$class .= ' is-full-width';

		$data = [
			"animationDelay" 		=> str_replace('ms', '', $options->get_value( 'delay' )),
			"barAnimationDelay" 	=> str_replace('ms', '', $options->get_value( 'ldbar_delay' )),
			"lettersDelay" 			=> str_replace('ms', '', $options->get_value( 'letters_delay' )),
			"typeLettersDelay" 		=> str_replace('ms', '', $options->get_value( 'typing_delay' )),
			"selectionDuration" 	=> str_replace('ms', '', $options->get_value( 'typing_duration' )),
			"revealDuration" 		=> str_replace('ms', '', $options->get_value( 'clip_duration' )),
			"revealAnimationDelay" 	=> str_replace('ms', '', $options->get_value( 'clip_delay' ) )
		];

		printf(
			'<%1$s itemprop="headline" class="%5$s" data-animh-config=%6$s>%2$s<span class="cd-words-wrapper waiting"><b class="is-visible">%3$s</b></span>%4$s</%1$s>', 
			$options->get_value("tag", 'h2'), 
			$beforeText,
			$animated_text,
			$afterText,
			$class,
			wp_json_encode( $data )
		);
    }
}