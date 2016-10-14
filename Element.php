<?php

/**
 * Main Class for Layout to include on Framework (Uhupi)
 *
 * This is the main Frontend Tool from Uhupis Framework (www.uhupi.com).
 * It allowes 
 *
 * PHP version 5
 *
 * LICENSE: NOT RELEASED JET
 *
 * @category   Frontend
 * @package    PHP-OOP-HTML-Tag-Element
 * @author     Santino Lange <santino@uhupi.com>
 * @copyright  2015-2016 Uhupi Framework
 * @license    NOT RELEASED JET
 */

class Layout_Element {

    /**
     * Html Element attributes.
     * @var array
     */
    public      $attr       = array();

    /**
     * Content for Element.
     * @var string
     */
    protected   $_content   = NULL;

    /**
     * Html Element Type.
     * @var string
     */
    private     $_tag       = NULL;

    /**
     * Html CSS Class.
     * @var array
     */
    private     $_class     = array();

    /**
     * Defines if Element is an Html Tag or not.
     * @var boolean
     */
    private     $_noTag     = TRUE;

    /**
     * Set Content before Element
     * @var string
     */
    private     $_append    = NULL;

    /**
     * Set Content after Element
     * @var string
     */
    private     $_preppend  = NULL;

    /**
     * Set Style CSS properties for Element
     * @var string
     */
    private     $_css       = array();

    public function __construct($tag = 'div', $content = FALSE) {
            $this->_tag = $tag;
            if ($content) {
                $this->setContent($content);
            }
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

    public function getContent() {
        return $this->_content;
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

            $this->_noTag = FALSE;
            return $this;

    }

    public function render() {

            $display = NULL;

            $close = NULL;
            if(!$this->_noTag) {
                    $close = '/';
            }
            $display.= $this->_preppend;
            $display.= '<' . $this->_tag . ' ' . $this->_getAttr() . $close . '>';
            $display.= $this->_content;
            if($this->_noTag) {
                    $display.= '</' . $this->_tag . '>';
            }
            $display.= $this->_append;
            return $display;

    }

    public function __toString() {
            return $this->render();
    }

}

?>