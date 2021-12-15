<?php
function compare_toggle_func( $atts, $content ){
	extract( shortcode_atts( array(
		'title' => '',
		'toggle_content' => '',
		'state' => '',
	), $atts ) );

	$rnd = compare_random_string();

	return '
		<div class="panel-group shortcode" id="accordion_'.$rnd.'" role="tablist" aria-multiselectable="true">
		  <div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="heading_'.$rnd.'">
		      <div class="panel-title">
		        <a class="'.( $state == 'in' ? '' : 'collapsed' ).'" data-toggle="collapse" data-parent="#accordion_'.$rnd.'" href="#coll_'.$rnd.'" aria-expanded="true" aria-controls="coll_'.$rnd.'">
		        	'.$title.'
		        	<i class="fa fa-chevron-circle-down animation"></i>
		        </a>
		      </div>
		    </div>
		    <div id="coll_'.$rnd.'" class="panel-collapse collapse '.$state.'" role="tabpanel" aria-labelledby="heading_'.$rnd.'">
		      <div class="panel-body">
		        '.apply_filters( 'the_content', $toggle_content ).'
		      </div>
		    </div>
		  </div>
		</div>';
}

add_shortcode( 'toggle', 'compare_toggle_func' );

function compare_toggle_params(){
	return array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title","compare"),
			"param_name" => "title",
			"value" => '',
			"description" => __("Input toggle title.","compare")
		),
		array(
			"type" => "textarea_raw_html",
			"holder" => "div",
			"class" => "",
			"heading" => __("Content","compare"),
			"param_name" => "toggle_content",
			"value" => '',
			"description" => __("Input toggle title.","compare")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Default State","compare"),
			"param_name" => "state",
			"value" => array(
				__( 'Closed', 'compare' ) => '',
				__( 'Opened', 'compare' ) => 'in',
			),
			"description" => __("Select default toggle state.","compare")
		),

	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => __("Toggle", 'compare'),
	   "base" => "toggle",
	   "category" => __('Content', 'compare'),
	   "params" => compare_toggle_params()
	) );
}

?>