<?php

// Print a simple Link
$link = new Layout_Element('a', 'Click here');
$link->setAttr('href', '/');

echo $link;

// Or create your Link template
class Layout_Button extends Layout_Element {
    public function __construct($content = FALSE, $url = FALSE) {
        parent::__construct('a', $content);
        if ($url) {
            $this->setAttr('href', $url);
        }
        $this->setClass('button');
    }
}

// Print it and maybe you need to add something else
$button = new Layout_Button('Click here', '/');
if (TRUE) {
    $button->setContent(' NOW!');
}
echo $button;

// Or maybe Print it in one line?
echo new Layout_Button('Click here', '/');

