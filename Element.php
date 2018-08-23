<?php

/**
 * Main Class for Layout to include on Framework (Uhupi)
 *
 * This is the main Frontend Tool from Uhupis Framework (www.uhupi.com).
 *
 * PHP version 5+
 *
 * @category   Frontend & Backend
 * @package    PHP-OOP-HTML-Tag-Element
 * @author     Santino Lange <santino@uhupi.com>
 * @copyright  2015-2016 Uhupi Framework (Not-released yet)
 * @license    MIT
 */

class Layout_Element {

    /**
     * Html Element attributes.
     * @var array
     */
    public      $attr       = [];

    /**
     * Content for Element.
     * @var string
     */
    protected   $_content   = null;

    /**
     * Html Element Type.
     * @var string
     */
    protected   $_tag       = null;

    /**
     * Html CSS Class.
     * @var array
     */
    protected   $_class   = [];

    /**
     * Defines if Element is an Html Tag or not.
     * @var boolean
     */
    protected   $isSingleton= false;

    /**
     * Set Content before Element
     * @var string
     */
    protected   $append    = null;

    /**
     * Set Content after Element
     * @var string
     */
    protected   $preppend  = null;

    /**
     * Set Style CSS properties for Element
     * @var string
     */
    private     $_css       = [];

    /**
     * __construct
     *
     * MAGIC FUNCTION
     * Collects or sets basic information about the Element
     *
     * @param string $tag       The html element you want to use
     * @param mixed  $content   Preset or previous content in your element
     * @param string $class     Class or classes you want to preset in your element
     */
    public function __construct($tag = 'div', $content = false, $class = null) {
        $this->_tag = $tag;
        if ($content) {
            $this->setContent($content);
        }
        if ($class) {
            $this->setClass($class);
        }
    }

    /**
     * setTag
     *
     * Set the html element you want to use
     *
     * @param string $tag    The html element you want to use
     * @return $this;
     */
    public function setTag($tag) {
        $this->_tag = $tag;
        return $this;
    }

    /**
     * append
     *
     * Glue another Element or content after this Element
     *
     * @param mixed $content Element or String you want to append
     * @return $this;
     */
    public function append($content) {
        $this->append = $content;
        return $this;
    }

    /**
     * preppend
     *
     * Glue another Element or content before this Element
     *
     * @param mixed $content Element or String you want to preppend
     * @return $this;
     */
    public function preppend($content) {
        $this->preppend = $content;
        return $this;
    }

    /**
     * setAttr
     *
     * ATTRIBUTE MANAGEMENT
     * Set a new attribute fot this Element
     *
     * @param string $name   Name of attribute
     * @param string $value  Value of attribute
     * @return $this;
     */
    public function setAttr($name, $value) {
        $this->attr[$name] = $value;
        return $this;
    }

    /**
     * setTitle
     *
     * ATTRIBUTE MANAGEMENT
     * Fill the attribute "title"
     *
     * @param string    $title   The title for Element
     * @return $this;
     */
    public function setTitle($title) {
        $this->setAttr('title', $title);
        return $this;
    }

    /**
     * setContent
     *
     * CONTENT MANAGEMENT
     * Set or appends more content into the Element
     *
     * @param mixed  $content    Element object or String to render in Element
     * @param boolean $reset      Dismiss all appended content so far
     * @return $this;
     */
    public function setContent($content, $reset = false) {
        if (is_object($content) && method_exists($content, 'render')) {
            $content = $content->render();
        }
        if ($reset) {
            $this->_content = $content;
        } else {
            $this->_content.= $content;
        }
        return $this;
    }

    /**
     * setInclude
     *
     * CONTENT MANAGEMENT
     * Allowes the possibility of running a php include and append it as content
     *
     * @param string    $file   File to be include
     * @param string    $path   Path where the file is to be found
     * @return $this;
     */
    public function setInclude($file, $path = __DIR__) {
        ob_start();
        include $path . '/' . $file;
        $this->setContent(ob_get_clean());
        return $this;
    }

    /**
     * getContent
     *
     * CONTENT MANAGEMENT
     * Get the collected content so far
     *
     * @return string   $this->_content return the content
     */
    public function getContent() {
        return $this->_content;
    }

