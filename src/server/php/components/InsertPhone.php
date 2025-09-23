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
                // Убираем HTML теги из номера телефона
                $cleanedPhoneText = strip_tags($phone['phone']);
                // Убираем пробелы для tel: ссылки
                $cleanedPhoneLink = str_replace(' ', '', $cleanedPhoneText);
                
                $html .= "<a class='link' href='tel:" . htmlspecialchars($cleanedPhoneLink) . "'>";

                if (!empty($social)) {
                    $html .= $this->insertSvg($social);
                }

                $html .= htmlspecialchars($cleanedPhoneText) . '</a>';
            }
        }
        return $html;
    }
}
