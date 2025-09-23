<?php 
require_once __DIR__ . '/../../../../vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

use DATABASE\DataBase;
use Exception;

class ContactAPI extends DataBase
{
  protected $pdo;
    
  public function __construct()
  {
    $db = DataBase::getInstance();
    $this->pdo = $db->getPdo();
  }

  // Получение всех контактов
  public function getContacts()
  {
    try {
      $query = "SELECT * FROM Contacts 
ORDER BY type ASC, sort_order ASC";
      $stmt = $this->pdo->prepare($query);
      $stmt->execute();
      $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

      error_log("Получены все элементы контактов: " . count($result));
      return $result;
    } catch (Exception $e) {
      error_log("Ошибка получения контактов: " . $e->getMessage());
      return ['error' => "Ошибка получения контактов"];
    }
  }
}

$contactsInit = new ContactAPI();
$allContacts = $contactsInit->getContacts();

// Исключаем элементы с типом "Карта"
$contacts = array_filter($allContacts, function($contact) {
    return $contact['type'] !== 'Карта' && $contact['type'] !== 'Как к нам добраться';
});


error_log(print_r($contacts, true) . 'CONTACTS');

// Проверяем, что запрос пришел с формы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_pdf'])) {
    
    // Логируем полученные данные для отладки
    error_log('POST data received: ' . print_r($_POST, true));
    
    // Проверяем и декодируем данные
    if (isset($_POST['products'])) {
        $products = json_decode($_POST['products'], true);
        
        // Декодируем дополнительные услуги (если есть)
        $addedServices = [];
        if (isset($_POST['addedServices'])) {
            $addedServices = json_decode($_POST['addedServices'], true);
            error_log('Added services: ' . print_r($addedServices, true));
        }
        
        error_log('Products count: ' . count($products));
        error_log('Added services count: ' . count($addedServices));
        
        if (json_last_error() === JSON_ERROR_NONE && is_array($products)) {
            // Подключаем TCPDF
            require_once __DIR__ . '/../../../../vendor/tecnickcom/tcpdf/tcpdf.php';
            
            // Создаем PDF
            $filename = 'Auto-Security-price-' . date('Y-m-d') . '.pdf';
            
            try {
                // Передаем contacts в функцию создания PDF
                $savedPath = createPriceListPDF($products, $addedServices, $contacts, $filename);
                error_log('PDF created at: ' . $savedPath);
                
                // Отправляем файл для скачивания
                if (file_exists($savedPath)) {
                    // Очищаем буфер вывода
                    if (ob_get_length()) {
                        ob_clean();
                    }
                    
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="' . $filename . '"');
                    header('Content-Length: ' . filesize($savedPath));
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    
                    readfile($savedPath);
                    
                    // Удаляем временный файл после отправки
                    unlink($savedPath);
                    exit;
                } else {
                    error_log('PDF file not found: ' . $savedPath);
                    http_response_code(500);
                    echo "Ошибка создания PDF файла";
                    exit;
                }
            } catch (Exception $e) {
                error_log('Error creating PDF: ' . $e->getMessage());
                http_response_code(500);
                echo "Ошибка: " . $e->getMessage();
                exit;
            }
        } else {
            error_log('JSON decode error: ' . json_last_error_msg());
            http_response_code(400);
            echo "Ошибка декодирования данных: " . json_last_error_msg();
            exit;
        }
    } else {
        error_log('No products data in POST');
        http_response_code(400);
        echo "Неверные данные - нет информации о продуктах";
        exit;
    }
} else {
    error_log('Invalid request method or missing generate_pdf parameter');
    http_response_code(405);
    echo "Метод не разрешен или отсутствует параметр generate_pdf";
    exit;
}

