import fetchWithCors from '../utils/fetchWithCors';
import showSwal from './showSwal';

export default async function addItemOnDB(item: any, url: string) {
  try {
    const formData = new FormData();

    for (const key in item) {
      formData.append(key, item[key]);
    }

    showSwal('Подождите', 'Добавляем', 'info');
    await fetchWithCors(url, {
      method: 'POST',
      body: formData,
    });
    showSwal('Успешно', 'Добавлено', 'success');
  } catch (error) {
    showSwal('Ошибка', 'Не удалось добавить', 'error');
  }
}
