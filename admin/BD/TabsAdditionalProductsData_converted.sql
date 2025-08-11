-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 23 2025 г., 12:00
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `autosygnals`
--

-- --------------------------------------------------------

--
-- Структура таблицы `TabsAdditionalProductsData`
--

CREATE TABLE `TabsAdditionalProductsData` (
  `id` int NOT NULL,
  `product_id` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tabs_data` json NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `TabsAdditionalProductsData` (КОНВЕРТИРОВАННЫЕ ДАННЫЕ)
--

INSERT INTO `TabsAdditionalProductsData` (`id`, `product_id`, `tabs_data`, `created_at`) VALUES
(1, 'product_keychain_a93-eco', '[{\"title\": \"ГАРАНТИЯ\", \"content\": [{\"title\": \"\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим\", \"icon\": \"\"}]}, {\"title\": \"ОПИСАНИЕ\", \"content\": [{\"title\": \"ДИАЛОГОВАЯ ЗАЩИТА\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Диалоговый код управления StarLine c индивидуальными ключами шифрования 128 бит гарантирует надежную защиту от всех известных кодграбберов.\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному 128-канальному трансиверу.\"}, {\"title\": \"SUPER SLAVE (ОПЦИЯ)\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной диалоговой авторизацией брелком StarLine. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}, {\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 50 до плюс 85 °С благодаря высококачественным комплектующим.\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных технологий и программных решений.\"}, {\"title\": \"КОНТРОЛЬ КАНАЛА СВЯЗИ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Автоматический контроль канала связи обеспечивает прoверку нахождения брелка в зоне действия приемопередатчика охранного оборудования.\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ (ОПЦИЯ)\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля. <br> Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}, {\"title\": \"3D ДАТЧИК УДАРА И НАКЛОНА\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интегрированный цифровой датчик удара и наклона с дистанционной настройкой регистрирует поддомкрачивание и эвакуацию транспортного средства.\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА (ОПЦИЯ)\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля. Опция доступна при интеграции 2CAN+2LIN интерфейса.\"}, {\"title\": \"ТЕЛЕМАТИКА (ОПЦИЯ)\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опциональных <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\\" target=\\\"_blank\\\">GSM</a>,<a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг.\"}, {\"title\": \"УПРАВЛЕНИЕ С ТЕЛЕФОНА (ОПЦИЯ)\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями, получать оповещения о статусе охраны на ваш мобильный телефон.\"}, {\"title\": \"БЕСПЛАТНЫЙ МОНИТОРИНГ (ОПЦИЯ)\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"С помощью простого и удобного мониторинга <a href=\\\"https://www.starline-online.ru/\\\" target=\\\"_blank\\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение своего транспортного средства. Опция доступна при подключении <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\\" target=\\\"_blank\\\">GPS-ГЛОНАСС</a> и наличии опционального <a href=\\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\\" target=\\\"_blank\\\"> GSM-ИНТЕРФЕЙСА</a>.\"}, {\"title\": \"АВТОЗАПУСК\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время или периодически.\"}, {\"title\": \"2CAN+2LIN (ОПЦИЯ)\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN.\"}, {\"title\": \"ГИБКИЕ СЕРВИСНЫЕ КАНАЛЫ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое.\"}, {\"title\": \"УДАРОПРОЧНЫЙ БРЕЛОК\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Брелок StarLine имеет инновационную, ударопрочную конструкцию, эргономичный дизайн и внутреннюю защищенную антенну.\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД (ОПЦИЯ)\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, {\"title\": \"ХАРАКТЕРИСТИКИ\", \"content\": [{\"title\": \"Функции\", \"description\": \"Автозапуск, Управление предпусковым подогревом\", \"icon\": \"\"}, {\"title\": \"Категория\", \"description\": \"Для легкового авто, Для внедорожника\", \"icon\": \"\"}]}, {\"title\": \"СОПУТСТВУЮЩИЕ ТОВАРЫ\", \"content\": []}]', '2025-07-19 01:23:32'),
(2, 'product_keychain_e96-eco', '[{\"title\": \"ГАРАНТИЯ\", \"content\": []}, {\"title\": \"ОПИСАНИЕ\", \"content\": [{\"title\": \"РАСШИРЕННЫЙ ДИАПАЗОН ТЕМПЕРАТУР\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в суровых климатических условиях при температуре от минус 40 до плюс 85 °С благодаря высококачественным комплектующим\"}, {\"title\": \"УМНАЯ АВТОРИЗАЦИЯ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Только владелец получает разрешение на поездку после авторизации по защищенному протоколу через персональную метку или смартфон на iOS и Android с мобильным приложением StarLine.\"}, {\"title\": \"УМНЫЙ ДИАЛОГ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Умный диалоговый код управления c индивидуальными ключами шифрования гарантирует надежную защиту от всех известных кодграбберов\"}, {\"title\": \"ЗАЩИТА ОТ РАДИОПОМЕХ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine уверенно работает в условиях экстремальных городских радиопомех, благодаря уникальному узкополосному трансиверу с малошумящим усилителем\"}, {\"title\": \"УМНЫЙ 3D КОНТРОЛЬ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный 3D-контроль с дистанционной настройкой регистрирует удары, поддомкрачивание и эвакуацию автомобиля и оценивает безопасность вождения\"}, {\"title\": \"РЕКОРДНАЯ ЭНЕРГОЭКОНОМИЧНОСТЬ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней в режиме охраны благодаря использованию запатентованных прогрессивных технологий и программных решений\"}, {\"title\": \"SUPER SLAVE\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Управление охраной автомобиля штатным брелком с надежной дополнительной авторизацией по основному брелку StarLine\"}, {\"title\": \"АВТОРИЗАЦИЯ ПО PIN-КОДУ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации. Поездка возможна только после ввода индивидуального PIN-кода при помощи штатных кнопок автомобиля\"}, {\"title\": \"НЕВИДИМАЯ БЛОКИРОВКА\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки двигателя по штатным цифровым шинам автомобиля\"}, {\"title\": \"УМНЫЙ КЛИМАТ-КОНТРОЛЬ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя по температуре, в заданное время, по просадке АКБ или дням недели. Комфорт гарантирован\"}, {\"title\": \"60 ГИБКИХ НАСТРОЕК И СЦЕНАРИЕВ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал при постановке на охрану и многое другое для комфорта автовладельца\"}, {\"title\": \"УМНАЯ ЦИФРОВАЯ ИНТЕГРАЦИЯ\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"2CAN+4LIN интерфейс обеспечивает быструю, удобную и безопасную установку охранного оборудования StarLine на современные автомобили, оснащенные шинами CAN или LIN\"}, {\"title\": \"УМНЫЙ БЕСКЛЮЧЕВОЙ ОБХОД\", \"icon\": \"/client/vectors/thermometer.svg\", \"description\": \"Экономьте на покупке дополнительного обходчика или дубликате ключа, необходимых для реализации функции автозапуска двигателя. StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера и запуск двигателя по цифровым шинам CAN или LIN*<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\\"https://can.starline.ru/\\\" target=\\\"_blank\\\">CAN.STARLINE.RU</i>\"}]}, {\"title\": \"ХАРАКТЕРИСТИКИ\", \"content\": []}, {\"title\": \"СОПУТСТВУЮЩИЕ ТОВАРЫ\", \"content\": []}]', '2025-07-19 01:23:32'),
(39, 'product_keychain_733794ff-0b39-4465-b5de-f6e58e3f338e', '[{\"title\": \"Новая вкладка\", \"content\": [{\"title\": \"Новый элемент\", \"icon\": \"/uploads/tabs/product_keychain_733794ff-0b39-4465-b5de-f6e58e3f338e/icon_687e0d233431e2.80511755.avif\", \"description\": \"\"}]}]', '2025-07-21 09:43:26');


--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `TabsAdditionalProductsData`
--
ALTER TABLE `TabsAdditionalProductsData`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `TabsAdditionalProductsData`
--
ALTER TABLE `TabsAdditionalProductsData`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
