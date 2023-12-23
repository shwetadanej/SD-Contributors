<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://shwetadanej.com
 * @since      1.0.0
 *
 * @package    Sd_Contributors
 * @subpackage Sd_Contributors/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sd_Contributors
 * @subpackage Sd_Contributors/public
 * @author     Shweta Danej <shwetadanej@gmail.com>
 */
class Sd_Contributors_Public {


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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sd-contributors-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sd-contributors-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Display contributors name on the post/page detail page
	 *
	 * @param string $content
	 * @return string
	 * @since    1.0.0
	 */
	public function display_contributors( $content ) {
		global $post;
		$id           = $post->ID;
		$contributors = get_post_meta( $id, 'sd_contributors', true );
		if ( $contributors ) {
			$content .= sprintf( "<div id='sd_contributors'><p>%s</p><ul>", __( 'Contributors', 'sd-contributors' ) );
			foreach ( $contributors as $value ) {
				$display_name = get_userdata( $value )->display_name;
				$avatar       = get_avatar_url( $value, array( 'size' => '35' ) );
				$url          = get_author_posts_url( $value );
				$content     .= sprintf( "<li><img src='%s'><a href='%s'>%s</a></li>", $avatar, $url, $display_name );
			}
			$content .= sprintf( '</ul></div>' );
		}
		return $content;
	}

	/**
	 * Show list of post on the author archive page for which contributors are assigned
	 *
	 * @param string $where
	 * @return string
	 * @since    1.0.0
	 */
	public function modify_author_archive_query( $where ) {
		if ( ! is_admin() && is_main_query() && is_author() ) {
			$author    = get_user_by( 'slug', get_query_var( 'author_name' ) );
			$author_id = $author->ID;
			$post_ids  = get_user_meta( $author_id, 'sd_contributor_post', true );
			if ( $post_ids ) {
				$post_ids = implode( ',', $post_ids );
				$where   .= "OR ID IN ($post_ids)";
			}
		}
		return $where;
	}
}
