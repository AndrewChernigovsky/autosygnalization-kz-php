import fetchWithCors from '../utils/fetchWithCors';
import showSwal from './showSwal';

export default async function deleteItemOnDB(item: any, url: string) {
  try {
    showSwal('Подождите', 'Удаляем', 'info');

    // Для контактов используем contact_id как ID
    const id = item.contact_id;
    if (!id) {
      throw new Error('ID контакта не найден');
    }

    // Добавляем ID в URL как параметр, body для DELETE не нужен
    const deleteUrl = `${url}?id=${id}`;

    await fetchWithCors(deleteUrl, {
      method: 'DELETE',
      // body не передаем для DELETE запроса
    });
    showSwal('Успешно', 'Удалено', 'success');
  } catch (error) {
    console.error('Delete error:', error);
    showSwal('Ошибка', 'Не удалось удалить', 'error');
  }
}
