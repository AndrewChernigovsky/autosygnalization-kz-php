<?php

namespace FUNCTIONS;

function renderPhoneButton($text = "Заказать звонок", $id = "phone-button")
{
    echo <<<HTML
<button type="button" id="$id" class="phone-button animated-calling" title="$text" aria-haspopup="true">
<div class="phone-button__wrapper">
  <span class="visually-hidden">
    $text
  </span>
</div>
</button>
HTML;
}
