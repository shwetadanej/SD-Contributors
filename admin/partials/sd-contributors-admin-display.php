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
    <?php
    if ($contributors) {
        foreach ($contributors as $key => $value) {
            $selected = "";
            $disabled = "";
            if ($author_id == $value->ID) {
                $selected = "checked";
                $disabled = "disabled";
            } else if (!empty($selected_contributors) && in_array($value->ID, $selected_contributors)) {
                $selected = "checked";
            }
    ?>
            <li>
                <label>
                    <input value="<?php esc_attr_e($value->ID) ?>" type="checkbox" name="sd_authors[]" <?php esc_attr_e($selected . " " . $disabled) ?>>
                    <?php esc_html_e($value->display_name . " ($value->user_login)") ?>
                </label>
            </li>
        <?php
        }
    } else {
        ?>
        <h4><?php esc_html_e("No authors found!", "rtc") ?></h4>
        <h4><?php esc_html_e("Please add new authors from USERS->Add New.", "rtc") ?></h4>
    <?php
    }
    ?>
</ul>