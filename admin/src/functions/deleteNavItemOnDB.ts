import fetchWithCors from '../utils/fetchWithCors';
import showSwal, { showConfirm } from './showSwal';

export default async function deleteNavItemOnDB(item: any, url: string) {
  try {
    // Показываем подтверждение удаления
    const confirmed = await showConfirm(
      'Подтвердите удаление',
      `Вы уверены, что хотите удалить "${item.title}"? Это действие нельзя отменить.`,
      'Удалить',
      'Отмена'
    );

    // Если пользователь отменил, выходим
    if (!confirmed) {
      return;
    }

    showSwal('Подождите', 'Удаляем', 'info');

    // Для навигации используем id как ID
    const id = item.id;
    if (!id) {
      throw new Error('ID навигационного элемента не найден');
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
