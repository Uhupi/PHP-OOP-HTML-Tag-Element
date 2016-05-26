<?php

// TEST

class Uhupi_Layout_Element extends Uhupi_Layout_Abstract {

	public $attr = array();
	private $_tag;
	protected $_content;
	private $_class = array();
	private $_close = TRUE;
	private $_append = NULL;
	private $_preppend = NULL;
	private $_css = array();

	public function __construct($tag = 'div') {

		parent::__construct();

		$this->_tag = $tag;

	}

	public function setTag($tag) {

		$this->_tag = $tag;

		return $this;

	}

	public function append($content) {

		$this->_append = $content;

	}

	public function preppend($content) {

		$this->_preppend = $content;

	}

	public function setAttr($name, $value) {

		$this->attr[$name] = $value;

		return $this;

	}

	public function setContent($content, $reset = FALSE) {

		if(is_object($content) && method_exists($content, 'render')) {
			$content = $content->render();
		}

		if($reset) {

			$this->_content = $content;

		} else {

			$this->_content.= $content;

		}

		return $this;

	}

	public function setInclude($path, $dir = ROOT) {
		ob_start();
		include $dir . '/' . $path;
		$content = ob_get_clean();
		$this->setContent($content);

		return $this;

	}

	public function setClass($class) {

		$this->_class[$class] = $class;

		return $this;

	}

	public function setData($data, $value) {

		$this->attr['data-' . $data] = $value;

		return $this;

	}

	public function setCss($property, $value) {

		$this->_css[$property] = $value;

		return $this;

	}

	public function setTitle($title) {

		$this->setAttr('title', $title);

		return $this;

	}

	private function _getAttr() {

		$display = NULL;

		if (count($this->_class)) {
			$this->setAttr('class', implode(' ', $this->_class));
		}

		$css = NULL;
		if (count($this->_css)) {
			foreach($this->_css as $key => $value) {
				$css.= $key . ': ' . $value . '; ';
			}
			$this->setAttr('style', $css);
		}

		foreach($this->attr as $key => $value) {

			if(!$value) {
				$display.= $key . ' ';
			} else {
				$display.= $key . '="' . $value . '" ';
			}
		}

		return $display;

	}

	public function notClosing() {

		$this->isTag();
		return $this;

	}

	public function isTag() {

		$this->_close = FALSE;
		return $this;

	}

	public function render() {

		$display = NULL;

		$close = NULL;
		if(!$this->_close) {
			$close = '/';
		}
		$display.= $this->_preppend;
		$display.= '<' . $this->_tag . ' ' . $this->_getAttr() . $close . '>';
		$display.= $this->_content;
		if($this->_close) {
			$display.= '</' . $this->_tag . '>';
		}
		$display.= $this->_append;
		return $display;

	}

}

?>