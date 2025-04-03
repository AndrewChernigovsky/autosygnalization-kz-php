<?php

namespace SECTIONS;

function bankSection($data = [])
{
    $imagesHtml = '';

    if (!empty($data)) {
        foreach ($data as $index => $image) {
            $imagesHtml .= "<div class='bank__image'>
                <img src='/client/images/kaspi-" . htmlspecialchars($index) . ".avif' alt='логотип Каспи Банка' width='100' height='100'>
            </div>";
        }
    }

    return <<<HTML
<section class="bank">
    <div class="container">
        <h2>
            Возможно оформление 
            <span style="color: red">в рассрочку</span> / 
            <span style="color: orangered">кредит</span> через Каспи Банк
        </h2>
        <div class="bank__wrapper">
            {$imagesHtml}
        </div>
        <button type="button" class="y-button-primary button buy-btn">Оставить заявку</button>
    </div>
</section>
HTML;
}
