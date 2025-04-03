<?php
use LAYOUT\Head;
use LAYOUT\Header;
use LAYOUT\Footer;
use HELPERS\IncludeSections;
use COMPONENTS\ModalForm;

use function FUNCTIONS\renderPhoneButton;

include_once __DIR__ . '/../../api/sessions/session.php';

$autoType = isset($_GET['service']) ? $_GET['service'] : null;
$footer = new Footer();
$header = new Header();
function getContent($base_path, $path_2, $type)
{
    $html = '';
    $html .= $header->getHeader();
    $html .= "<main class='main'>";
    $html .= "<div>";
    $html .= setType($type);
    "</div>";
    "</main>";
    renderPhoneButton();
    include $path_2 . '/server/php/helpers/components/phone-button.php';
    include $path_2 . '/server/php/sections/popups/modal-form.php';
}
function setType($type): string
{
    include "./$type.php";
}

function getAutoContent($type, $base_path, $path_2)
{
    switch ($type) {
        case 'setup':
            return getContent($base_path, $path_2, 'setup');
        case 'locks':
            return getContent($base_path, $path_2, 'locks');
        case 'setup-media':
            return getContent($base_path, $path_2, 'setup-media');
        case 'setup-system-parking':
            return getContent($base_path, $path_2, 'setup-system-parking');
        case 'autoelectric':
            return getContent($base_path, $path_2, 'autoelectric');
        case 'rus':
            return getContent($base_path, $path_2, 'rus');
        case 'diagnostic':
            return getContent($base_path, $path_2, 'diagnostic');
        case 'disabled-autosynal':
            return getContent($base_path, $path_2, 'disabled-autosynal');
        case 'setup-videoregistration':
            return getContent($base_path, $path_2, 'setup-videoregistration');
        default:
            return getContent($base_path, $path_2, 'default');
    }
}
$content = getAutoContent($autoType, $base_path, $path_2);
$title = 'Установка и ремонт автосигнализаций | Auto Security';
$head = new Head($title, [], []);
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
</body>

</html>