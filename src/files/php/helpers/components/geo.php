<?php

class GEO extends CreateTag
{
  public function insertGeo()
  {
    $output = '';

    $div = new CreateTag('div', 'Привет, мир!');
    $div->setClasses('example class1 class2');
    $output = $this->createTag();
    return $output;
  }
}

?>