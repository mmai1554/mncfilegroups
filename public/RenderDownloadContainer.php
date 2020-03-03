<?php

namespace mnc;

class RenderDownloadContainer {

	protected $title = 'Downloads';
	protected $content = '';
	protected $dom_id = '';
	protected $template = null;

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
			$this->dom_id = 'MNCCont_' . $id;
		}
	}

	public function setTemplate( $template ) {
		if(substr($template,0,1) !== '/') {
			$template = '/' . $template;
		}
		$this->template = $template;
	}

	/**
	 * try loading the default templates
	 * 1) customer based template, set in shortcode
	 * 2) default template in theme
	 * 3) default template in plugin
	 * @return string
	 */
	public function loadTemplate() {
		$file = get_stylesheet_directory() . $this->template;
		if ( $this->template !== null && file_exists( $file ) ) {
			return $file;
		}
		$file = get_stylesheet_directory() . '/includes/mnc/downloadbox.php';
		if ( file_exists( $file ) ) {
			return $file;
		}
		$file = plugin_dir_path( __FILE__ ) . 'templates/downloadbox.php';
		if ( file_exists( $file ) ) {
			return $file;
		}
	}

	public function render() {
		// 1. try fetching template in theme:
		$file = $this->loadTemplate();
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