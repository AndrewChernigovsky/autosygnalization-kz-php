import Swal from 'sweetalert2';

// Обычное уведомление
export default function showSwal(title: string, text: string, icon: any) {
  Swal.fire({
    title: title,
    text: text,
    icon: icon,
    confirmButtonText: 'OK',
  });
}

// Функция для подтверждения действий
export function showConfirm(
  title: string, 
  text: string, 
  confirmButtonText: string = 'Подтвердить',
  cancelButtonText: string = 'Отмена'
): Promise<boolean> {
  return Swal.fire({
    title: title,
    text: text,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: confirmButtonText,
    cancelButtonText: cancelButtonText,
    reverseButtons: true, // Кнопка отмены слева, подтверждения справа
  }).then((result) => {
    return result.isConfirmed;
  });
}
