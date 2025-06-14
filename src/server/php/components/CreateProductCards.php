<?php
namespace COMPONENTS;

class CreateProductCards
{

  private array $current_products_arr;
  private bool $cart;
  private int $total_items_per_page;
  private int $current_page_number;
  private mixed $special_offers_callback = null;


  public function __construct(
    array $current_products_arr = [],
    bool $cart = false,
    int $total_items_per_page = null,
    int $current_page_number = null,
    mixed $special_offers_callback = null
  ) {

    $this->current_products_arr = $current_products_arr;
    $this->cart = $cart;
    $this->total_items_per_page = $total_items_per_page ?? count($current_products_arr);
    $this->current_page_number = $current_page_number ?? filter_var($_GET['PAGE'] ?? 1, FILTER_VALIDATE_INT) ?: 1;
    $this->special_offers_callback = $special_offers_callback;
  }


  public function errorControl(?array $array_loging = null): string
  {

    $array_loging = $array_loging ?? $this->current_products_arr;

    if (empty($array_loging)) {
      return 'Массив продутов пуст';
    }

    return 'Массив продуктов существует';

  }


  public function renderProductCards()
  {
    function formatPriceWithSpaces($price)
    {
      return number_format((int) $price, 0, '', ' ');
    }
    if (!empty($this->current_products_arr)) {

      $current_rendered_product_arr = array_slice($this->current_products_arr, ($this->current_page_number - 1) * $this->total_items_per_page, $this->total_items_per_page);
      $total_items = count($current_rendered_product_arr);
      $iteration_count = 0;


      ob_start();
      foreach ($current_rendered_product_arr as $item) {

        $random_bg = array_rand($item['gallery']);
        $iteration_count++;

        if ($this->special_offers_callback !== null && ($iteration_count === 5 || ($total_items < 10 && $this->current_page_number > 1) || ($iteration_count === $total_items && $total_items < 5))) {
          call_user_func($this->special_offers_callback);
        }
        $price = formatPriceWithSpaces($item['price']);
        ?>
        <article class='product-card' id="<?php echo htmlspecialchars($item['id']); ?>">
          <div class="product-card__bg">
            <img src="<?php echo htmlspecialchars($item['gallery'][$random_bg]); ?>"
              alt="<?php echo htmlspecialchars($item['description']); ?>" width="300" height="250">
          </div>
          <div class="product-card__body">
            <div class="product-card__head">
              <h3><?php echo htmlspecialchars($item['title']); ?></h3>
              <?php if (isset($item['price'])): ?>
                <p>
                  <span>Цена:</span> <?php echo $price; ?>
                  <span><?php echo htmlspecialchars($item['currency']); ?></span>
                </p>
              <?php endif; ?>
            </div>
            <?php if (!$this->cart): ?>
              <div class="product-card__buttons">
                <a class="button y-button-secondary" href="<?php echo htmlspecialchars($item['link']); ?>">Подробнее</a>
                <button type="button" class="button y-button-primary cart-button"
                  data-id="<?php echo htmlspecialchars($item['id']); ?>" data-cost="<?= $item['price'] ?>">Купить</button>
              </div>
            <?php endif; ?>
            <?php if (isset($item['quantity']) && $this->cart): ?>
              <p>Количество: <?php echo htmlspecialchars($item['quantity']); ?></p>
            <?php endif; ?>
          </div>
        </article>
        <?php
      }
      return ob_get_clean();
    }

    return '<h2>Нет доступных товаров</h2>';

  }
}
?>