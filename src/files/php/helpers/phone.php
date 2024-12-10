<?php
class InsertPhone extends InsertSVG
{
  public function displayPhones($contacts_phone, $social = [])
  {
    if (!empty($contacts_phone)) {
      foreach ($contacts_phone as $phone) {
        $cleanedPhone = str_replace(' ', '', $phone['phone']);
        echo '<a href="tel:' . htmlspecialchars($cleanedPhone) . '">';

        if (!empty($social)) {
          echo $this->render($social);
        }

        echo htmlspecialchars($phone['phone']) . '</a>';
      }
    }
  }
}
?>