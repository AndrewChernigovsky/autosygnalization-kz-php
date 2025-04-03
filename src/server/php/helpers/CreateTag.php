<?php

namespace HELPERS;
class CreateTag
{
  private $tag;
  private $content;
  private $classes;

  public function __construct($tag, $content = '')
  {
    $this->tag = $tag;
    $this->content = $content;
    $this->classes = '';
  }
  public function createTag()
  {
    $classAttribute = !empty($this->classes) ? " class='{$this->classes}'" : '';
    echo "<{$this->tag}{$classAttribute}>{$this->content}</{$this->tag}>";
  }

  public function setClasses($classes)
  {
    $this->classes = $classes;
  }

  // Метод для установки контента, который может включать другие теги
  public function setContent($content)
  {
    $this->content = $content;
  }
}
?>