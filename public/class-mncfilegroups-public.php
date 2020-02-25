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

use mnc\HTMLHelper;
use mnc\RenderDownloadBox;

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
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
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
		$this->register_shortcode_list();
		// $this->register_shortcode_attachments();
	}

//	protected function register_shortcode_attachments() {
//		add_shortcode( 'mnc_doc_attachments', function () {
//			global $post;
//
//			return $this->renderDownloadBox( 'Downloads:', $this->renderDownloadgroupByPost( $post ) );
//		} );
//	}

	protected function register_shortcode_list() {
		add_shortcode( 'mnc_doclist', function ( $params ) {

			// init:
			$a      = shortcode_atts( array(
				'id'    => '',
				'empty' => 'Notfound',
			), $params );
			$the_id = $a['id'];
			$empty  = $a['empty'];
			$post   = get_post( $the_id );
			if ( ! ( $post && $post->post_type == 'mnc_filegroups' ) ) {
				return $empty;
			}

			//
			$html   = [];
			$html[] = $post->post_content;
			if ( have_rows( 'mnc_filegroup', $post->ID ) ) {
				$html[] = '<ul>';
				while ( have_rows( 'mnc_filegroup', $post->ID ) ) {
					the_row();
					$arr       = get_sub_field( 'mnc_file' );
					$url       = $arr['url'];
					$displayname  = isset($arr['caption']) && $arr['caption'] != '' ? $arr['caption'] : $arr['filename'];
					$filesize  = size_format( $arr['filesize'], 2 );
					$css_class = str_replace( [ '/', '.' ], '-', $arr['mime_type'] );
					$render    = RenderDownloadBox::renderElement( $url, $displayname, $filesize, $css_class );
					$html[]    = $render;
				}
				$html[] = '</ul>';

			}
			if ( ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) && $post !== null ) {
				$html[] = HTMLHelper::div( HTMLHelper::atag( get_edit_post_link( $post ), "Downloads bearbeiten" ) );
			}
			$list = implode( "\n", $html );
			return (new \mnc\RenderDownloadBox( $post->post_title, $list, $post->ID ))->render();
		} );
	}

	protected function getFilesDataAsArray( WP_Post $post ) {
		$file_list = [];
		if ( have_rows( 'mnc_filegroup', $post->ID ) ) {
			while ( have_rows( 'mnc_filegroup', $post->ID ) ) {
				the_row();
				$arr         = get_sub_field( 'mnc_file' );
				$url         = $arr['url'];
				$filename    = $arr['filename'];
				$filesize    = size_format( $arr['filesize'], 2 );
				$css_class   = str_replace( [ '/', '.' ], '-', $arr['mime_type'] );
				$file_list[] = [
					'url'         => $url,
					'file'        => $filename,
					'displayname' => $filename,
					'size'        => $filesize,
					'class'       => $css_class
				];
			}
		}
		return $file_list;
	}


//	protected function renderDownloadgroupByPost( $post ) {
//		$html = [];
//		if ( ! have_rows( 'mnc_filegroup', $post->ID ) ) {
//			return '';
//		}
//		$html[] = '<ul>';
//		while ( have_rows( 'mnc_filegroup', $post->ID ) ) {
//			the_row();
//
//			$arr       = get_sub_field( 'mnc_file' );
//			$url       = $arr['url'];
//			$filename  = $arr['filename'];
//			$filesize  = size_format( $arr['filesize'], 2 );
//			$css_class = str_replace( [ '/', '.' ], '-', $arr['mime_type'] );
//			$render    = RenderDownloadBox::renderElement( $url, $filename, $filesize, $css_class );
//			$html[]    = $render;
//		}
//		$html[] = '</ul>';
//
//		return implode( "\n", $html );
//	}

	/**
	 * renders the default box
	 *
	 * @param string $title
	 * @param string $content
	 *
	 * @return string
	 */
	protected function renderDownloadBox( $title, $content ) {
		$render = new RenderDownloadBox( $title, $content );

		return $render->render();
	}

//	public function add_downloads_to_page( $content ) {
//		global $post;
//		$box = '';
//		if ( is_page( $post->ID ) ) {
//			$box = $this->renderDownloadgroupByPost( $post );
//			if ( $box ) {
//				$box = $this->renderDownloadBox( 'Dokumente:', $box );
//			}
//		}
//
//		return $content . $box;
//	}


}
