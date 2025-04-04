<?php

namespace SECTIONS;

require_once __DIR__ . '/../../../server/vendor/autoload.php';

use DATA\BanksData;

function bankSection()
{
    $images = (new BanksData())->getData();
    $imagesHtml = '';

    if (!empty($images)) {
        foreach ($images as $image) {
            $imagesHtml .= "<div class='bank__image'>
                <img src='{$image}' alt='логотип Каспи Банка' width='100' height='100'>
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
