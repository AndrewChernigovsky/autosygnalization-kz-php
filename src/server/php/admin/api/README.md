# Navigation API

Полноценный CRUD API для управления навигацией сайта с поддержкой иерархической структуры.

## Созданные файлы

1. **`navigation.php`** - Основной CRUD API для навигации
2. **`navigation_tree.php`** - API для работы с иерархическим деревом навигации
3. **`navigation_examples.md`** - Подробная документация с примерами использования
4. **`test_navigation.php`** - Тестовый скрипт для проверки API
5. **`../database/migrations/navigation.sql`** - Миграция таблицы Navigation
6. **`../database/migrations/navigation_seed.sql`** - Базовые данные для навигации

## Функциональность

### Основные операции (navigation.php):
- **GET** `/navigation.php` - Получение всех элементов навигации
- **GET** `/navigation.php?id={id}` - Получение конкретного элемента
- **POST** `/navigation.php` - Создание нового элемента
- **PUT** `/navigation.php?id={id}` - Обновление элемента
- **DELETE** `/navigation.php?id={id}` - Удаление элемента

### Дерево навигации (navigation_tree.php):
- **GET** `/navigation_tree.php` - Получение древовидной структуры навигации
- **POST** `/navigation_tree.php` - Массовое обновление позиций элементов

## Структура таблицы Navigation

```sql
- navigation_id (PRIMARY KEY)
- title (VARCHAR 255, NOT NULL)
- slug (VARCHAR 255, UNIQUE, NOT NULL)
- href (VARCHAR 255, NOT NULL)
- parent_id (INT, FK to Navigation)
- position (INT, DEFAULT 0)
- is_active (BOOLEAN, DEFAULT TRUE)
- icon (VARCHAR 255)
- target (VARCHAR 20, DEFAULT '_self')
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

## Особенности

1. **Валидация данных** - Проверка обязательных полей и уникальности slug
2. **Иерархическая структура** - Поддержка parent_id для создания подменю
3. **Позиционирование** - Поле position для сортировки элементов
4. **Активность** - Поле is_active для включения/отключения элементов
5. **Безопасность** - Подготовленные запросы, обработка ошибок
6. **CORS** - Настроенные заголовки для работы с фронтендом
7. **Транзакции** - Для массовых операций обновления позиций
8. **Логирование** - Детальное логирование всех операций

## Тестирование

Для тестирования API запустите:
```bash
php test_navigation.php
```

## Примеры использования

Подробные примеры с кодом JavaScript находятся в файле `navigation_examples.md`.

## Инициализация

При первом запуске автоматически создаются:
- Таблица Navigation
- Базовые элементы навигации (Главная, Каталог, Услуги, и т.д.)

## Архитектура

- Использование паттерна Singleton для подключения к БД
- Наследование от базового класса DataBase
- Стандартизированный формат ответов JSON
- Обработка OPTIONS запросов для CORS
- Единообразная обработка ошибок 