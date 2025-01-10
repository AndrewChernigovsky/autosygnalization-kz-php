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
          <p class="custom-select__title">Сортировка: </p>
            <div class="select-selected">Выберите опцию</div>
              <div class="select-items select-hide">
                <?php foreach($data as $option):?>
                <div data-value="<?= $option['title']?>"><?= $option["title"]?></div>
                <?php endforeach;?>
              </div>
            </div>
        </div>


    <?php
    return ob_get_clean();

  }
}
?>



