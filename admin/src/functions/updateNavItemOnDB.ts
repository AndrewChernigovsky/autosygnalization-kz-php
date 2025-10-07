import fetchWithCors from '../utils/fetchWithCors';
import showSwal from './showSwal';

export default async function updateNavItemOnDB(item: any, url: string) {
  try {
    showSwal('Подождите', 'Обновляем', 'info');

    const id = item.id;
    if (!id) {
      throw new Error('ID навигационного элемента не найден');
    }

    const formData = new FormData();

    for (const key in item) {
      formData.append(key, item[key]);
    }

    const updateUrl = `${url}?id=${id}`;

    const response = await fetchWithCors(updateUrl, {
      method: 'POST',
      body: formData,
    });

    if (response.success) {
      const message = response.data?.message || 'Обновлено';
      showSwal('Успешно', message, 'success');
    } else {
      throw new Error(response.error || 'Ошибка обновления');
    }
  } catch (error) {
    console.error('Update error:', error);
    showSwal('Ошибка', 'Не удалось обновить', 'error');
  }
}
