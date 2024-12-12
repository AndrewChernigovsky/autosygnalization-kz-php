<?php

class GenerateFooterLinks
{
  private $items;

  public function __construct($items)
  {
    $this->items = $items;
  }
  public function generateFooter()
  {
    function renderNavigation($items)
    {
      $output = '';

      foreach ($items as $navItem) {
        $output .= '<div class="footer__menu-title">';
        $output .= '<h3>';
        if (isset($navItem['link'])) {
          $output .= '<a href="' . htmlspecialchars($navItem['link']) . '">';
        }
        $output .= htmlspecialchars($navItem['title']);
        $output .= '</a>';
        $output .= '</h3>';

        if (isset($navItem['list'])) {
          $output .= '<ul class="footer__menu-list">';
          foreach ($navItem['list'] as $listItem) {
            $output .= '<li>';
            if (isset($listItem['link'])) {
              $output .= '<a href="' . htmlspecialchars($listItem['link']) . '">';
            }
            $output .= htmlspecialchars($listItem['name']);
            $output .= '</a>';

            if (isset($listItem['children'])) {
              $output .= '<ul class="footer__submenu-list">';
              foreach ($listItem['children'] as $childItem) {
                $output .= '<li>';
                if (isset($childItem['link'])) {
                  $output .= '<a href="' . htmlspecialchars($childItem['link']) . '">';
                }
                $output .= htmlspecialchars($childItem['name']);
                $output .= '</a>';
                $output .= '</li>';
              }
              $output .= '</ul>';
            }

            $output .= '</li>';
          }
          $output .= '</ul>';
        }

        $output .= '</div>';
      }

      return $output;
    }


    function renderSubMenu($subTitle)
    {
      $output = '<ul class="footer__submenu-list">';
      foreach ($subTitle['list'] as $subSubItem) {
        $output .= '<li>';
        if (isset($subSubItem['link'])) {
          $output .= '<a href="' . htmlspecialchars($subSubItem['link']) . '">';
        }
        if (isset($subSubItem['name'])) {
          $output .= htmlspecialchars($subSubItem['name']);
        }
        $output .= '</a>';

        if (isset($subSubItem['sub-title'])) {
          $output .= renderSubMenu($subSubItem['sub-title']);
        }

        $output .= '</li>';
      }
      $output .= '</ul>';

      return $output;
    }

    echo renderNavigation($this->items);
  }
}

?>