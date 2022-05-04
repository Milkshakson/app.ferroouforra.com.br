<?php

namespace App\Libraries;

class Html
{
    protected $html = '';
    public function __construct($params = [])
    {
    }
    public function addTag($tagName = 'div', $content = '', $atributes = [])
    {
        $this->html .= $this->getTag($tagName, $content, $atributes);
    }
    public function addRaw($raw = '')
    {
        if (is_array($raw)) {
            foreach ($raw as $rawItem) {
                $this->html .= $rawItem;
            }
        } else {
            $this->html .= $raw;
        }
    }
    public function getTag($tagName = 'div', $content = '', $atributes = []): String
    {
        $mustClose = $this->mustClose($tagName);
        $tag = '<' . $tagName;
        //atributos
        if (is_array($atributes)) {
            foreach ($atributes as $atribute => $value) {
                $tag .= " $atribute='$value'";
            }
        } elseif (is_string($atributes)) {
            $tag .= " $atributes";
        }
        $tag .= ($mustClose ? '' : '/') . '>';
        if (is_array($content)) {
            foreach ($content as $contentItem) {
                $tag .= $contentItem;
            }
        } else {
            $tag .= $content;
        }
        if ($mustClose) {
            $tag .= '</' . $tagName . '>';
        }
        return $tag;
    }
    public function getTagLink($content, $atributes)
    {
        return $this->getTag('a', $content, $atributes);
    }
    private function mustClose($tagName = '')
    {
        $mustClose = ['img'];
        return !in_array($tagName, $mustClose);
    }

    public function display($clear = true)
    {
        $return = $this->html;
        if ($clear == true) {
            $this->html = '';
        }
        echo $return;
    }

    public function render($clear = true)
    {
        $return = $this->html;
        if ($clear == true) {
            $this->html = '';
        }
        return $return;
    }
}
