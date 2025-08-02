# Navigation API - Примеры использования

## Базовые CRUD операции

### 1. Получение всех элементов навигации

```http
GET /admin/api/navigation.php
```

**Ответ:**

```json
{
  "success": true,
  "data": [
    {
      "navigation_id": 1,
      "title": "Главная",
      "slug": "home",
      "href": "/",
      "parent_id": null,
      "position": 1,
      "is_active": true,
      "icon": "home",
      "target": "_self",
      "created_at": "2024-01-01 12:00:00",
      "updated_at": "2024-01-01 12:00:00"
    }
  ]
}
```

### 2. Получение конкретного элемента навигации

```http
GET /admin/api/navigation.php?id=1
```

**Ответ:**

```json
{
  "success": true,
  "data": {
    "navigation_id": 1,
    "title": "Главная",
    "slug": "home",
    "href": "/",
    "parent_id": null,
    "position": 1,
    "is_active": true,
    "icon": "home",
    "target": "_self",
    "created_at": "2024-01-01 12:00:00",
    "updated_at": "2024-01-01 12:00:00"
  }
}
```

### 3. Создание нового элемента навигации

```http
POST /admin/api/navigation.php
Content-Type: application/json

{
  "title": "О нас",
  "slug": "about",
  "href": "/about",
  "parent_id": null,
  "position": 2,
  "is_active": true,
  "icon": "info",
  "target": "_self"
}
```

**Ответ:**

```json
{
  "success": true,
  "data": {
    "navigation_id": 2,
    "message": "Элемент навигации создан"
  }
}
```

### 4. Обновление элемента навигации

```http
PUT /admin/api/navigation.php?id=1
Content-Type: application/json

{
  "title": "Главная страница",
  "slug": "home",
  "href": "/",
  "parent_id": null,
  "position": 1,
  "is_active": true,
  "icon": "home",
  "target": "_self"
}
```

**Ответ:**

```json
{
  "success": true,
  "data": {
    "message": "Элемент навигации обновлен"
  }
}
```

### 5. Удаление элемента навигации

```http
DELETE /admin/api/navigation.php?id=1
```

**Ответ:**

```json
{
  "success": true,
  "data": {
    "message": "Элемент навигации удален"
  }
}
```

## Работа с иерархической структурой

### 6. Получение дерева навигации

```http
GET /admin/api/navigation_tree.php
```

**Ответ:**

```json
{
  "success": true,
  "data": [
    {
      "navigation_id": 1,
      "title": "Главная",
      "slug": "home",
      "href": "/",
      "parent_id": null,
      "position": 1,
      "is_active": true,
      "icon": "home",
      "target": "_self",
      "children": [
        {
          "navigation_id": 2,
          "title": "Подраздел",
          "slug": "subsection",
          "href": "/subsection",
          "parent_id": 1,
          "position": 1,
          "is_active": true,
          "icon": "folder",
          "target": "_self"
        }
      ]
    }
  ]
}
```

### 7. Обновление позиций элементов навигации

```http
POST /admin/api/navigation_tree.php
Content-Type: application/json

[
  {
    "navigation_id": 1,
    "position": 1,
    "parent_id": null
  },
  {
    "navigation_id": 2,
    "position": 2,
    "parent_id": 1
  }
]
```

**Ответ:**

```json
{
  "success": true,
  "data": {
    "message": "Позиции обновлены"
  }
}
```

## Примеры ошибок

### Ошибка валидации

```json
{
  "success": false,
  "error": "Поле title обязательно"
}
```

### Элемент не найден

```json
{
  "success": false,
  "error": "Элемент навигации не найден"
}
```

### Дублирование slug

```json
{
  "success": false,
  "error": "Slug уже существует"
}
```

## Структура таблицы Navigation

```sql
CREATE TABLE Navigation (
    navigation_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    href VARCHAR(255) NOT NULL,
    parent_id INT NULL,
    position INT NOT NULL DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    icon VARCHAR(255) NULL,
    target VARCHAR(20) DEFAULT '_self',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES Navigation(navigation_id) ON DELETE CASCADE
);
```

## Использование в JavaScript

```javascript
// Получение всех элементов навигации
const getNavigation = async () => {
  const response = await fetch('/admin/api/navigation.php');
  const data = await response.json();
  return data;
};

// Создание нового элемента
const createNavigation = async (navigationData) => {
  const response = await fetch('/admin/api/navigation.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(navigationData),
  });
  const data = await response.json();
  return data;
};

// Обновление элемента
const updateNavigation = async (id, navigationData) => {
  const response = await fetch(`/admin/api/navigation.php?id=${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(navigationData),
  });
  const data = await response.json();
  return data;
};

// Удаление элемента
const deleteNavigation = async (id) => {
  const response = await fetch(`/admin/api/navigation.php?id=${id}`, {
    method: 'DELETE',
  });
  const data = await response.json();
  return data;
};

// Получение дерева навигации
const getNavigationTree = async () => {
  const response = await fetch('/admin/api/navigation_tree.php');
  const data = await response.json();
  return data;
};
```
