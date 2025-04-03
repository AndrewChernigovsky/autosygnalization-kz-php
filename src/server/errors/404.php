<?php
$path_to_include = __DIR__ . '/../helpers/classes/setVariables.php';
if (!file_exists($path_to_include)) {
    die("Файл не найден: $path_to_include");
}
include $path_to_include;

$variables = new SetVariables();
$variables->setVar();
$docROOT = $variables->getDocRoot();

$head_path = __DIR__ . '/../../php/layout/head.php';
$sections_path = __DIR__ .  '/../../php/helpers/include-sections.php';
include $head_path;
include $sections_path;
$base_path = __DIR__ . '/../../php/layout';
$path_components = __DIR__ .  '/../../php/helpers/components';

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
</body>

</html>