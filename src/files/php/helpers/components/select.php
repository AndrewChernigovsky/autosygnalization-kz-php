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


        <div class="select">
          <p class="select__title">Сортировка: </p>
            <div class="select__selected">Выберите опцию</div>
              <div class="select__items select__hide">
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



