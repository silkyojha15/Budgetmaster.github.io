<?php
function compare_progressbar_func( $atts, $content ){
	extract( shortcode_atts( array(
		'label' => '',
		'value' => '',
		'color' => '',
		'bgcolor' => '',
		'label_color' => '',
		'height' => '',
		'font_size' => '',
		'icon' => '',
		'border_radius' => '',
		'style' => ''
	), $atts ) );

	$rnd = compare_random_string();

	$style_css = '
	<style>
		.'.$rnd.'{
			'.( !empty( $label_color ) ? 'color: '.$label_color.';' : '' ).'
			'.( !empty( $border_radius ) ? 'border-radius: '.$border_radius.';' : '' ).'
			'.( !empty( $height ) ? 'height: '.$height.';' : '' ).'
			'.( !empty( $bgcolor ) ? 'background-color: '.$bgcolor.';' : '' ).'
		}

		.'.$rnd.' .progress-bar{
			'.( !empty( $font_size ) ? 'font-size: '.$font_size.';' : '' ).'
			'.( !empty( $height ) ? 'line-height: '.$height.';' : '' ).'
			'.( !empty( $color ) ? 'background-color: '.$color.';' : '' ).'
		}

		.'.$rnd.' .progress-bar-value{
			'.( !empty( $color ) ? 'background-color: '.$color.';' : '' ).'
			'.( !empty( $label_color ) ? 'color: '.$label_color.';' : '' ).'
		}

		.'.$rnd.' .progress-bar-value:after{
			'.( !empty( $color ) ? 'border-color: '.$color.' transparent;' : '' ).'
		}
	</style>
	';

	return compare_shortcode_style( $style_css ).'
	<div class="progress '.$rnd.'">
	  <div class="progress-bar '.$style.'" style="width: '.esc_attr( $value ).'%" role="progressbar" aria-valuenow="'.$value.'" aria-valuemin="0" aria-valuemax="100">
	  		<div class="progress-bar-value">'.$value.'%</div>
	  		'.( !empty( $icon ) ? '<i class="fa fa-'.$icon.'"></i>' : '' ).''.$label.'
	  </div>
	</div>';
}

add_shortcode( 'progressbar', 'compare_progressbar_func' );

function compare_progressbar_params(){
	return array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Label","compare"),
			"param_name" => "label",
			"value" => '',
			"description" => __("Input progress bar label.","compare")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Label Font Size","compare"),
			"param_name" => "font_size",
			"value" => '',
			"description" => __("Input label font size.","compare")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Value","compare"),
			"param_name" => "value",
			"value" => '',
			"description" => __("Input progress bar value. Input number only unit is in percentage.","compare")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => __("Progress Bar Color","compare"),
			"param_name" => "color",
			"value" => '',
			"description" => __("Select progress bar color.","compare")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => __("Progress Bar Background Color","compare"),
			"param_name" => "bgcolor",
			"value" => '',
			"description" => __("Select progress bar background color.","compare")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => __("Progress Bar Label Color","compare"),
			"param_name" => "label_color",
			"value" => '',
			"description" => __("Select progress bar label color.","compare")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Progress Bar Height","compare"),
			"param_name" => "height",
			"value" => '',
			"description" => __("Input progress bar height.","compare")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Progress Bar Label Icon","compare"),
			"param_name" => "icon",
			"value" => compare_awesome_icons_list(),
			"description" => __("Select icon for the label.","compare")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Progress Bar Border Radius","compare"),
			"param_name" => "border_radius",
			"value" => '',
			"description" => __("Input progress bar border radius.","compare")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Progress Bar Style","compare"),
			"param_name" => "style",
			"value" => array(
				__( 'Normal', 'compare' ) => '',
				__( 'Stripes', 'compare' ) => 'progress-bar-striped',
				__( 'Active Stripes', 'compare' ) => 'progress-bar-striped active',
			),
			"description" => __("Select progress bar style.","compare")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => __("Progress Bar", 'compare'),
	   "base" => "progressbar",
	   "category" => __('Content', 'compare'),
	   "params" => compare_progressbar_params()
	) );
}

?>