function createPriceListPDF($products, $addedServices = [], $contacts = [], $filename = 'price_list.pdf')
{
    // Создаем экземпляр TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Настройки документа
    $pdf->SetCreator('Auto Security');
    $pdf->SetTitle('Прайс-лист Auto Security');
    $pdf->SetAuthor('Auto Security');
    
    // Устанавливаем margins
    $pdf->SetMargins(20, 25, 20);
    $pdf->SetHeaderMargin(10);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 25);
    
    // Добавляем страницу
    $pdf->AddPage();
    
    // Заголовок
    $pdf->SetFont('dejavusans', 'B', 20);
    $pdf->Cell(0, 10, 'ПРАЙС-ЛИСТ AUTO SECURITY', 0, 1, 'C');
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->Cell(0, 10, 'Актуально на: ' . date('d.m.Y'), 0, 1, 'C');
    $pdf->Ln(10);
    
    // --- ВЫВОД КОНТАКТОВ В НАЧАЛЕ ДОКУМЕНТА ---
    if (!empty($contacts) && !isset($contacts['error'])) {
        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->Cell(0, 10, 'Наши контакты:', 0, 1);
        $pdf->Ln(5);
        
        $pdf->SetFont('dejavusans', '', 10);
        
        // Группируем контакты по типам
        $groupedContacts = [];
        foreach ($contacts as $contact) {
            if (isset($contact['on_page']) && $contact['on_page'] == 1) { // Только контакты, отмеченные для отображения на странице
                $groupedContacts[$contact['type']][] = $contact;
            }
        }
        
        // Выводим контакты по группам
        foreach ($groupedContacts as $type => $typeContacts) {
            $pdf->SetFont('dejavusans', 'B', 11);
            $pdf->Cell(0, 8, $type . ':', 0, 1);
            
            foreach ($typeContacts as $contact) {
                $pdf->SetFont('dejavusans', '', 10);
                
                $contactText = '';
                if (!empty($contact['title'])) {
                    $contactText .= $contact['title'] . ' ';
                }
                if (!empty($contact['content'])) {
                    $cleanContent = strip_tags($contact['content']);
                    $contactText .= $cleanContent;
                }
                
                if (!empty($contactText)) {
                    $pdf->Cell(0, 6, '• ' . $contactText, 0, 1);
                }
            }
            $pdf->Ln(2);
        }
        
        $pdf->Ln(10);
    }
    // --- КОНЕЦ ВЫВОДА КОНТАКТОВ ---
    
    // Разделительная линия перед оборудованием
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
    $pdf->Ln(15);
    
    // Раздел: Оборудование
    $pdf->SetFont('dejavusans', 'B', 16);
    $pdf->Cell(0, 10, 'Оборудование Starline:', 0, 1);
    $pdf->Ln(5);
    
    // Заполняем данными в виде абзацев
    foreach ($products as $index => $product) {
        // Проверяем, не нужно ли новую страницу
        if ($pdf->GetY() > 250) {
            $pdf->AddPage();
            // После добавления страницы сбрасываем шрифт
            $pdf->SetFont('dejavusans', 'B', 12);
        }
        
        // Номер позиции, название товара и цена в одной строке
        $pdf->SetFont('dejavusans', 'B', 12);
        
        // Левая часть: номер и название
        $leftText = ($index + 1) . '. ' . $product['title'];
        $pdf->Cell(120, 8, $leftText, 0, 0, 'L');
        
        // Правая часть: цена
        $priceText = number_format($product['price'], 0, ',', ' ') . ' ' . ($product['currency'] ?? '₽');
        $pdf->Cell(0, 8, $priceText, 0, 1, 'R');
        
        $pdf->Ln(2);
        
        // Описание
        if (!empty($product['description']) || !empty($product['content'])) {
            $pdf->SetFont('dejavusans', '', 10);
            $description = !empty($product['description']) ? $product['description'] : $product['content'];
            $description = strip_tags($description);
            $pdf->MultiCell(0, 6, $description, 0, 'L');
            $pdf->Ln(3);
        }
        
        // Установка от
        $pdf->SetFont('dejavusans', 'B', 11);
        $installationPrice = 'Установка от: уточняйте';

        if (!empty($product['price_list'])) {
            $priceListData = json_decode($product['price_list'], true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($priceListData) && !empty($priceListData[0]['price'])) {
                // Извлекаем цену из первого элемента массива
                $installationPrice = 'Установка от: ' . $priceListData[0]['price'] . ' ' . ($priceListData[0]['currency'] ?? '₽');
            }
        }
        $pdf->Cell(0, 8, $installationPrice, 0, 1);
        
        // Разделительная линия между товарами
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
        $pdf->Ln(10);
    }
    
    // Раздел: Дополнительные услуги (если есть)
    if (!empty($addedServices) && is_array($addedServices)) {
        // Проверяем, нужно ли новую страницу для дополнительных услуг
        if ($pdf->GetY() > 200) {
            $pdf->AddPage();
        }
        
        // Заголовок раздела
        $pdf->SetFont('dejavusans', 'B', 16);
        $pdf->Cell(0, 10, 'Цены на дополнительные услуги:', 0, 1);
        $pdf->Ln(8);
        
        // Добавляем дополнительные услуги
        foreach ($addedServices as $index => $service) {
            // Проверяем, не нужно ли новую страницу
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();
                // После добавления страницы сбрасываем шрифт
                $pdf->SetFont('dejavusans', 'B', 12);
            }
            
            // Наименование услуги и цена в одной строке
            $pdf->SetFont('dejavusans', 'B', 12);
            
            // Левая часть: номер и название услуги
            $serviceText = ($index + 1) . '. ' . $service['title'];
            $pdf->Cell(140, 8, $serviceText, 0, 0, 'L');
            
            // Правая часть: цена услуги
            $pdf->Cell(0, 8, $service['price'], 0, 1, 'R');
            
            // Разделительная линия между услугами
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
            $pdf->Ln(8);
        }
        
        $pdf->Ln(10);
    }
    
    // Добавляем примечание внизу
    $pdf->SetFont('dejavusans', 'I', 10);
    $pdf->MultiCell(0, 8, '* Цена услуг зависит от автомобиля и сложности работ. Обязательно нужно уточнять у мастера совместимость оборудования и необходимый набор функций. Все нюансы оговариваются при осмотре автомашины.', 0, 'L');
    
    // Создаем директорию если не существует
    $saveDir = __DIR__ . '/pdf_documents/';
    if (!file_exists($saveDir)) {
        mkdir($saveDir, 0755, true);
    }
    
    $savePath = $saveDir . $filename;
    $pdf->Output($savePath, 'F');
    
    return $savePath;
}