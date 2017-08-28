<?php

/**
 * Main Class for Layout to include on Framework (Uhupi)
 *
 * This is the main Frontend Tool from Uhupis Framework (www.uhupi.com).
 * It allowes 
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
    private     $_tag       = null;

    /**
     * Html CSS Class.
     * @var array
     */
    private     $_class     = [];

    /**
     * Defines if Element is an Html Tag or not.
     * @var boolean
     */
    private     $_noTag     = true;

    /**
     * Set Content before Element
     * @var string
     */
    private     $_append    = null;

    /**
     * Set Content after Element
     * @var string
     */
    private     $_preppend  = null;

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
     */
    public function append($content) {
        $this->_append = $content;
        return $this;
    }
    
    /**
     * preppend
     *
     * Glue another Element or content before this Element
     *
     * @param mixed $content Element or String you want to preppend
     */
    public function preppend($content) {
        $this->_preppend = $content;
        return $this;
    }
    
    /**
     * setAttr
     *
     * ATTRIBUTE MANAGMENT
     * Set a new atributte fot this Element
     *
     * @param string $name   Name of attributte
     * @param string $value  Value of attributte
     */
    public function setAttr($name, $value) {
        $this->attr[$name] = $value;
        return $this;
    }

    /**
     * setTitle
     *
     * ATTRIBUTE MANAGMENT
     * Fill the attributte "title"
     *
     * @param string    $title   The title for Element
     */
    public function setTitle($title) {
        $this->setAttr('title', $title);
        return $this;
    }
    
    /**
     * setContent
     * 
     * CONTENT MANAGMENT
     * Set or appennds more content into the Element
     *
     * @param mixed  $content    Element object or String to render in Element
     * @param string $reset      Dismiss all appended content so far
     */
    public function setContent($content, $reset = false) {
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
    
    /**
     * setInclude
     *
     * CONTENT MANAGMENT
     * Allowes the possibility of runing a php include and append it as content
     *
     * @param string    $file   File to be include
     * @param string    $path   Path where the file is to be found
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
     * CONTENT MANAGMENT
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
     * CLASS MANAGMENT
     * Add one or more classes to the Element
     *
     * @param string    $classes    Class name or different classes separate by spaces
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
     * CLASS MANAGMENT
     * Set one class to the Element
     *
     * @param string    $classes    Class name
     */
    public function setClass($class) {
        $this->_class[$class] = $class;
        return $this;
    }

    /**
     * hasClass
     *
     * CLASS MANAGMENT
     * Check if the Element has a class
     *
     * @param string    $class  Class name
     */
    public function hasClass($class) {
        if ($this->_class[$class]) {
            return true;
        }
    }

    /**
     * setData
     *
     * DATA ATTRIBUTE MANAGMENT
     * Check if the Element has a class
     *
     * @param string    $name   The data name will be complile with "data-"
     * @param string    $value  The data value
     */
    public function setData($name, $value) {
        $this->attr['data-' . $name] = $value;
        return $this;
    }

    /**
     * setData
     *
     * CSS ATTRIBUTE MANAGMENT
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
     */
    public function setCss($property, $value) {
        $this->_css[$property] = $value;
        return $this;
    }

    /**
     * _renderAttr
     *
     * DATA ATTRIBUTE MANAGMENT
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

    /**
     * isTag
     *
     * Define if this Element is only a Tag. (e.g., <br>, <hr>, etc...)
     */
    public function isTag() {
        $this->_noTag = false;
        return $this;
    }

    /**
     * render
     *
     * Render object into an string
     *
     * @return string   $display    All the attributes glue toguether in a string
     */
    public function render() {
        $display = null;

        $close = null;
        if(!$this->_noTag) {
            $close = '/';
        }
        $display.= $this->_preppend;
        $display.= '<' . $this->_tag . ' ' . $this->_renderAttr() . $close . '>';
        $display.= $this->_content;
        if($this->_noTag) {
            $display.= '</' . $this->_tag . '>';
        }
        $display.= $this->_append;
        return $display;
    }

    /**
     * __toString
     *
     * MAGIC FUNCTION
     * Render object when it is treated like a string
     *
     * @return string   $this->render() All the attributes glue toguether in a string
     */
    public function __toString() {
        return $this->render();
    }
}

?>