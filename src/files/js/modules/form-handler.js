export function formHandler() {
  document.getElementById('feedback-form').addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('/dist/files/php/data/process_form.php', {
      method: 'POST',
      body: formData
    })
      .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.json();
      })
      .then(data => {
        console.log('Received data:', data);
        if (!data.success) {
          alert(data.errors?.join('\n') || 'Произошла ошибка');
        } else {
          alert('Заявка успешно отправлена!');
          this.reset();
        }
      })
      .catch(error => {
        console.error('Подробности ошибки:', error);
        alert('Произошла ошибка при отправке заявки.');
      });
  });
}
