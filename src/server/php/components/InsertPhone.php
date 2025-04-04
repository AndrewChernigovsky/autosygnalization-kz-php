<?php

namespace COMPONENTS;

use COMPONENTS\InsertSVG;

class InsertPhone extends InsertSVG
{
    public function displayPhones($contacts_phone, $social = [])
    {
        $html = '';
        if (!empty($contacts_phone)) {
            foreach ($contacts_phone as $phone) {
                $cleanedPhone = str_replace(' ', '', $phone['phone']);
                $html .= "<a class='link' href='tel:" . htmlspecialchars($cleanedPhone) . "'>";

                if (!empty($social)) {
                    $html .= $this->insertSvg($social);
                }

                $html .= htmlspecialchars($phone['phone']) . '</a>';
            }
        }
        return $html;
    }
}
