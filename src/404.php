<?php
$path_to_include = __DIR__ . '/server/php/helpers/classes/setVariables.php';
if (!file_exists($path_to_include)) {
  die("Файл не найден: $path_to_include");
}
include $path_to_include;

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();
$path = $variables->getPathFileURL();

$head_path = $docROOT . $path . '/server/php/layout/head.php';
$sections_path = $docROOT . $path . '/server/php/helpers/include-sections.php';
include $head_path;
include $sections_path;
$base_path = $docROOT . $path . '/server/php/layout';
$path_components = $docROOT . $path . '/server/php/helpers/components';

$title = 'Не найдено | Auto Security';
$head = new Head($title, [], []);
?>

<!DOCTYPE html>
<html lang="ru">
<?php
echo $head->setHead();
?>

<body>
    <?php include $base_path . '/header.php'; ?>
    <main class="main">
        <div class="container" style="text-align: center; padding: 100px 0;">
            <h1 style="font-size: 24px;">По данному запросу ничего не найдено</h1>
            <a href="<?= $path . '/index.php'; ?>" class="link"
                style="color: orange; font-weight: 700; font-size: 36px">Вернуться на
                главную</a>
        </div>
    </main>

    <?php include $base_path . '/footer.php'; ?>
    <?php include_once $path_components . '/phone-button.php'; ?>
    <?php include_once $file_2_section . '/popups/modal-form.php'; ?>
</body>

</html>