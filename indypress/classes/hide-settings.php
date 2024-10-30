<?php

class indypresshide_settings {
	function indypresshide_settings() {
		add_action( 'admin_menu', array( $this, 'menu' ) );
	}
	function main_page() {
		if ( !current_user_can( 'administrator' ) )
			wp_die( __( 'You do not have sufficient permissions to access this page.' , 'indypress') );
		?>
		<div class="wrap">
			<h2>Indypress Hide</h2>
			<form action="options.php" method="post" name="indypresshide">
			<?php settings_fields( 'indypresshide' ); ?>
			<?php do_settings_sections( 'indypresshide_settings' ); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'indypress'); ?>" />
			</p>
			</form>
			</div>
		<?php
		//TODO: report a summary about the current status of the plugin, with links to documentation
	}
	function register_settings() {
		add_settings_section( 'indypresshide_visualization',
			__('Visualization', 'indypresshide'),
			create_function('',''),
			'indypresshide_settings' );

		//disclaimer
		register_setting( 'indypresshide' /*group*/, 'indypresshide_disclaimer_premoderate' );
		add_settings_field( 'indypresshide_disclaimer_premoderate',
			__('Disclaimer for premoderated posts', 'indypress'),
			array( $this, 'setting_textarea' ),
			'indypresshide_settings', //page
			'indypresshide_visualization' /*section*/,
			array('option' => 'indypresshide_disclaimer_premoderate',
				'default' => '<div id="premoderate-disclaimer" class="disclaimer">Post awaiting premoderation</div>')
			);
		register_setting( 'indypresshide' /*group*/, 'indypresshide_disclaimer_hide' );
		add_settings_field( 'indypresshide_disclaimer_hide',
			__('Disclaimer for hidden posts', 'indypress'),
			array( $this, 'setting_textarea' ),
			'indypresshide_settings', //page
			'indypresshide_visualization' /*section*/,
			array('option' => 'indypresshide_disclaimer_hide',
				'default' => '<div id="hide-disclaimer" class="disclaimer">Post hidden because it doesn\'t complies with the policy</div>')
			 );
		//strip image
		register_setting( 'indypresshide' /*group*/, 'indypresshide_stripimg_hide' );
		add_settings_field( 'indypresshide_stripimg_hide',
			__('Strip image from hidden posts', 'indypress'),
			array( $this, 'setting_boolean' ),
			'indypresshide_settings', //page
			'indypresshide_visualization' /*section*/,
			array('option' => 'indypresshide_stripimg_hide',
				'default' => true)
			);
		register_setting( 'indypresshide' /*group*/, 'indypresshide_stripimg_premoderate' );
		add_settings_field( 'indypresshide_stripimg_premoderate',
			__('Strip image from premoderated posts', 'indypress'),
			array( $this, 'setting_boolean' ),
			'indypresshide_settings', //page
			'indypresshide_visualization' /*section*/,
			array('option' => 'indypresshide_stripimg_premoderate',
				'default' => false)
			);


		//same as above, but add to Indypress->Visualization
		add_settings_section( 'indypress_visualization_hide', __('Hidden posts visualization', 'indypress'), create_function('',''), 'indypress_visualization_settings' );
		register_setting( 'indypress_visualization', 'indypresshide_disclaimer_premoderate' );
		add_settings_field( 'indypresshide_disclaimer_premoderate',
			__('Disclaimer for premoderated posts', 'indypress'),
			array( $this, 'setting_textarea' ),
			'indypress_visualization_settings', //page
			'indypress_visualization_hide' /*section*/,
			array('option' => 'indypresshide_disclaimer_premoderate',
				'default' => '<div id="premoderate-disclaimer" class="disclaimer">Post awaiting premoderation</div>')
			);
		register_setting( 'indypresshide' /*group*/, 'indypresshide_disclaimer_hide' );
		add_settings_field( 'indypresshide_disclaimer_hide',
			__('Disclaimer for hidden posts', 'indypress'),
			array( $this, 'setting_textarea' ),
			'indypress_visualization_settings', //page
			'indypress_visualization_hide' /*section*/,
			array('option' => 'indypresshide_disclaimer_hide',
				'default' => '<div id="hide-disclaimer" class="disclaimer">Post hidden because it doesn\'t complies with the policy</div>')
			 );
	}
	function menu() {
		add_menu_page( 'IndypressHide options', 'Hide', 'administrator', 'indypresshide', array( $this, 'main_page' ), NULL );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

	}

	//callbacks
	function setting_boolean( $arr ) {
		/**
		 * Indents a flat JSON string to make it more human-readable.
		 *
		 * @param string $json The original JSON string to process.
		 *
		 * @return string Indented version of the original JSON string.
		 */

		$option = $arr['option'];
		$default = $arr['default'];

		$value = get_option( $option, $default );
?>
	<input type="checkbox" name="<?php echo $option; ?>" <?php if( $value ) echo ' checked="checked"'; ?>">
<?php
	}

	function setting_textarea( $arr ) {
		/**
		 * Indents a flat JSON string to make it more human-readable.
		 *
		 * @param string $json The original JSON string to process.
		 *
		 * @return string Indented version of the original JSON string.
		 */

		$option = $arr['option'];
		$default = $arr['default'];

		$value = get_option( $option, $default );
?>
	<textarea style="width: 100%" name="<?php echo $option; ?>"><?php echo $value;
?></textarea>
<?php
	}

}
