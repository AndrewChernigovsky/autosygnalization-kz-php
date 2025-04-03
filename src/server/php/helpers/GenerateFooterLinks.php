<?php

namespace HELPERS;

class GenerateFooterLinks
{
    private $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function generateFooter()
    {
        function renderList($items)
        {
            $output = '<ul class="footer__menu-list">';

            foreach ($items as $item) {
                $output .= '<li>';
                if (isset($item['link'])) {
                    $output .= '<a href="' . htmlspecialchars($item['link']) . '">';
                }
                if (isset($item['name'])) {
                    $output .= '<span class="toggle-list toggle-inner-list active">' . htmlspecialchars($item['name']) . '</span>';
                }
                if (isset($item['link'])) {
                    $output .= '</a>';
                }

                if (isset($item['children'])) {
                    $output .= renderList($item['children']);
                }

                $output .= '</li>';
            }

            $output .= '</ul>';
            return $output;
        }

        function renderNavigation($items)
        {
            $output = '';

            foreach ($items as $navItem) {
                $output .= '<div class="footer__menu-title">';
                $output .= '<h3 class="toggle-list active">';
                if (isset($navItem['link'])) {
                    $output .= '<a href="' . htmlspecialchars($navItem['link']) . '">';
                }
                $output .= htmlspecialchars($navItem['title']);
                if (isset($navItem['link'])) {
                    $output .= '</a>';
                }
                $output .= '</h3>';

                if (isset($navItem['list'])) {
                    $output .= renderList($navItem['list']);
                }

                $output .= '</div>';
            }

            return $output;
        }

        return renderNavigation($this->items);
    }
}
