-- Обновление иконок в TabsAdditionalProductsData
-- Замена /client/vectors/thermometer.svg на соответствующие .avif изображения

-- Диалоговая защита
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Диалоговый код управления StarLine c индивидуальными ключами шифрования"', 
  '"path-icon": "/client/images/products/advantages/dialogue-protection.avif", "description": "Диалоговый код управления StarLine c индивидуальными ключами шифрования"'
);

-- Защита от радиопомех
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "StarLine уверенно работает в условиях экстремальных городских радиопомех"', 
  '"path-icon": "/client/images/products/advantages/radio-protection.avif", "description": "StarLine уверенно работает в условиях экстремальных городских радиопомех"'
);

-- Расширенный диапазон температур
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "StarLine уверенно работает в суровых климатических условиях при температуре от минус"', 
  '"path-icon": "/client/images/products/advantages/temperature-range.avif", "description": "StarLine уверенно работает в суровых климатических условиях при температуре от минус"'
);

-- Рекордная энергоэкономичность
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней"', 
  '"path-icon": "/client/images/products/advantages/energy-eco.avif", "description": "StarLine гарантирует сохранность достаточного заряда аккумулятора до 60 дней"'
);

-- SUPER SLAVE
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Управление охраной автомобиля штатным брелком с надежной дополнительной"', 
  '"path-icon": "/client/images/products/advantages/super-slave.avif", "description": "Управление охраной автомобиля штатным брелком с надежной дополнительной"'
);

-- Авторизация по PIN-коду
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации"', 
  '"path-icon": "/client/images/products/advantages/pin-authorization.avif", "description": "Защитите автомобиль от угона даже в случае кражи ключей или меток благодаря умной дополнительной авторизации"'
);

-- 3D датчик удара и наклона
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Интегрированный цифровой датчик удара и наклона"', 
  '"path-icon": "/client/images/products/advantages/3d-control.avif", "description": "Интегрированный цифровой датчик удара и наклона"'
);

-- 3D контроль
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Интеллектуальный 3D-контроль с дистанционной настройкой"', 
  '"path-icon": "/client/images/products/advantages/3d-control.avif", "description": "Интеллектуальный 3D-контроль с дистанционной настройкой"'
);

-- Невидимая блокировка
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки"', 
  '"path-icon": "/client/images/products/advantages/invisible-blocking.avif", "description": "iCAN гарантирует надежную защиту благодаря уникальной запатентованной технологии скрытой блокировки"'
);

-- Контроль канала связи
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Автоматический контроль канала связи обеспечивает"', 
  '"path-icon": "/client/images/products/advantages/control-channel.avif", "description": "Автоматический контроль канала связи обеспечивает"'
);

-- Автозапуск
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя"', 
  '"path-icon": "/client/images/products/advantages/autostart.avif", "description": "Интеллектуальный автозапуск позволяет осуществлять дистанционный и автоматический запуск двигателя"'
);

-- Телематика (вариант 1) - с GSM ссылкой
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Интеграция опциональных <a href=\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\" target=\\"_blank\\">GSM</a>,<a href=\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\" target=\\"_blank\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг."', 
  '"path-icon": "/client/images/products/advantages/telematics.avif", "description": "Интеграция опциональных <a href=\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/?ncc=1\\" target=\\"_blank\\">GSM</a>,<a href=\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gps_glonass_master/\\" target=\\"_blank\\">GPS-ГЛОНАСС</a> телематических интерфейсов позволяет дистанционно управлять охраной вашего автомобиля и осуществлять мониторинг."'
);

-- Телематика (вариант 2)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Управляйте охраной автомобиля из любой точки мира. Передовые технологии GSM-GPRS, GPS-ГЛОНАСС"', 
  '"path-icon": "/client/images/products/advantages/telematics.avif", "description": "Управляйте охраной автомобиля из любой точки мира. Передовые технологии GSM-GPRS, GPS-ГЛОНАСС"'
);

-- Управление с телефона (вариант 1)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Интеграция опционального <a href=\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\" target=\\"_blank\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями"', 
  '"path-icon": "/client/images/products/advantages/gsm-control.avif", "description": "Интеграция опционального <a href=\\"https://store.starline.ru/catalog/dopolnitelnoe_oborudovanie/starline_gsm_master/\\" target=\\"_blank\\"> GSM-ИНТЕРФЕЙСА</a> позволяет управлять охранными и сервисными функциями"'
);

