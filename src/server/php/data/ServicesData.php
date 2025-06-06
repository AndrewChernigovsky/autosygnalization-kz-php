<?php

namespace DATA;

class ServicesData
{
    public function getData(): array
    {
        return [
          "setup" => [
            "name" => " УСТАНОВКА И РЕМОНТ АВТОСИГНАЛИЗАЦИЙ",
            "description" => "<ul>
                <li>Вы купили автомобиль и желаете защитить его, установив сигнализацию?</li>
                <li>Вы любите комфорт и хотите установить автозапуск на Ваше авто?</li>
                <li>Вам необходимо отслеживать Ваш транспорт по GPS?</li>
                <li>Обращайтесь к нам, и мы поможем Вам решить эти задачи!</li>
              </ul>",
            "image" => [
              "src" => "/client/images/services/service-1.avif",
              "description" => "Картинка на которой изображена услуга Auto Security",
            ],
            "href" => "/service?service=setup",
            "services" => [
              "Продажу автосигнализаций, охранных комплексов, маяков GPS и мн.др.",
              "Профессиональную установку, ремонт, и настройку автосигнализаций различных брендов.",
              "Установку иммобилайзеров и противоугонных систем.",
              "Установку автозапуска, в том числе и со штатных пультов от автомобилей (некоторых видов).",
              "Привязку штатных охранных систем к современным охранно-телематическим комплексам.",
              "Установку сигнализаций с функцией GSM и GPS.",
              "Подключение модулей закрывания стекол.",
              "Подключение функции открывания багажника и др.",
              "Монтаж маячков и систем слежения за автомобилем.",
              "Диагностику неисправностей автосигнализаций.",
              "Ремонт автозапуска и обхода иммобилайзера.",
              "Настройку и запись пультов.",
              "Настройку чувствительности датчика удара.",
              "Замену сирен и клаксонов.",
              "Демонтаж старых автосигнализаций.",
              "Отключение блокировок двигателя.",
              "Установку оборудования с выездом.",
              "Гарантию на работу и на материал.",
              "Бесплатные консультации.",
              "Возможность выезда квалифицированного мастера к Вам на место для установки оборудования - от 3.000 тг."
            ],
            "cost" => 15000,
            "currency" => "₸"
          ],
          "locks" => [
            "name" => "РЕМОНТ ЦЕНТРОЗАМКОВ",
            "description" => "<ul>
                <li>Вы испытываете постоянные неудобства из-за неработающего центрального замка?</li>
                <li>Вы ищете специалистов в области ремонта систем центрозамков?</li>
                <li>У вас нет времени приехать в квалифицированный сервис для ремонта центрального замка?</li>
                <li>Обращайтесь к нам, и Вы получите качественные услуги по ремонту электронной части центральных замков!</li>
              </ul>",
            "image" => [
              "src" => "/client/images/services/service-2.avif",
              "description" => "Картинка на которой изображена услуга Ремонт Центрозамков",
            ],
            "href" => "/service?service=locks",
            "services" => [
              "Ремонт центральных замков различных автомобилей.",
              "Установка новых замков.",
              "Замена личинок замков.",
              "Диагностику неисправностей.",
              "Замену реле центрозамка.",
              "Ремонт и замену толкателей (моторчиков) центрального замка в дверях автомобиля.",
              "Ремонт проводки центрозамка.",
              "Бесплатную консультацию по телефону.",
              "Выезд профессионала к Вам на место - от 5.000 тг и выше."
            ],
            "cost" => 15000,
            "currency" => "₸"
          ],
          "setup-media" => [
            "name" => "УСТАНОВКА АВТОЗВУКА И МУЛЬТИМЕДИА",
            "description" => "<ul>
                <li>Вы желаете подобрать и купить новую магнитолу на Ваше авто?</li>
                <li>Вам необходимо установить имеющуюся магнитолу?</li>
                <li>Вы хотите получить качественные услуги в сфере установки автозвука?</li>
                <li>Вы желаете обновить музыку на Вашем авто?</li>
                <li>Обращайтесь к нам, и мы осуществим Ваши планы!</li>
              </ul>",
            "image" => [
              "src" => "/client/images/services/service-3.avif",
              "description" => "Картинка на которой изображена услуга Установка Автозвука и Мультимедиа",
            ],
            "href" => "/service?service=setup-media",
            "services" => [
              "Установка аудиосистем.",
              "Настройка мультимедийных систем.",
              "Интеграция с мобильными устройствами.",
              "Продажу, установку и настройку автомагнитол.",
              "Изготовление креплений для магнитол.",
              "Подключение функций мультируля.",
              "Подключение/отключение усилителей.",
              "Установку сабвуферов.",
              "Замену и первичную установку колонок.",
              "Установку мониторов и телевизоров (DVD/TV).",
              "Установку и замену камер заднего и переднего вида.",
              "Установку и замену антенн для радио.",
              "Выезд квалифицированного мастера к Вам на место - от 3.000 тг и выше.",
              "Доставку выбранного на нашем сайте оборудования."
            ],
            "cost" => 15000,
            "currency" => "₸"
          ],
          "setup-system-parking" => [
            "name" => "УСТАНОВКА СИСТЕМ ПАРКИНГА",
            "description" => "<ul>
                <li>Вы часто разбиваете или царапаете бампер?</li>
                <li>Вам необходим 'ассистент' парковки?</li>
                <li>Обращайтесь к нам, и мы поможем Вам решить эти проблемы!</li>
              </ul>",
            "image" => [
              "src" => "/client/images/services/service-4.avif",
              "description" => "Картинка на которой изображена услуга Установка Систем Паркинга",
            ],
            "href" => "/service?service=setup-system-parking",
            "services" => [
              "Установка датчиков парковки.",
              "Настройка камер заднего вида.",
              "Продажу систем паркинга.",
              "Установку и замену камер заднего вида.",
              "Бесплатные консультации.",
              "Выезд специалиста к Вам на место для монтажа оборудования - от 3.000 тг и выше."
            ],
            "cost" => 15000,
            "currency" => "₸"
          ],
          "autoelectric" => [
            "name" => "УСЛУГИ АВТОЭЛЕКТРИКА",
            "description" => "<ul>
                <li>У Вашей машины имеется неисправность по автоэлектрике?</li>
                <li>Не запускается двигатель?</li>
                <li>Вы хотите сделать компьютерную диагностику автомобиля?</li>
                <li>Вам нужно заменить фары или установить дополнительный свет?</li>
                <li>Обращайтесь к нам, и мы постараемся Вам помочь!</li>
              </ul>",
            "image" => [
              "src" => "/client/images/services/service-5.avif",
              "description" => "Картинка на которой изображена услуга Услуги Автоэлектрика",
            ],
            "href" => "/service?service=autoelectric",
            'services' => [
              'Диагностика электрической системы автомобиля.',
              'Ремонт электропроводки.',
              'Замена аккумуляторов.',
              'Установка дополнительного оборудования.',
              'Решение проблем с запуском двигателя, связанных с автоэлектрикой.',
              'Услугу "прикурить авто".',
              'Компьютерную диагностику и устранение неполадок.',
              'Установку ксенона, LED-ламп.',
              'Ремонт стартеров и генераторов.',
              'Активацию ходовых огней.',
              'Установку противотуманок.',
              'Замену лампочек освещения.',
              'Установку автомагнитол.',
              'Поиск и ремонт различных неисправностей.',
              'Мелкие работы по автоэлектрике.',
              'Установку автосигнализаций.',
              'Отключение блокировок двигателя.',
              'Прошивка блоков srs, восстановление  блоков srs airbag.',
              'Русификация магнитол KIA, Hyundai, Toyota, Lexus, GM.',
              'Ремонт центральных замков.',
              'Выезд мастера к Вам на место - от 5000 тг и выше.'
            ],
            "cost" => 15000,
            "currency" => "₸"
          ],
          'rus' => [
            'name' => 'РУСИФИКАЦИЯ АВТО И ЧИПТЮНИНГ',
            'description' => "<ul>
                <li>Вы желали бы поменять язык на штатном головном устройстве Kia, Hyundai, Toyota, Lexus, GM?</li>
                <li>Ваш авто после аварии и необходимо удалить ошибку по SRS?</li>
                <li>Необходимо убрать ошибки по мотору и катализатору?</li>
              </ul>",
            'image' => [
              'src' => '/client/images/services/service-6.avif',
              'description' => 'Картинка на которой изображена услуга Русификация авто.',
            ],
            'href' => '/service?service=rus',
            'services' => [
              'Русификация бортового компьютера.',
              'Чип-тюнинг для улучшения производительности.',
              'Настройка параметров двигателя.',
              'Русификация Kia, Hyundai',
              'Русификация Toyota, Lexus, Chevrolet и мн.др.',
              'Русификация китайских авто',
              'Установка приложений на Android',
              'Програмное удаление вторых датчиков кислорода (удаление лямда-зондов)',
              'Програмное удаление катализаторов, EGR, AdBlue, EVAP',
              'Перепрошивку блоков SRS',
              'Выезд мастера к Вам на место'
            ],
            "cost" => 15000,
            "currency" => "₸"
          ],
          'diagnostic' => [
            'name' => 'КОМПЬЮТЕРНАЯ ДИАГНОСТИКА',
            'description' => "<ul>
                <li>Вы купили автомобиль и хотите узнать об имеющихся компьютерных ошибках?</li>
                <li>На Вашем автомобиле внезапно загорелась какая-то сигнальная лампочка на щитке приборов?</li>
                <li>Обращайтесь к нам, и вы узнаете все имеющиеся ошибки и электронные неисправности Вашего автомобиля с помощью нашей услуги по компьютерной диагностике!</li>
              </ul>",
            'image' => [
              'src' => '/client/images/services/service-7.avif',
              'description' => 'Картинка на которой изображена услуга Компьютерная диагностика.',
            ],
            'href' => '/service?service=diagnostic',
            'services' => [
              'Полная диагностика автомобиля.',
              'Определение ошибок системы.',
              'Рекомендации по устранению неисправностей.',
              'Комплексную компьютерную диагностику автомобилей с 1998 г. с разъемом OBD2- от 5000 тг.',
              'Сброс ошибок.',
              'Диагностику отдельных электронных блоков*.',
              'Всегда в работе обновленный мультимарочный сканер Launch PRO с расширенными возможностями и функционалом.',
              'Консультации и советы по ремонту Вашего авто.',
              'Выезд квалифицированного специалиста к Вам на место- от 3000 тг и выше.'
            ],
            "cost" => 15000,
            "currency" => "₸"
          ],
          'disabled-autosynal' => [
            'name' => 'ОТКЛЮЧЕНИЕ СИГНАЛИЗАЦИИ',
            'description' => "<ul>
                <li>Отказала сигнализация?</li>
                <li>Хотите снять неисправное оборудование?</li>
                <li>Вы продаете свой автомобиль и желаете аккуратно снять сигнализацию для последующей установки на другую машину?</li>
                <li>Обращайтесь к нам, и вы получите квалифицированную помощь в отключении сигнализации!</li>
              </ul>",
            'image' => [
              'src' => '/client/images/services/service-8.avif',
              'description' => 'Картинка на которой изображена услуга Отключение сигнализации.',
            ],
            'href' => '/service?service=disabled-autosynal',
            'services' => [
              'Отключение старых сигнализаций.',
              'Настройка новых систем безопасности.',
              'Отключение автосигнализации.',
              'Полный или частичный демонтаж неисправного оборудования, согласно Вашим пожеланиям.',
              'Аккуратное снятие рабочей сигнализации для последующей установки на другой автомобиль.',
              'Восстановление проводки после снятия сигнализации в целостный вид.',
              'Отключение блокировок двигателя.',
              'Выезд квалифицированного мастера на место - от 3.000 тг и выше.',
              'Бесплатные консультации по телефону.'
            ],
            "cost" => 15000,
            "currency" => "₸"
          ],
          'setup-videoregistration' => [
            'name' => 'УСТАНОВКА ВИДЕОРЕГИСТРАТОРОВ И АНТИРАДАРОВ',
            'description' => "<ul>
                <li>Вы хотите купить и установить видеорегистратор или антирадар?</li>
                <li>Вам надоели болтающиеся провода?</li>
                <li>Вы хотите освободить гнездо (розетку) прикуривателя?</li>
                <li>Обращайтесь к нам, и мы сможем Вам помочь в решении этих вопросов!</li>
              </ul>",
            'image' => [
              'src' => '/client/images/services/service-9.avif',
              'description' => 'Картинка на которой изображена услуга Установка видеорегистраторов и антирадаров.'
            ],
            'href' => '/service?service=setup-videoregistration',
            'services' => [
              'Установка видеорегистраторов.',
              'Настройка антирадаров.',
              'Аккуратную установку видеорегистраторов и антирадаров.',
              'Настройку оборудования.',
              'Скрытое подключение: cпрячем все лишние провода от видеорегистратора или антирадара и освободим гнездо прикуривателя.',
              'Бесплатные консультации.',
              'Услугу выезда мастера к Вам на место - от 5000 тг и выше.',
              'Доставку выбранного Вами оборудования на нашем сайте.'
            ],
            "cost" => 15000,
            "currency" => "₸"
          ]
        ];
    }
}
