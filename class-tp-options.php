<?php

class TP_Options {

	function __construct() {

		add_action( 'admin_init', array($this, 'tp_options_init') );
		add_action( 'admin_menu', array($this, 'tp_options_page') );

	}

	function tp_options_init(){
		register_setting(
			'tp_options_group',
			'tp_options',
			'tp_options_validate'
		);

	}

	function tp_options_page() {
		add_menu_page(
			'Transliterate Permalinks', 
			'Transliterate Permalinks', 
			'manage_options', 
			'tp_options',
			array($this, 'tp_render_options'),
			'dashicons-translation'
		);
	}

	function tp_render_options() {
?>
		<div class="wrap">
			<style>
#transliterate-all {
	border-radius: 4px;
	border: 1px solid #ccc;
	cursor: pointer;
	width: 140px;
	line-height: 25px;
}
#transliterate-all.success {
	color: #0c3c0c;
	border: 1px solid #0c3c0c;
}
.flex {
	display: flex;
}
.flex > div {
	line-height: 25px;
	margin-right: 10px;
}
</style>
				<form method="post" action="options.php">
<?php
		$post_types = get_post_types(array('public' => true));
		$taxonomies = get_taxonomies(array('show_ui' => true, 'public' => true));
		settings_fields( 'tp_options_group' );
		$options = get_option( 'tp_options' );
?>
						<h1>Permalink transliteration options</h1>
						<table class="form-table">
								<tr valign="top">
										<th scope="row">
											Post types to transliterate
											<a id="select_post_types">Select all</a>
										</th>
										<td>
												<?php
													foreach ($post_types as $tp) : 
												?>
												<label style="margin-right:15px;"><input type="checkbox" name="tp_options[post_types][]" value="<?php echo $tp; ?>" <?php if(isset($options['post_types']) && in_array($tp, $options['post_types'])) echo 'checked'; ?> ><?php echo ucfirst($tp);  ?></label>
												<?php endforeach; ?>
										</td>
								</tr>
								<tr valign="top">
										<th scope="row">
											Taxonomies to transliterate
											<a id="select_taxonomies">Select all</a>
										</th>
										<td>
											<?php
												foreach ($taxonomies as $tax) : 
											?>
												<label style="margin-right:15px"><input type="checkbox" name="tp_options[taxonomies][]" value="<?php echo $tax; ?>" <?php if(isset($options['taxonomies']) && in_array($tax, $options['taxonomies'])) echo 'checked'; ?>> <?php echo ucfirst($tax);  ?></label>
											<?php endforeach; ?>
										</td>
								</tr>
						</table>
						<p class="submit">
								<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'tp') ?>" />
						</p>
				</form>
			
			<div>
				<div class="flex">
						<div>
							Transliterate existing posts and taxonomies
						</div>
						<div>
							<button id="transliterate-all">GO!</button>
						</div>
				</div>
				<div>
							<span>This action cannot be undone</span>
				</div>
				<div>
							<span>Please, save the options before transliteration</span>
				</div>
				<div>
							<span id="result"></span>
				</div>
		</div>
		</div>
<?php   
	}

	function tp_options_validate( $input ) {
		// do some validation here if necessary
		return $input;
	}

}

?>
