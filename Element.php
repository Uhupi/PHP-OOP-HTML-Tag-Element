<?php

/**
 * Main Class for Layout to include on Framework
 *
 * This is a PHP Utility for Frontend Developers.
 *
 * PHP version 7.4+
 *
 * @category   Frontend & Backend
 * @package    PHP-OOP-HTML-Tag-Element
 * @author     Santino Lange <santino@uhupi.com>
 * @copyright  2015-2023 Uhupi
 * @license    MIT
 */

class Element {

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
    public function __construct($tag = 'div', $content = false, $class = null)
    {
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
     * Set the html element you want to use
     */
    public function setTag(string $tag): self
    {
        $this->_tag = $tag;
        return $this;
    }

    public function getTag(): string
    {
        return $this->_tag;
    }

    /**
     * append
     *
     * Glue another Element or content after this Element
     *
     * @param mixed $content Element or String you want to append
     * @return $this;
     */
    public function append(?string $content): self
    {
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
    public function preppend(?string $content): self
    {
        $this->preppend = $content;
        return $this;
    }

    /**
     * setAttr
     * ATTRIBUTE MANAGEMENT
     * Set a new attribute for this Element
     */
    public function setAttr(string $name, string $value): self
    {
        $this->attr[$name] = $value;
        return $this;
    }

    /**
     * getAttr
     * ATTRIBUTE MANAGEMENT
     * Get a new attribute from this Element
     */
    public function getAttr(string $name) : ?string
    {
        if (isset($this->attr[$name])) {
            return $this->attr[$name];
        }
        return null;
    }

    /**
     * removeAttr
     *
     * ATTRIBUTE MANAGEMENT
     * Remove one attr from the Element
     *
     * @param string $attr Attr name
     * @return $this;
     */
    public function removeAttr(string $attr) : self
    {
        unset($this->attr[$attr]);
        return $this;
    }

    /**
     * setTitle
     * ATTRIBUTE MANAGEMENT
     * Fill the attribute "title"
     */
    public function setTitle(string $title): self
    {
        $this->setAttr('title', $title);
        return $this;
    }

    /**
     * setContent
     * CONTENT MANAGEMENT
     * Set or appends more content into the Element
     *
     * @param mixed  $content Element object or String to render in Element
     */
    public function setContent(?string $content, bool $reset = false): self
    {
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
     * CONTENT MANAGEMENT
     * Allowes the possibility of running a php include and append it as content
     *
     * @param string    $file   File to be include
     * @param string    $path   Path where the file is to be found
     */
    public function setInclude(string $file, string $path = __DIR__): self
    {
        ob_start();
        include $path . '/' . $file;
        $this->setContent(ob_get_clean());
        return $this;
    }

    /**
     * getContent
     * CONTENT MANAGEMENT
     * Get the collected content so far
     */
    public function getContent(bool $reset = false): ?string
    {
        $content = $this->_content;
        if ($reset) {
            $this->_content = null;
        }
        return $content;
    }

    /**
     * addClass
     * CLASS MANAGEMENT
     * Add one or more classes to the Element
     *
     * @param string    $classes    Class name or different classes separate by spaces
     */
    public function addClass(string $classes): self
    {
        if (!is_null($classes)) {
            foreach (explode(' ', $classes) as $class) {
                $this->setClass($class);
            }
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
    public function setClass(string $class): self
    {
        $this->_class[$class] = $class;
        return $this;
    }

    /**
     * CLASS MANAGEMENT
     * Remove one class from the Element
     */
    public function removeClass(string $class): self
    {
        unset($this->_class[$class]);
        return $this;
    }

    /**
     * CLASS MANAGEMENT
     * Reset all classes from the Element
     */
    public function resetClasses(): self
    {
        $this->_class = [];
        return $this;
    }

    /**
     * CLASS MANAGEMENT
     * Check if the Element has a class
     */
    public function hasClass(string $class): bool
    {
        return !empty($this->_class[$class]);
    }

    /**
     * DATA ATTRIBUTE MANAGEMENT
     * The data name will be complile with "data-"
     */
    public function setData(string $name, string $value): self
    {
        $this->attr['data-' . $name] = $value;
        return $this;
    }
    
    /**
     * DATA ATTRIBUTE MANAGEMENT
     * Return the value of a data attribute. The data name will be complile with "data-"
     */
    public function getData(string $name): ?string
    {
        return $this->attr['data-' . $name] ?? null;
    }

    /**
     * ARIA ATTRIBUTE MANAGEMENT
     * The aria name will be complile with "aria-"
     */
    public function setAria(string $name, string $value = ''): self
    {
        $this->attr['aria-' . $name] = $value;
        return $this;
    }

    /**
     * ARIA ATTRIBUTE MANAGEMENT
     * Return the value of an aria attribute. The aria name will be complile with "aria-"
     */
    public function getAria(string $name): ?string
    {
        return $this->attr['aria-' . $name] ?? null;
    }

    /**
     * CSS ATTRIBUTE MANAGEMENT
     * This allowes to use inline CSS styling on your element
     *
     * NOTE:
     * Inlining CSS attributes on HTML elements (e.g., <p style=...>)
     * should be avoided where possible, as this often leads to unnecessary
     * code duplication. Further, inline CSS on HTML elements is blocked by
     * default with Content Security Policy (CSP).
     */
    public function setStyle(string $property, $value): self
    {
        $this->_css[$property] = $value;
        return $this;
    }

    /**
     * DATA ATTRIBUTE MANAGEMENT
     * Render all attibutes in this Element
     * Al the attributes glue toguether in a string
     */
    private function _renderAttr(): string
    {
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
     * Define if this Element is only a Tag. (e.g., <br>, <hr>, etc...)
     */
    public function isSingleton(): self
    {
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
    public function render(): string
    {

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
     * @return string $this->render() All the attributes glue together into a string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
