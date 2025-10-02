export default async function fetchWithCors(
  url: string,
  options: RequestInit = {}
) {
  const headers = new Headers(options.headers);

  // Не устанавливаем Content-Type, если тело запроса - это FormData.
  // Браузер сделает это автоматически с правильным boundary.
  if (!(options.body instanceof FormData)) {
    headers.set('Content-Type', 'application/json');
  } else {
    // Если это FormData, убедимся, что Content-Type не установлен вручную
    headers.delete('Content-Type');
  }

  const response = await fetch(url, {
    ...options,
    headers,
    credentials: 'include',
  });

  // Получаем текст ответа для диагностики
  const responseText = await response.text();

  // Проверяем, является ли ответ HTML (ошибка PHP)
  if (responseText.trim().startsWith('<') || responseText.includes('<br />')) {
    console.error('Server returned HTML instead of JSON:', responseText);
    throw new Error(`Server error: ${responseText.substring(0, 200)}...`);
  }

  if (!response.ok) {
    // Пытаемся парсить как JSON
    try {
      const errorData = JSON.parse(responseText);
      throw new Error(
        errorData.error || `HTTP error! status: ${response.status}`
      );
    } catch (parseError) {
      // Если не JSON, показываем текст ответа
      throw new Error(
        `HTTP error! status: ${
          response.status
        }. Response: ${responseText.substring(0, 200)}...`
      );
    }
  }

  // Пытаемся парсить JSON
  try {
    return JSON.parse(responseText);
  } catch (parseError) {
    console.error('Failed to parse JSON response:', responseText);
    throw new Error(
      `Invalid JSON response: ${responseText.substring(0, 200)}...`
    );
  }
}
