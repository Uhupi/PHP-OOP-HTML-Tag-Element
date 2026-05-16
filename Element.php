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
    protected   string  $tag            = 'div';    /* Html Element Type */
    protected   array   $attrs          = [];       /* Html Element attributes */
    protected   array   $classes        = [];       /* Html CSS Class. */
    protected   array   $styles         = [];       /* Set Style CSS properties for Element */
    protected   bool    $isSingleton    = false;    /* Defines if Element is an Html Tag or not */
    protected   ?string $prepend        = null;     /* Set Content before Element */
    protected   ?string $content        = null;     /* Content for Element */
    protected   ?string $append         = null;     /* Set Content before Element */

    /**
     * MAGIC FUNCTION
     * Collects or sets basic information about the Element
     *
     * @param string $tag       The html element you want to use
     * @param mixed  $content   Preset or previous content in your element
     * @param string $class     Class or classes you want to preset in your element
     */
    public function __construct(string $tag = 'div', $content = null, ?string $class = null)
    {
        $this->setTag($tag);
        if ($content) {
            $this->setContent($content);
        }
        if ($class) {
            $this->setClass($class);
        }
    }

    /**
     * Set the html element you want to use
     */
    public function setTag(string $tag): self
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Get the current html element tag
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
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
     * Glue another Element or content before this Element
     *
     * @param mixed $content Element or String you want to prepend
     * @return $this;
     */
    public function prepend(?string $content): self
    {
        $this->prepend = $content;
        return $this;
    }

    /**
     * ATTRIBUTE MANAGEMENT
     * Set a new attribute for this Element
     */
    public function setAttr(string $name, string $value): self
    {
        $this->attrs[$name] = $value;
        return $this;
    }

    /**
     * ATTRIBUTE MANAGEMENT
     * Get a new attribute from this Element
     */
    public function getAttr(string $name) : ?string
    {
        if (isset($this->attrs[$name])) {
            return $this->attrs[$name];
        }
        return null;
    }

    /**
     * ATTRIBUTE MANAGEMENT
     * Remove one attr from the Element
     */
    public function removeAttr(string $attr) : self
    {
        unset($this->attrs[$attr]);
        return $this;
    }

    /**
     * ATTRIBUTE MANAGEMENT
     * Fill the attribute "title"
     */
    public function setTitle(string $title): self
    {
        $this->setAttr('title', $title);
        return $this;
    }

    /**
     * CONTENT MANAGEMENT
     * Set or appends more content into the Element
     *
     * @param mixed  $content Element object or String to render in Element
     */
    public function setContent(mixed $content, bool $reset = false): self
    {
        if (is_object($content) && method_exists($content, 'render')) {
            $content = $content->render();
        }
        if ($reset) {
            $this->content = $content;
        } else {
            $this->content.= $content;
        }
        return $this;
    }

    /**
     * CONTENT MANAGEMENT
     * Allowes the possibility of running a php include and append it as content
     */
    public function setInclude(string $file, string $path = __DIR__): self
    {
        $realPath = realpath($path . '/' . $file);
        $realBase = realpath($path);
        if ($realPath === false || $realBase === false || strpos($realPath, $realBase . DIRECTORY_SEPARATOR) !== 0) {
            throw new \InvalidArgumentException('Invalid or disallowed file path.');
        }
        ob_start();
        include $realPath;
        $this->setContent(ob_get_clean());
        return $this;
    }

    /**
     * CONTENT MANAGEMENT
     * Get the collected content so far
     */
    public function getContent(bool $reset = false): ?string
    {
        $content = $this->content;
        if ($reset) {
            $this->content = null;
        }
        return $content;
    }

    /**
     * CLASS MANAGEMENT
     * Add one or more classes separate by spaces to the Element
     */
    public function addClass(string $classes): self
    {
        foreach (explode(' ', $classes) as $class) {
            $this->setClass($class);
        }
        return $this;
    }

    /**
     * CLASS MANAGEMENT
     * Set one class to the Element
     */
    public function setClass(string $class): self
    {
        $this->classes[$class] = $class;
        return $this;
    }

    /**
     * CLASS MANAGEMENT
     * Remove one class from the Element
     */
    public function removeClass(string $class): self
    {
        unset($this->classes[$class]);
        return $this;
    }

    /**
     * CLASS MANAGEMENT
     * Reset all classes from the Element
     */
    public function resetClasses(): self
    {
        $this->classes = [];
        return $this;
    }

    /**
     * CLASS MANAGEMENT
     * Check if the Element has a class
     */
    public function hasClass(string $class): bool
    {
        return !empty($this->classes[$class]);
    }

    /**
     * DATA ATTRIBUTE MANAGEMENT
     * The data name will be complile with "data-"
     */
    public function setData(string $name, string $value): self
    {
        $this->attrs['data-' . $name] = $value;
        return $this;
    }
    
    /**
     * DATA ATTRIBUTE MANAGEMENT
     * Return the value of a data attribute. The data name will be complile with "data-"
     */
    public function getData(string $name): ?string
    {
        return $this->attrs['data-' . $name] ?? null;
    }

    /**
     * ARIA ATTRIBUTE MANAGEMENT
     * The aria name will be complile with "aria-"
     */
    public function setAria(string $name, string $value = ''): self
    {
        $this->attrs['aria-' . $name] = $value;
        return $this;
    }

    /**
     * ARIA ATTRIBUTE MANAGEMENT
     * Return the value of an aria attribute. The aria name will be complile with "aria-"
     */
    public function getAria(string $name): ?string
    {
        return $this->attrs['aria-' . $name] ?? null;
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
    public function setStyle(string $property, string $value): self
    {
        $this->styles[$property] = $value;
        return $this;
    }

    /**
     * DATA ATTRIBUTE MANAGEMENT
     * Render all attibutes in this Element
     * Al the attributes glue toguether in a string
     */
    private function renderAttr(): string
    {
        $display = '';

        if (count($this->classes)) {
            $this->setAttr('class', implode(' ', $this->classes));
        }

        $css = '';
        if (count($this->styles)) {
            foreach ($this->styles as $key => $value) {
                $css .= $key . ': ' . $value . '; ';
            }
            $this->setAttr('style', rtrim($css));
        }

        foreach ($this->attrs as $key => $value) {
            if ($value === false) {
                $display .= $key . ' ';
            } else {
                $display .= $key . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '" ';
            }
        }

        return $display !== '' ? ' ' . trim($display) : '';
    }

    /**
     * Define if this Element is only a Tag. (e.g., <br>, <hr>, etc...)
     */
    public function isSingleton(bool $isSingleton = true): self
    {
        $this->isSingleton = $isSingleton;
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
        $opener = '<';
        $closer = '>';
        $end    = '/';

        $attrs = $this->renderAttr();

        $display = null;
        $display.= $this->prepend;
        $display.= $opener . $this->tag . $attrs . (($this->isSingleton) ? $end : null) . $closer;
        $display.= $this->content;
        $display.= ($this->isSingleton) ? null : $opener . $end . $this->tag . $closer ;
        $display.= $this->append;

        return ($this->content || $attrs || $this->isSingleton) ? $display : '';
    }

    /**
     * MAGIC FUNCTION
     * Render object when it is treated like a string
     * $this->render() All the attributes glue together into a string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
