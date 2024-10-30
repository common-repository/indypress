<?php
/*
Plugin Name: IndyPress Base configuration
Plugin URI: http://code.autistici.org/p/indypress
Description: Temporary plugin to configure indypress (development version)
Author: boyska
Version: 0.1
Author URI: 
License: GPL2
Domain Path: ./languages/
*/

add_filter('indypress_input_actions', 'indypressbase_action');
add_filter('indypress_input_fields', 'indypressbase_field');
add_filter('get_indypress_publication_terms', 'indypressbase_terms');
add_filter('indypress_publication_term_title_article', create_function('$t', 'return "Article";'));
	function indypressbase_terms( $terms ) {
		$terms[] = 'article';
		return $terms;
	}
	function indypressbase_field( $fields ) {
		if( 'eventi' == indypress_get_form_slug() )
			$fields[] = array(
				'type' => 'daterange',
				'name_prefix' => 'event'
			);
		$fields[] = array(
			'type' => 'html',
			'content' => '<h2>' . __( 'Titolo' ) . '</h2>'
		);
		$fields[] = array(
			'type' => 'line',
			'name' => 'post_title', 
			'value' =>  ( isset( $args['post_title'] ) ) ? $args['post_title'] : NULL,
			'classes' => 'required',
			'required' => true,
			'title' => 'Titolo del post'
		);
		$fields[] = array(
			'type' => 'tinymce',
			'name' => 'post_content',
		);
		$fields[] = array(
			'type' => 'if',
			'if' => 'logged',
			'else' => array(
				'type' => 'html',
				'content' => '<h2>' . __( 'Author' ) . '</h2>'
			) );
		$fields[] = array(
			'type' => 'if',
			'if' => 'logged',
			'else' => array(
				'type' => 'line',
				'name' => 'post_author_name', 
				'value' =>  ( isset( $args['post_author_name'] ) ) ? $args['post_author_name'] : NULL,
				'classes' => 'required',
				'required' => true,
				'title' => __('Il tuo nick')
			) );
		$fields[] = array(
			'type' => 'html',
			'content' => '<h2>' . __( 'Categories' ) . '</h2>'
		);
		$cat_entries = array();
		foreach(get_categories() as $cat)
			$cat_entries[$cat->term_id] = array();
		$fields[] = array(
			'type' => 'checkboxes',
			'name' => 'post_categories',
			'entries' => $cat_entries,
			'ids_are_terms' => true
		);
		$fields[] = array(
			'type' => 'html',
			'content' => '<h2>' . __( 'Tags' ) . '</h2>'
		);
		$fields[] = array(
			'type' => 'autocomplete',
			'name' => 'tags_input', 
			'value' =>  ( isset( $args['tags_input'] ) ) ? $args['tags_input'] : "",
			'classes' => 'optional',
			'title' => __('Separa i tags con una virgola')
		);
		return $fields;
	}
	function indypressbase_action( $actions ) {
		if( 'eventi' == indypress_get_form_slug() ) {
			$actions[] = array(
				'type' => 'pre',
				'post_field' => 'type',
				'static' => 'indypress_event'
			);
		}
		$actions[] = array(
			'type' => 'meta',
			'meta_field' => 'post_author_name',
			'submit_field' => 'post_author_name'
		);
		$actions[] = array(
			'type' => 'meta',
			'meta_field' => 'event_start',
			'submit_field' => 'event_start'
		);
		$actions[] = array(
			'type' => 'meta',
			'meta_field' => 'event_end',
			'submit_field' => 'event_end'
		);
		$actions[] = array(
			'type' => 'pre',
			'post_field' => 'title',
			'submit_field' => 'post_title'
		);
		$actions[] = array(
			'type' => 'pre',
			'operation' => 'array_push',
			'post_field' => 'terms',
			'static' => 3, //News category
		);
		$actions[] = array(
			'type' => 'pre',
			'operation' => 'array_merge',
			'post_field' => 'terms',
			'submit_field' => 'post_categories',
		);
		$actions[] = array(
			'type' => 'pre',
			'post_field' => 'content',
			'submit_field' => 'post_content'
		);
		return $actions;
	}

