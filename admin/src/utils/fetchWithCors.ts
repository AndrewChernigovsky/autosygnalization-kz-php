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

  if (!response.ok) {
    // Пытаемся получить сообщение об ошибке из тела ответа
    const errorData = await response.json().catch(() => ({
      error: `HTTP error! status: ${response.status}`,
    }));
    throw new Error(
      errorData.error || `HTTP error! status: ${response.status}`
    );
  }

  return response.json();
}
