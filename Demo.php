<?php

// print a simple Link
$link = new Layout_Element('a', 'Click here');
$link->setAttr('href', '/');

echo $link;

// Or create your Link template
class Layout_Button extends Layout_Element {
    public function __construct($content = false, $url = false) {
        parent::__construct('a', $content, 'button');
        if ($url) {
            $this->setAttr('href', $url);
        }
        $this->setClass('button');
    }
}

// print adding your defined values like content and the url
$button = new Layout_Button('Click here', '/');
// or maybe you decide in the view or yout template to add come content
if (TRUE) {
    $button->setContent(' NOW!');
}
echo $button;

// or Print it in one line if yout like
echo new Layout_Button('Click here', '/');