-- Управление с телефона (вариант 2)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Управляйте охранными и сервисными функциями, получайте оповещения о статусе охраны на ваш мобильный телефон"', 
  '"path-icon": "/client/images/products/advantages/gsm-control.avif", "description": "Управляйте охранными и сервисными функциями, получайте оповещения о статусе охраны на ваш мобильный телефон"'
);

-- Управление с телефона (вариант 3)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Интегрированный GSM-интерфейс позволяет управлять охранными и сервисными функциями"', 
  '"path-icon": "/client/images/products/advantages/gsm-control.avif", "description": "Интегрированный GSM-интерфейс позволяет управлять охранными и сервисными функциями"'
);

-- Умная авторизация (вариант 1)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Только владелец получает разрешение на поездку после авторизации по защищенному протоколу"', 
  '"path-icon": "/client/images/products/advantages/smart-authorization.avif", "description": "Только владелец получает разрешение на поездку после авторизации по защищенному протоколу"'
);

-- Умная авторизация (вариант 2)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Только владелец получает разрешение на поездку после авторизации по персональной метке"', 
  '"path-icon": "/client/images/products/advantages/smart-authorization.avif", "description": "Только владелец получает разрешение на поездку после авторизации по персональной метке"'
);

-- Умный диалог
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Умный диалоговый код управления c индивидуальными ключами шифрования"', 
  '"path-icon": "/client/images/products/advantages/dialogue-protection.avif", "description": "Умный диалоговый код управления c индивидуальными ключами шифрования"'
);

-- Гибкие настройки
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал"', 
  '"path-icon": "/client/images/products/advantages/flexible-settings.avif", "description": "Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал"'
);

-- 2CAN+2LIN (вариант 1)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "2CAN+2LIN интерфейс обеспечивает быструю, удобную и безопасную установку"', 
  '"path-icon": "/client/images/products/advantages/2can2lin.avif", "description": "2CAN+2LIN интерфейс обеспечивает быструю, удобную и безопасную установку"'
);

-- 2CAN+4LIN
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "2CAN+4LIN интерфейс обеспечивает быструю, удобную и безопасную установку"', 
  '"path-icon": "/client/images/products/advantages/2can-4lin.avif", "description": "2CAN+4LIN интерфейс обеспечивает быструю, удобную и безопасную установку"'
);

-- 3CAN+4LIN
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "3CAN+4LIN интерфейс обеспечивает быструю, удобную и безопасную установку"', 
  '"path-icon": "/client/images/products/advantages/3can4lin.avif", "description": "3CAN+4LIN интерфейс обеспечивает быструю, удобную и безопасную установку"'
);

-- 2CAN+2LIN (вариант 2)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку"', 
  '"path-icon": "/client/images/products/advantages/2can2lin.avif", "description": "Интеграция опционального 2CAN+2LIN интерфейса обеспечивает быструю, удобную и безопасную установку"'
);

-- 2CAN+2LIN (вариант 3)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Обеспечивает бережную установку и минимальное вмешательство в электронику автомобиля"', 
  '"path-icon": "/client/images/products/advantages/2can2lin.avif", "description": "Обеспечивает бережную установку и минимальное вмешательство в электронику автомобиля"'
);

-- iKey (вариант 1)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера"', 
  '"path-icon": "/client/images/products/advantages/iKey.avif", "description": "StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера"'
);

-- iKey (вариант 2)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Экономьте на покупке дополнительного обходчика или дубликате ключа"', 
  '"path-icon": "/client/images/products/advantages/iKey.avif", "description": "Экономьте на покупке дополнительного обходчика или дубликате ключа"'
);

-- Ударопрочный брелок
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Брелок StarLine имеет инновационную, ударопрочную конструкцию"', 
  '"path-icon": "/client/images/products/advantages/shockproff-keychain.avif", "description": "Брелок StarLine имеет инновационную, ударопрочную конструкцию"'
);

-- Умная автодиагностика
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Экономьте на диагностике транспортного средства — получайте информацию о состоянии автомобиля"', 
  '"path-icon": "/client/images/products/advantages/functions-smart-diagnostic.avif", "description": "Экономьте на диагностике транспортного средства — получайте информацию о состоянии автомобиля"'
);

-- GPS-ГЛОНАСС / Бесплатный мониторинг
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "С помощью простого и удобного мониторинга <a href=\\"https://www.starline-online.ru/\\" target=\\"_blank\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение"', 
  '"path-icon": "/client/images/products/advantages/gps-glonass.avif", "description": "С помощью простого и удобного мониторинга <a href=\\"https://www.starline-online.ru/\\" target=\\"_blank\\">STARLINE.ONLINE</a> вы сможете с точностью до нескольких метров узнать местонахождение"'
);

