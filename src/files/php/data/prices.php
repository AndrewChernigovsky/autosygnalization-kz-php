<?php
include_once __DIR__ . '/../helpers/classes/setVariables.php';

$variables = new SetVariables();
$variables->setVar();

$path = $variables->getPathFileURL();

$prices = [
    [
        'title' => 'StarLine B97 v2 3CAN+FD+4LIN LTE-GPS', 
        'model' => 'StarLine',
        'id' => 'B97 v2 3CAN+FD+4LIN LTE-GPS',
        'productPrice' => '259 600',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 3CAN+FD+4LIN и Bluetooth Smart-интерфейсом, LTE-модулем, приемопередатчиком, брелок с обратной связью с ЖКИ - функция SLAVE,дополнительный брелок-метка BLE, силовой модуль запуска, навигационный модуль c ГЛОНАСС+GPSантенной, реле блокировки двигателя R6L, сирена, интегрированный SIM-чип+ SIM-холдер, комплекты проводов и печатной продукции).',
        'installationPrice' => '60 000',
    ],
    [
        'title' => 'StarLine B97 v2 3CAN+FD+4LIN LTE', 
        'model' => 'StarLine',
        'id' => 'B97 v2 3CAN+FD+4LIN LTE',
        'productPrice' => '226 300',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 3CAN+FD+4LIN и Bluetooth Smart-интерфейсом, LTE-модулем, приемопередатчиком, брелок с обратной связью с ЖКИ - функция SLAVE,дополнительный брелок-метка BLE, силовой модуль запуска, реле блокировки двигателя R7, сирена, интегрированный SIM-чип+ SIM-холдер, комплекты проводов и печатной продукции)',
        'installationPrice' => '60 000',
    ],
    [
        'title' => 'StarLine E96 V2 GSM-GPS PRO', 
        'model' => 'StarLine',
        'id' => 'E96 V2 GSM-GPS PRO',
        'productPrice' => '172 000',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и GSM-модулем, 3D датчиком удара, наклона и движения; приемопередатчик, навигационный модуль c ГЛОНАСС+GPS-антенной, брелок с обратной связью с ЖКИ- функция SLAVE, дополнительный брелок-метка BLE, интегрированный SIM-чип+ SIM-холдер, микрореле R7, различные варианты комплектаций SIM-картами, силовой модуль запуска, сирена, чехол кожаный, комплекты проводов и печатной продукции) ',
        'installationPrice' => '60 000',
    ],
    [
        'title' => 'StarLine S96 V2 LTE-GPS PRO', 
        'model' => 'StarLine',
        'id' => 'S96 V2 LTE-GPS PRO',
        'productPrice' => '170 100',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и LTE-модулем, навигационный модуль c ГЛОНАСС+GPS-антенной, 3D датчиком удара, наклона и движения, дополнительный брелок-метка BLE (2 шт.), силовой модуль запуска, сирена, реле R6L, интегрированный SIM-чип+ SIM-холдер, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции). ',
        'installationPrice' => '60 000',
    ],
    [
        'title' => 'StarLine E96 v2 GSM-GPS', 
        'model' => 'StarLine',
        'id' => 'E96 v2 GSM-GPS',
        'productPrice' => '144 900',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN и GSM, и Bluetooth Smart - интерфейсами и приемопередатчиком, навигационный модуль c ГЛОНАСС+GPS-антенной, 3D датчиком удара, наклона и движения, брелок с обратной связью с ЖКИ- функция SLAVE, дополнительный брелок-метка BLE, интегрированный SIM-чип МТС+ SIM-холдер, различные варианты комплектаций SIM-картами, силовой модуль запуска, сирена, чехол кожаный, комплекты проводов и печатной продукции)  ',
        'installationPrice' => '50 000',
    ],
    [
        'title' => 'StarLine S96 V2 LTE-GPS', 
        'model' => 'StarLine',
        'id' => 'S96 V2 LTE-GPS',
        'productPrice' => '133 900',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и LTE-модулем, навигационный модуль c ГЛОНАСС+GPS-антенной, 3D датчиком удара, наклона и движения, дополнительный брелок-метка BLE (2 шт.), силовой модуль запуска, сирена,  интегрированный SIM-чип+ SIM-холдер, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции)',
        'installationPrice' => '50 000',
    ],
    [
        'title' => 'StarLine E96 v2 GSM ECO', 
        'model' => 'StarLine',
        'id' => 'E96 v2 GSM ECO',
        'productPrice' => '127 300',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и GSM-модулем, 3D датчиком удара, наклона и движения; приемопередатчик, брелок с обратной связью с ЖКИ- функция SLAVE, интегрированный SIM-чип+ SIM-холдер, различные варианты комплектаций SIM-картами, силовой модуль запуска, сирена, комплекты проводов и печатной продукции).',
        'installationPrice' => '50 000',
    ],
    [
        'title' => 'StarLine А93 v2 2CAN+2LIN GSM ECO', 
        'model' => 'StarLine',
        'id' => 'А93 v2 2CAN+2LIN GSM ECO',
        'productPrice' => '127 300',
        'currency' => '₸',
        'description' => '(центральный блок c интегрированным GSM-модулем и интегрированным 2CAN+2LIN-интерфейсом,  брелок с обратной связью с ЖКИ- функция SLAVE, силовой модуль запуска, модуль приемопередатчика с интегрированными 3D датчиками удара и наклона, сирена, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции).',
        'installationPrice' => '50 000',
    ],
    [
        'title' => 'StarLine S96 v2 GPS', 
        'model' => 'StarLine',
        'id' => 'S96 v2 GPS',
        'productPrice' => '121 300',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и GSM-модулем, навигационный модуль c ГЛОНАСС+GPS-антенной, 3D датчиком удара, наклона и движения, дополнительный брелок-метка BLE (2 шт.), силовой модуль запуска, сирена,  интегрированный SIM-чип МТС+ SIM-холдер, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции). ',
        'installationPrice' => '50 000',
    ],
    [
        'title' => 'StarLine S96 V2 LTE ', 
        'model' => 'StarLine',
        'id' => 'S96 V2 LTE ',
        'productPrice' => '110 900',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и LTE-модулем, 3D датчиком удара, наклона и движения, дополнительный брелок-метка BLE (2 шт.), силовой модуль запуска, сирена,интегрированный SIM-чип+ SIM-холдер, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции). ',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine S96 v2', 
        'model' => 'StarLine',
        'id' => 'S96 v2',
        'productPrice' => '98 300',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и GSM-модулем, 3D датчиком удара, наклона и движения, дополнительный брелок-метка BLE (2 шт.), силовой модуль запуска, сирена,интегрированный SIM-чип МТС+ SIM-холдер, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine E96 V2', 
        'model' => 'StarLine',
        'id' => 'E96 V2',
        'productPrice' => '98 300',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN и Bluetooth Smart - интерфейсами и приемопередатчиком, 3D датчиком удара, наклона и движения, брелок с обратной связью с ЖКИ - функция SLAVE, дополнительный брелок-метка BLE, силовой модуль запуска, сирена, чехол кожаный, комплекты проводов и печатной продукции).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine А93 v2 2CAN+2LIN', 
        'model' => 'StarLine',
        'id' => 'А93 v2 2CAN+2LIN',
        'productPrice' => '98 300',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+2LIN-интерфейсом, брелок с обратной связью с ЖКИ- функция SLAVE, доп.брелок - функция SLAVE, силовой модуль запуска, модуль приемопередатчика с интегрированными 3D датчиками удара и наклона, сирена, комплекты проводов и печатной продукции).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine А93 v2 GSM', 
        'model' => 'StarLine',
        'id' => 'А93 v2 GSM',
        'productPrice' => '95 800',
        'currency' => '₸',
        'description' => '(центральный блок c интегрированным GSM-модулем, брелок с обратной связью с ЖКИ- функция SLAVE, доп.брелок- функция SLAVE, силовой модуль запуска, модуль приемопередатчика с интегрированными 3D датчиками удара и наклона, сирена,различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine S96 v2 ECO', 
        'model' => 'StarLine',
        'id' => 'S96 v2 ECO',
        'productPrice' => '92 600',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и GSM-модулем, 3D датчиком удара, наклона и движения, дополнительный брелок-метка BLE, силовой модуль запуска, сирена,интегрированный SIM-чип+ SIM-холдер, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine E96 V2 ECO', 
        'model' => 'StarLine',
        'id' => 'E96 V2 ECO',
        'productPrice' => '86 600',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN и Bluetooth Smart - интерфейсами и приемопередатчиком, 3D датчиком удара, наклона и движения, брелок с обратной связью с ЖКИ - функция SLAVE, силовой модуль запуска, сирена, комплекты проводов и печатной продукции).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine А93 v2 2CAN+2LIN ECO', 
        'model' => 'StarLine',
        'id' => 'А93 v2 2CAN+2LIN ECO',
        'productPrice' => '86 600',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+2LIN-интерфейсом, брелок с обратной связью с ЖКИ - функция SLAVE, силовой модуль запуска, модуль приемопередатчика с интегрированными 3D датчиками удара и наклона, сирена, комплекты проводов и печатной продукции).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine A93 v2', 
        'model' => 'StarLine',
        'id' => 'A93 v2',
        'productPrice' => '64 300',
        'currency' => '₸',
        'description' => '(центральный блок, брелок с обратной связью с ЖКИ- функция SLAVE, доп.брелок- функция SLAVE, силовой модуль запуска, модуль приемопередатчика с интегрированными 3D датчиками удара и наклона, сирена, комплекты проводов и печатной продукции).',
        'installationPrice' => '35 000',
    ],
    [
        'title' => 'StarLine А93 v2 ECO', 
        'model' => 'StarLine',
        'id' => 'А93 v2 ECO',
        'productPrice' => '56 100',
        'currency' => '₸',
        'description' => '(центральный блок, брелок с обратной связью с ЖКИ- функция SLAVE, силовой модуль запуска, модуль приемопередатчика с интегрированными 3D датчиками удара и наклона, сирена, комплекты проводов и печатной продукции).',
        'installationPrice' => '35 000',
    ],
    [
        'title' => 'StarLine A90 ECO', 
        'model' => 'StarLine',
        'id' => 'A90 ECO',
        'productPrice' => '52 300',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным приемопередатчиком, 3D датчиком удара, наклона и движения, брелок с обратной связью с ЖКИ, силовой модуль запуска, сирена, комплекты проводов и печатной продукции).',
        'installationPrice' => '35 000',
    ],
    [
        'title' => 'StarLine E66 v2 GSM ECO', 
        'model' => 'StarLine',
        'id' => 'E66 v2 GSM ECO',
        'productPrice' => '112 800',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и GSM-модулем, 3D датчиком удара, наклона и движения; приемопередатчик, брелок с обратной связью с ЖКИ- функция SLAVE, интегрированный SIM-чип+ SIM-холдер, различные варианты комплектаций SIM-картами, сирена, комплекты проводов и печатной продукции).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine S66 V2 LTE', 
        'model' => 'StarLine',
        'id' => 'S66 V2 LTE',
        'productPrice' => '93 900',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и LTE-модулем, 3D датчиком удара, наклона и движения, дополнительный брелок-метка BLE (2 шт.), сирена, интегрированный SIM-чип+ SIM-холдер, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции, автозапуск StarLine для автомобилей, не требующих силовых цепей; бесключевая технология обхода iKey).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine S66 v2', 
        'model' => 'StarLine',
        'id' => 'S66 v2',
        'productPrice' => '81 300',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и GSM-модулем, 3D датчиком удара, наклона и движения, дополнительный брелок-метка BLE (2 шт.), сирена, интегрированный SIM-чип МТС+ SIM-холдер, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции, автозапуск StarLine для автомобилей, не требующих силовых цепей; бесключевая технология обхода iKey).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine S66 v2 ECO', 
        'model' => 'StarLine',
        'id' => 'S66 v2 ECO',
        'productPrice' => '75 600',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN, Bluetooth Smart - интерфейсом и GSM-модулем, 3D датчиком удара, наклона и движения, дополнительный брелок-метка BLE, сирена, интегрированный SIM-чип+ SIM-холдер, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции, автозапуск StarLine для автомобилей, не требующих силовых цепей; бесключевая технология обхода iKey).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine E66 V2 ECO', 
        'model' => 'StarLine',
        'id' => 'E66 V2 ECO',
        'productPrice' => '73 700',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+4LIN и Bluetooth Smart - интерфейсами и приемопередатчиком, 3D датчиком удара, наклона и движения, брелок с обратной связью с ЖКИ - функция SLAVE с вертикальным дисплеем, сирена, комплекты проводов и печатной продукции), автозапуск StarLine для автомобилей, не требующих силовых цепей; бесключевая технология обхода iKey).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine А63 v2 2CAN+2LIN ECO', 
        'model' => 'StarLine',
        'id' => 'А63 v2 2CAN+2LIN ECO',
        'productPrice' => '73 700',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным 2CAN+2LIN-интерфейсом, брелок с обратной связью с ЖКИ -фунция SLAVE, модуль приемопередатчика с интегрированными 3D датчиками удара и наклона, сирена, комплекты проводов и печатной продукции, автозапуск StarLine для автомобилей, не требующих силовых цепей).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine А63 v2 GSM ECO', 
        'model' => 'StarLine',
        'id' => 'А63 v2 GSM ECO',
        'productPrice' => '72 500',
        'currency' => '₸',
        'description' => '(центральный блок с c интегрированным GSM-модулем, брелок с обратной связью с ЖКИ- функция SLAVE, модуль приемопередатчика с интегрированными 3D датчиками удара и наклона, сирена, различные варианты комплектаций SIM-картами, комплекты проводов и печатной продукции),автозапуск StarLine для автомобилей, не требующих силовых цепей).',
        'installationPrice' => '40 000',
    ],
    [
        'title' => 'StarLine A63 v2', 
        'model' => 'StarLine',
        'id' => 'A63 v2',
        'productPrice' => '50 400',
        'currency' => '₸',
        'description' => '(центральный блок, брелок с обратной связью с ЖКИ- функция SLAVE, доп.брелок, модуль приемопередатчика с интегрированными 3D датчиками удара и наклона, комплекты проводов и печатной продукции, автозапуск StarLine для автомобилей, не требующих силовых цепей).',
        'installationPrice' => '30 000',
    ],
    [
        'title' => 'StarLine A63 v2 ECO', 
        'model' => 'StarLine',
        'id' => 'A63 v2 ECO',
        'productPrice' => '43 500',
        'currency' => '₸',
        'description' => '(центральный блок, брелок с обратной связью с ЖКИ- функция SLAVE, модуль приемопередатчика с интегрированными 3D датчиками удара и наклона, комплекты проводов и печатной продукции, автозапуск StarLine для автомобилей, не требующих силовых цепей).',
        'installationPrice' => '30 000',
    ],
    [
        'title' => 'StarLine A60 ECO', 
        'model' => 'StarLine',
        'id' => 'A60 ECO',
        'productPrice' => '38 000',
        'currency' => '₸',
        'description' => '(центральный блок с интегрированным приемопередатчиком, 3D датчиком удара, наклона и движения, брелок с обратной связью с ЖКИ, комплекты проводов и печатной продукции, автозапуск StarLine для автомобилей, не требующих силовых цепей).',
        'installationPrice' => '30 000',
    ],
];

?>