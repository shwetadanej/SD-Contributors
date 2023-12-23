<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://shwetadanej.com
 * @since      1.0.0
 *
 * @package    Sd_Contributors
 * @subpackage Sd_Contributors/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<ul id="sd_list">
	<?php wp_nonce_field('save_contributor_action_nonce', 'save_contributor_action_nonce_field'); ?>
	<?php
	if ( $contributors ) {
		foreach ( $contributors as $key => $value ) {
			$selected = '';
			$disabled = '';
			if ( $author_id === $value->ID ) {
				$selected = 'checked';
				$disabled = 'disabled';
			} elseif ( is_array($selected_contributors) && in_array( (string)$value->ID, $selected_contributors, true ) ) {
				$selected = 'checked';
			}

			?>
			<li>
				<label>
					<input value="<?php echo esc_attr( (string) $value->ID ); ?>" type="checkbox" name="sd_authors[]" <?php echo esc_attr( (string) $selected . ' ' . $disabled ); ?>>
					<?php echo esc_html( (string) $value->display_name . " ($value->user_login)" ); ?>
				</label>
			</li>
			<?php
		}
	} else {
		?>
		<h4><?php esc_html_e( 'No authors found!', 'sd-contributors' ); ?></h4>
		<h4><?php esc_html_e( 'Please add new authors from USERS->Add New.', 'sd-contributors' ); ?></h4>
		<?php
	}
	?>
</ul>
