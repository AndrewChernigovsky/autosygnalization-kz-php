import Swal from 'sweetalert2';

export default function showSwal(title: string, text: string, icon: any) {
  Swal.fire({
    title: title,
    text: text,
    icon: icon,
    confirmButtonText: 'OK',
  });
}