    /**
     * addClass
     *
     * CLASS MANAGEMENT
     * Add one or more classes to the Element
     *
     * @param string    $classes    Class name or different classes separate by spaces
     * @return $this;
     */
    public function addClass($classes) {
        foreach (explode(' ', $classes) as $class) {
            $this->setClass($class);
        }
        return $this;
    }

    /**
     * setClass
     *
     * CLASS MANAGEMENT
     * Set one class to the Element
     *
     * @param string $class Class name
     * @return $this;
     */
    public function setClass($class) {
        $this->_class[$class] = $class;
        return $this;
    }

    /**
     * removeClass
     *
     * CLASS MANAGEMENT
     * Remove one class from the Element
     *
     * @param string $class Class name
     * @return $this;
     */
    public function removeClass($class) {
        unset($this->_class[$class]);
        return $this;
    }

    /**
     * hasClass
     *
     * CLASS MANAGEMENT
     * Check if the Element has a class
     *
     * @param string    $class  Class name
     * @return boolean;
     */
    public function hasClass($class) {
        if (isset($this->_class[$class]) && $this->_class[$class]) {
            return true;
        }
    }

    /**
     * setData
     *
     * DATA ATTRIBUTE MANAGEMENT
     * Check if the Element has a class
     *
     * @param string    $name   The data name will be complile with "data-"
     * @param string    $value  The data value
     * @return $this;
     */
    public function setData($name, $value) {
        $this->attr['data-' . $name] = $value;
        return $this;
    }

    /**
     * setData
     *
     * CSS ATTRIBUTE MANAGEMENT
     * This allowes to use inline CSS styling on your element
     *
     * NOTE:
     * Inlining CSS attributes on HTML elements (e.g., <p style=...>)
     * should be avoided where possible, as this often leads to unnecessary
     * code duplication. Further, inline CSS on HTML elements is blocked by
     * default with Content Security Policy (CSP).
     *
     * @param string    $property   Standard CSS Property
     * @param string    $value      CSS value
     * @return $this;
     */
    public function setCss($property, $value) {
        $this->_css[$property] = $value;
        return $this;
    }

    /**
     * _renderAttr
     *
     * DATA ATTRIBUTE MANAGEMENT
     * Render all attibutes in this Element
     *
     * @return string    Al the attributes glue toguether in a string
     */
    private function _renderAttr() {
        $display = null;

        /* Lets set classes to the attributes */
        if (count($this->_class)) {
            $this->setAttr('class', implode(' ', $this->_class));
        }

        /* Lets set styling to the attributes */
        $css = null;
        if (count($this->_css)) {
            foreach ($this->_css as $key => $value) {
                $css.= $key . ': ' . $value . '; ';
            }
            $this->setAttr('style', $css);
        }

        foreach ($this->attr as $key => $value) {
            if ($value === false) {
                $display.= $key . ' ';
            } else {
                $display.= $key . '="' . $value . '" ';
            }
        }
        return " $display ";
    }

    /**
     * isTag
     * @deprecated since version 1.0.4
     * Define if this Element is only a Tag. (e.g., <br>, <hr>, etc...)
     */
    public function isTag() {
        $this->isSingleton();
        return $this;
    }

    /**
     * isTag
     * @deprecated since version 1.0.4
     * Define if this Element is only a Tag. (e.g., <br>, <hr>, etc...)
     */
    public function isSingleton() {
        $this->isSingleton = true;
        return $this;
    }

    /**
     * render
     *
     * Render object into an string
     *
     * @return string   $display    All the attributes glue together in a string
     */
    public function render() {

        $tag    = $this->_tag;
        $opener = '<';
        $closer = '>';
        $end    = '/';

        $attrs = $this->_renderAttr();

        $display = null;
        $display.= $this->preppend;
        $display.= $opener . $tag . $attrs . (($this->isSingleton) ? $end : null) . $closer;
        $display.= $this->_content;
        $display.= ($this->isSingleton) ? null : $opener . $end . $tag . $closer ;
        $display.= $this->append;

        return ($this->_content || $attrs || $this->isSingleton) ? $display : '';
    }

    /**
     * __toString
     *
     * MAGIC FUNCTION
     * Render object when it is treated like a string
     *
     * @return string   $this->render() All the attributes glue together into a string
     */
    public function __toString() {
        return $this->render();
    }
}