-- Звуковой извещатель
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "При реализации функции авторизации по PIN-коду настраивается звуковое оповещение"', 
  '"path-icon": "/client/images/products/advantages/sound.avif", "description": "При реализации функции авторизации по PIN-коду настраивается звуковое оповещение"'
);

-- Защита от ретрансляции
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Надежная защита от перехвата сигнала штатного брелока автомобиля благодаря умной блокировке KeyLess"', 
  '"path-icon": "/client/images/products/advantages/radio-protection.avif", "description": "Надежная защита от перехвата сигнала штатного брелока автомобиля благодаря умной блокировке KeyLess"'
);

-- 2SIM (вариант 1)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Режим автоматического переключения SIM-карт обеспечивает постоянный контроль транспортного средства"', 
  '"path-icon": "/client/images/products/advantages/2sim.avif", "description": "Режим автоматического переключения SIM-карт обеспечивает постоянный контроль транспортного средства"'
);

-- 2SIM (вариант 2)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Интегрированный SIM-чип МТС обеспечивает высокое качество сотовой связи"', 
  '"path-icon": "/client/images/products/advantages/2sim.avif", "description": "Интегрированный SIM-чип МТС обеспечивает высокое качество сотовой связи"'
);

-- Гибкие сервисные каналы
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое"', 
  '"path-icon": "/client/images/products/advantages/flex-channel.avif", "description": "Программируемые параметры управления аварийной световой сигнализацией, складыванием зеркал, настройкой сидений под владельца и многое другое"'
);

-- Дополнительная авторизация
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Дополнительная авторизация надежно защищает автомобиль от угона"', 
  '"path-icon": "/client/images/products/advantages/pin-authorization.avif", "description": "Дополнительная авторизация надежно защищает автомобиль от угона"'
);

-- GSM-GPRS телематический интерфейс
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "GSM-GPRS телематический интерфейс позволяет дистанционно управлять охраной"', 
  '"path-icon": "/client/images/products/advantages/telematics.avif", "description": "GSM-GPRS телематический интерфейс позволяет дистанционно управлять охраной"'
);

-- Умная авторизация по смартфону
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Только владелец получает разрешение на поездку после авторизации по смартфону на платформе iOS или Android с мобильным приложением StarLine по защищенной технологии Bluetooth Smart"', 
  '"path-icon": "/client/images/products/advantages/authorization-smartphone.avif", "description": "Только владелец получает разрешение на поездку после авторизации по смартфону на платформе iOS или Android с мобильным приложением StarLine по защищенной технологии Bluetooth Smart"'
);

-- Всегда на связи
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "Автоматическое переключение с SIM-чип на SIM-карту другого оператора гарантирует надежный прием сотовой связи"', 
  '"path-icon": "/client/images/products/advantages/2sim.avif", "description": "Автоматическое переключение с SIM-чип на SIM-карту другого оператора гарантирует надежный прием сотовой связи"'
);

-- Умный бесключевой обход с can.starline.ru (исправляем незакрытый тег)
UPDATE TabsAdditionalProductsData 
SET tabs_data = REPLACE(tabs_data, 
  '"path-icon": "/client/vectors/thermometer.svg", "description": "StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\"https://can.starline.ru/\\" target=\\"_blank\\">CAN.STARLINE.RU</a></i>"', 
  '"path-icon": "/client/images/products/advantages/iKey.avif", "description": "StarLine iKey позволяет реализовать бесключевой обход штатного иммобилайзера в автомобилях, оборудованных охранными комплексами StarLine. Опция доступна при подключении 2CAN+2LIN интерфейса*.<br><i>*Список автомобилей, поддерживающих данную функцию, смотрите на <a href=\\"https://can.starline.ru/\\" target=\\"_blank\\">CAN.STARLINE.RU</a></i>"'
);

-- Проверяем результат
SELECT product_id, 
       CASE 
         WHEN tabs_data LIKE '%thermometer.svg%' THEN 'НЕ ОБНОВЛЕНО'
         ELSE 'ОБНОВЛЕНО'
       END as status,
       LENGTH(tabs_data) as data_length
FROM TabsAdditionalProductsData 
WHERE tabs_data LIKE '%"path-icon":%'
ORDER BY id;
