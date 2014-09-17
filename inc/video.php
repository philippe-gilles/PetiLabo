<?php
	define("_TEMPLATE_VID_DAILYMOTION", "<iframe frameborder=\"0\" width=\"480\" height=\"270\" src=\"http://www.dailymotion.com/embed/video/%s\"></iframe>");
	define("_TEMPLATE_VID_YOUTUBE", "<iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/%s\" frameborder=\"0\" allowfullscreen></iframe>");
	define("_TEMPLATE_VID_VIMEO", "<iframe src=\"//player.vimeo.com/video/%s\" width=\"500\" height=\"281\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>");
	define("_TEMPLATE_IMG_DAILYMOTION", "http://www.dailymotion.com/thumbnail/video/%s");
	define("_TEMPLATE_IMG_YOUTUBE", "http://img.youtube.com/vi/%s/0.jpg");
	define("_TEMPLATE_API_VIMEO", "http://vimeo.com/api/v2/video/%s.php");
	define("_TEMPLATE_IMG_VIMEO", "%s");

	class video {
		// Propriétés
		private $source = null;
		private $code = null;
		
		public function __construct($source, $code) {
			$this->source = trim($source);
			$this->code = trim($code);
		}
		public function get_iframe() {
			$html = null;
			if (!(strcmp($this->source, _PAGE_ATTR_SOURCE_YOUTUBE))) {$html = sprintf(_TEMPLATE_VID_YOUTUBE, $this->code);}
			elseif (!(strcmp($this->source, _PAGE_ATTR_SOURCE_DAILYMOTION))) {$html = sprintf(_TEMPLATE_VID_DAILYMOTION, $this->code);}
			elseif (!(strcmp($this->source, _PAGE_ATTR_SOURCE_VIMEO))) {$html = sprintf(_TEMPLATE_VID_VIMEO, $this->code);}
			return $html;
		}
		public function get_src() {
			$src = null;
			if (!(strcmp($this->source, _PAGE_ATTR_SOURCE_YOUTUBE))) {$src = sprintf(_TEMPLATE_IMG_YOUTUBE, $this->code);}
			elseif (!(strcmp($this->source, _PAGE_ATTR_SOURCE_DAILYMOTION))) {$src = sprintf(_TEMPLATE_IMG_DAILYMOTION, $this->code);}
			elseif (!(strcmp($this->source, _PAGE_ATTR_SOURCE_VIMEO))) {
				$src_api_vi = sprintf(_TEMPLATE_API_VIMEO, $this->code);
				$api_vi = unserialize(file_get_contents($src_api_vi));
				$src_vi = $api_vi[0]['thumbnail_large'];  
				$src = sprintf(_TEMPLATE_IMG_VIMEO, $src_vi);
			}
			return $src;
		}
	}