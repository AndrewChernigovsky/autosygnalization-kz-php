<?php

namespace COMPONENTS;

class ModalDelivery
{
  public function render(): string
  {
    $filePath = __DIR__ . '/../../../client/docs/delivary.txt';
    $content = '';

    // Добавим отладочную информацию
    error_log("Looking for file at: " . $filePath);
    
    if (file_exists($filePath) && is_readable($filePath)) {
      $fileContent = file_get_contents($filePath);
      error_log("File content: " . $fileContent);
      
      if (!empty($fileContent)) {
        // Разделяем содержимое на параграфы по двойным переносам строк
        $paragraphs = preg_split('/\n\s*\n/', $fileContent);
        $formattedContent = '';
        
        foreach ($paragraphs as $paragraph) {
          $paragraph = trim($paragraph);
          if (!empty($paragraph)) {
            // Проверяем, является ли параграф заголовком
            if (strpos($paragraph, 'ОБ ОПЛАТЕ') === 0 || 
                strpos($paragraph, 'О ДОСТАВКЕ') === 0 ||
                strpos($paragraph, 'ВОЗВРАТ ТОВАРА') === 0 ||
                strpos($paragraph, 'СОГЛАШЕНИЕ') === 0) {
              $formattedContent .= '<p class="modal__subtitle">' . $paragraph . '</p>';
            } else {
              $formattedContent .= '<p>' . $paragraph . '</p>';
            }
          }
        }
        
        $content = $formattedContent;
      } else {
        $content = $this->getDefaultContent();
      }
    } else {
      error_log("File not found or not readable: " . $filePath);
      $content = $this->getDefaultContent();
    }

    return <<<HTML
   <div class="modal" id="deliveryModal">
      <div class="modal__content">
        <span class="modal__close-button" id="modalClose">&times;</span>
        <h2 class="modal__title">Оплата и доставка</h2>
        {$content}
      </div>
    </div>
HTML;
  }
  
  private function getDefaultContent(): string
  {
    return '
      <p class="modal__subtitle">ОБ ОПЛАТЕ</p>
      <p>Оплата осуществляется наличными при покупке и/или установке оборудования, либо при получении товара после доставки.</p>
      <p>Также, можно оплатить товар/услугу безналичным расчетом, либо оформить рассрочку/кредит - для этого нужно будет обратиться к менеджеру.</p>
      <p class="modal__subtitle">О ДОСТАВКЕ</p>
      <p>Доставка товара по городу осуществляется в течение 1-2 рабочих дня с момента оформления и оплаты заказа.</p>
      <p>Стоимость доставки составляет в черте города от 1.000 тг и выше, в зависимости от удаленности местоположения заказчика, а при заказе на сумму более 40.000 тг - бесплатно!</p>
      <p>Также, Вы можете забрать товар самовывозом из магазина по адресу: Алматы, пр.Абая 145/г, бокс №15.</p>
      <p>Обратите внимание, что полноценную гарантию на материал Вы получаете только при установке выбранного Вами товара нашим квалифицированным специалистом!</p>
      <p class="modal__subtitle">ВОЗВРАТ ТОВАРА</p>
      <p>Возврат товара возможен в течение 14 дней с момента продажи и только при сохранении товарного вида изделия, упаковки и комплектующих.</p>
      <p>При установке оборудования на автомобиль возврат товара НЕВОЗМОЖЕН по причине потери товарного вида изделия и его комплектующих и нарушения целостности упаковки товара.</p>
      <p>Соответствие оборудования на товарный вид при возврате решает продавец магазина, либо установщик оборудования.</p>
      <p class="modal__subtitle">СОГЛАШЕНИЕ</p>
      <p>Покупкой/заказом товара/услуги Вы подтверждаете Ваше согласие на условия оплаты, доставки, возврата и обслуживания по гарантии.</p>
    ';
    return 'fuck';
  }
}