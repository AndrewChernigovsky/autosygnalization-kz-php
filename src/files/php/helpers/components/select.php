<?php
    include_once __DIR__ . '/../../helpers/classes/setVariables.php';
    include_once __DIR__ . '/../../data/select.php';
    $variables = new SetVariables();
    $variables->setVar();
    $path = $variables->getPathFileURL();
    $select = new SelectData()
?>
<?php

class Select
{

  public function createComponent($data)
  {
    ob_start();
    ?>
    

        <div class="custom-select">
            <div class="select-selected" data-value="name">Название</div>
              <div class="select-items select-hide">
                <?php foreach($data as $option):?>
                <div class="<?= $option['class']?>" data-value="<?= $option['value']?>"><?= $option["name"]?></div>
                <?php endforeach;?>
              </div>
        </div>




    <?php
    return ob_get_clean();

  }
}
?>



