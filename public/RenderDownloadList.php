<?php

namespace mnc;

class RenderDownloadList {

	/**
	 * @var \WP_Post|null
	 */
	protected $post = null;
	/**
	 * @var string
	 */
	protected $list = null;

	protected $count = 0;

	public function __construct( \WP_Post $post ) {
		$this->post = $post;
	}

	public function render() {
		$this->list = $this->build();

		return implode( "\n", $this->list );
	}

	public function build() {
		$post = $this->post;
		if ( $post->post_type == MNCFilegroups::CPT_MNCFILEGROUP ) {
			$html[] = $post->post_content;
		}
		$this->count = 0;
		if ( have_rows( 'mnc_filegroup', $post->ID ) ) {
			$html[] = '<ul>';
			while ( have_rows( 'mnc_filegroup', $post->ID ) ) {
				$this->count ++;
				the_row();
				$arr = get_sub_field( 'mnc_file' );
				if ( is_array( $arr ) && count( $arr ) > 0 ) {
					$render = $this->renderEl( $arr );
				} else {
					$render = '';
				}
//				$url       = $arr['url'];
//				$displayname  = isset($arr['caption']) && $arr['caption'] != '' ? $arr['caption'] : $arr['filename'];
//				$filesize  = size_format( $arr['filesize'], 2 );
//				$css_class = str_replace( [ '/', '.' ], '-', $arr['mime_type'] );
				$html[] = $render;
			}
			$html[] = '</ul>';

		}
		if ( ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) && $post !== null ) {
			$html[] = HTMLHelper::div( HTMLHelper::atag( get_edit_post_link( $post ), "Downloads bearbeiten" ) );
		}

		return $html;
	}

	public function isEmpty() {
		return $this->count == 0;
	}

	public function renderEl( array $arr ) {
		$attachment_id = $arr['ID'];
		$url           = $arr['url'];
		$displayname   = isset( $arr['caption'] ) && $arr['caption'] != '' ? $arr['caption'] : $arr['filename'];
		// $filesize    = size_format( $arr['filesize'], 2 );
		$css_class = str_replace( [ '/', '.' ], '-', $arr['mime_type'] );
		$title     = $this->getTitleinfo( $arr );
		$image     = wp_get_attachment_image_src( $attachment_id );
		if ( ! $image ) {
			$image_url = '/wp-content/plugins/mncfilegroups/public/assets/pdf-icon.svg';
		} else {
			$image_url = $image[0];
		}
		// 1. try fetching template in theme:
		$file = get_stylesheet_directory() . '/includes/mnc/element.php';
		if ( ! file_exists( $file ) ) {
			// 2. use default rendering in plugin:
			$file = plugin_dir_path( __FILE__ ) . 'templates/element.php';
			if ( ! file_exists( $file ) ) {
				return 'ERROR: Templates element.php not found in ' . $file;
			}
		}
		ob_start();
		require $file;
		$var = ob_get_contents();
		ob_end_clean();

		return $var;
	}

	protected function getTitleinfo( $arr ) {
		$filesize = size_format( $arr['filesize'], 2 );

		return sprintf( "Dokument %s öffnen oder herunterladen, Dateigröße: %s", $arr['filename'], $filesize );
	}

	public static function renderElement( $url, $filename, $filesize, $class = '' ) {
		if ( $class ) {
			$class = ' class="' . $class . '"';
		}

		return '<li' . $class . '><a href="' . $url . '" target="_self">' . $filename . ' (' . $filesize . ')</a></li>';
	}


}