<?php
namespace mnc;

class RenderDownloads {

	protected $title = 'Downloads';
	protected $content = '';

	function __construct( $title, $content ) {
		$this->title   = $title;
		$this->content = $content;
	}

	protected function renderDefaultBox() {
		return sprintf( '<div class="mi-downloads"><h3>%s<span class="uabb-icon"><i class="fi-download"></i></span></h3><div>%s</div></div>',
			$this->title,
			$this->content
		);
	}

	function render_list() {

	}

	function render() {
		// try fetching template in theme:
		$file = get_stylesheet_directory() . '/includes/mnc/downloadbox.php';
		if ( ! file_exists( $file ) ) {
			return $this->renderDefaultBox();
		}
		ob_start();
		$title = $this->title;
		$content = $this->content;
		require $file;
		$var = ob_get_contents();
		ob_end_clean();
		return $var;
	}


}