export default async function fetchWithCors(
  url: string,
  options: RequestInit = {}
) {
  const response = await fetch(url, {
    ...options,
    headers: {
      'Content-Type': 'application/json',
      ...options.headers,
    },
    credentials: 'include',
  });

  if (!response.ok) {
    console.log(response.json());
    throw new Error(`HTTP error! status: ${response.status}`);
  }

  return response.json();
}
