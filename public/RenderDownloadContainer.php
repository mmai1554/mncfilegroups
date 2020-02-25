<?php

namespace mnc;

class RenderDownloadContainer {

	protected $title = 'Downloads';
	protected $content = '';
	protected $dom_id = '';

	/**
	 * RenderDownloadBox constructor.
	 *
	 * @param string $title
	 * @param string $content
	 * @param null|string $id
	 */
	public function __construct( $title, $content, $id = null ) {
		$this->title   = $title;
		$this->content = $content;
		if ( $id !== null ) {
			$this->dom_id = 'MNCCont_'.$id;
		}
	}

	public function render() {
		// 1. try fetching template in theme:
		$file = get_stylesheet_directory() . '/includes/mnc/downloadbox.php';
		if ( ! file_exists( $file ) ) {
			// 2. use default rendering in plugin:
			$file = plugin_dir_path( __FILE__ ) . 'templates/downloadbox.php';
			if ( ! file_exists( $file ) ) {
				return 'ERROR: Templates downloadbox not found in ' . $file;
			}
		}
		ob_start();
		$title   = $this->title;
		$content = $this->content;
		$dom_id  = $this->dom_id;
		require $file;
		$var = ob_get_contents();
		ob_end_clean();

		return $var;
	}

	public static function renderElement( $url, $filename, $filesize, $class = '' ) {
		if ( $class ) {
			$class = ' class="' . $class . '"';
		}

		return '<li' . $class . '><a href="' . $url . '" target="_self">' . $filename . ' (' . $filesize . ')</a></li>';
	}


}