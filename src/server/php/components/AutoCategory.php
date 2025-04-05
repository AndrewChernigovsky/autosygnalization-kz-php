<?php

namespace COMPONENTS;



class AutoCategory
{
    public function render($title)
    {

        $html = <<<HTML
    <section class="autosygnals" id="autosygnals">
      <div class="container">
        <h2 class="autosygnals__title">$title</h2>
      </div>
    </section>
    HTML;

        return $html;

    }
}
