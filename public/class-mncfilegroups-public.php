<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://mainetcare.de
 * @since      1.0.0
 *
 * @package    Mncfilegroups
 * @subpackage Mncfilegroups/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mncfilegroups
 * @subpackage Mncfilegroups/public
 * @author     MaiNetCare GmbH <info@mainetcare.com>
 */
class Mncfilegroups_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Mncfilegroups_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mncfilegroups_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mncfilegroups-public.css', array(), $this->version, 'all' );

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
		 * defined in Mncfilegroups_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mncfilegroups_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mncfilegroups-public.js', array( 'jquery' ), $this->version, false );

	}

	public function register_shortcodes() {
		add_shortcode( 'mnc_list_filegroup', function ( $params ) {

			// init:
			$a     = shortcode_atts( array(
				'title' => 'Dokumente:',
				'cat'   => false,
			), $params );
			$title = $a['title'];
			$cat   = $a['cat'];
			$list  = '';

			// go:
			$render_template = function ( $title, $content ) {
				return
					sprintf( '<div class="mi-downloads"><h3>%s<span class="uabb-icon"><i class="fi-download"></i></span></h3><div>%s</div></div>',
						$title,
						$content
					);
			};
			$mi_li           = function ( $url, $filename, $filesize, $class = '' ) {
				if ( $class ) {
					$class = ' class="' . $class . '"';
				}

				return '<li' . $class . '><a href="' . $url . '" target="_self">' . $filename . ' (' . $filesize . ')</a></li>';
			};

			$args = [
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
			];
			if ( $cat ) {
				$cat               = explode( ",", $cat );
				$args['tax_query'] = [
					[
						'taxonomy' => 'media_category',
						'field'    => 'slug',
						'terms'    => $cat // term slug
					]
				];
			}

			//
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {

				$html   = [];
				$html[] = '<ul>';

				while ( $query->have_posts() ) {
					$query->the_post();
					$fi = $query->post;
					$fii = wp_basename(get_attached_file( $query->post->ID ));
					$css_class = str_replace(['/', '.'], '-', $query->post->post_mime_type);
					$url      = wp_get_attachment_url( $query->post->ID );
					$filename = get_the_title();
					$filesize = filesize( get_attached_file( $query->post->ID ) );
					$filesize = size_format( $filesize, 2 );
					$render   =  $mi_li($url,$filename,$filesize, $css_class);
					$html[]   = $render;
				}
				wp_reset_postdata();
				$html[] = '</ul>';
				$list   = implode( "\n", $html );
			}

			return $render_template( $title, $list );
		} );
	}

}
