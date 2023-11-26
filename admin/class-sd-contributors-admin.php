<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://shwetadanej.com
 * @since      1.0.0
 *
 * @package    Sd_Contributors
 * @subpackage Sd_Contributors/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sd_Contributors
 * @subpackage Sd_Contributors/admin
 * @author     Shweta Danej <shwetadanej@gmail.com>
 */
class Sd_Contributors_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sd_Contributors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sd_Contributors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/sd-contributors-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sd_Contributors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sd_Contributors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/sd-contributors-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * Create contributor meta box
	 *
	 * @return void
	 * @since    1.0.0
	 */
	public function create_meta_box()
	{
		$user = wp_get_current_user();
		if (count(array_intersect(array('author', 'administrator', 'editor'), (array) $user->roles)) > 0) {
			add_meta_box('sd_metabox', __("Contributors", "sd-contributors"), array($this, "display_meta_box"), array('post'), 'side', 'high');
		}
	}

	/**
	 * Display contributor meta box content
	 *
	 * @param object $post
	 * @return void
	 * @since    1.0.0
	 */
	public function display_meta_box($post)
	{
		$contributors = get_users(array('capability' => 'publish_posts'));
		$selected_contributors = get_post_meta($post->ID, 'sd_contributors', true);
		$author_id = $post->post_author;
		require_once plugin_dir_path( __FILE__ ) . '/partials/sd-contributors-admin-display.php';
	}

	/**
	 * Save post and page data when contributors get selected or deselected
	 *
	 * @param int $id
	 * @return void
	 * @since    1.0.0
	 */
	public function save_meta_box($id)
	{
		$post_type = get_post_type($id);
		if (in_array($post_type, ['post', 'page'])) {
			$prev_users = get_post_meta($id, 'sd_contributors', true);
			if (isset($_POST['sd_authors']) && !empty($_POST['sd_authors'])) {
				update_post_meta($id, 'sd_contributors', $_POST['sd_authors']);
			}
			$deleted_users = array_diff($prev_users,  $_POST['sd_authors']);
			foreach ($deleted_users as $v) {
				$post_list = get_user_meta($v, 'sd_contributor_post', true);
				$key = array_search($id, $post_list);
				unset($post_list[$key]);
				update_user_meta($v, 'sd_contributor_post', $post_list);
			}
			foreach ($_POST['sd_authors'] as $key => $value) {
				$post_list = get_user_meta($value, 'sd_contributor_post', true);
				if (!is_array($post_list)) {
					$post_list = array();
				}
				if (!in_array($id, $post_list)) {
					$post_list[] = $id;
				}
				update_user_meta($value, 'sd_contributor_post', $post_list);
			}
		}
	}
}