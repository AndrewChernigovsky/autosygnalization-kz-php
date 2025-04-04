<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use LAYOUT\Header;
use LAYOUT\Footer;
use LAYOUT\Head;

use function AUTH\SESSIONS\initSession;

initSession();

$autoType = isset($_GET["auto"]) ? $_GET["auto"] : null;

function getContent($type)
{
    ob_start();
    $header = (new Header())->getHeader();

    echo "<main class='main'>";
    echo "<div>";

    if (file_exists("./$type.php")) {
        include_once "./$type.php";
    } else {
        echo "<p>Файл $type.php не найден.</p>";
    }

    echo "</div>";
    echo "</main>";

    $html = ob_get_clean();
    $footer = (new Footer())->getFooter();

    return $header . $html . $footer;
}

function getAutoContent($type)
{
    switch ($type) {
        case 'auto':
            return getContent('auto');
        case 'gsm':
            return getContent('gsm');
        case 'no-auto':
            return getContent('no-auto');
        case 'catalog':
            return getContent('catalog');
        case 'accessories':
            return getContent('acessories');
        case 'price':
            return getContent('gsm');
        default:
            return getContent('default');
    }
}

$content = getAutoContent($autoType);
$title = 'Автосигнализации | Auto Security';
$head = new Head($title, [], []);
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
echo $content;
?>

<body>

</body>

</html>