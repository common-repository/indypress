<?php
add_action('indypress_input_init_emptyspam', 'indypress_input_emptyspam_init', 10, 2);
function indypress_input_emptyspam_init ($args, $number) {
	new indypress_input_emptyspam( $args, $number );
}
	/* TODO:
		customizable name, title, placeholder, class (to avoid detection)
	 */
class indypress_input_emptyspam {
	/* Handles a very simple antispam (field to be left empty)
		Args:
	 */
	function indypress_input_emptyspam ( $args, $number ) {
		$this->args = $args;
		add_filter( 'indypress_input_form_emptyspam_' . $number, array( &$this, 'form' ), 10, 2 );
		add_filter( 'indypress_form_validation_all', array( &$this, 'validation' ), 10, 2 );
	}
	function form( $previous, $submitted ) {
		//this outputs html
		$previous .= '
	<style> input.es-url { display: none; } </style>
	<input class="es-url" type="url" name="base_url" value="" title="Please do not fill me" placeholder="Dont write anything here" />
		';
		return $previous;
	}
	function validation( $errors, $submitted ) {
		$args = $this->args;
		var_dump( $submitted );
		if( isset( $submitted['base_url'] ) && !empty( $submitted['base_url'] ) )
			$errors[] = 'base_url';
		return $errors;
	}

}


