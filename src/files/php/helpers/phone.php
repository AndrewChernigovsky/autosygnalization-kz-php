<?php
include_once 'classes/createSVG.php';
class InsertPhone extends CreateSVG
{
  public function displayPhones($contacts_phone, $social = [])
  {
    if (!empty($contacts_phone)) {
      foreach ($contacts_phone as $phone) {
        $cleanedPhone = str_replace(' ', '', $phone['phone']);
        echo '<a href="tel:' . htmlspecialchars($cleanedPhone) . '">';

        if (!empty($social)) {
          echo $this->insertSvg($social);
        }

        echo htmlspecialchars($phone['phone']) . '</a>';
      }
    }
  }
}
?>