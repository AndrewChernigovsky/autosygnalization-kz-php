export function formHandler() {
  document.getElementById('feedback-form').addEventListener('submit', function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('/dist/files/php/process_form.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (!data.success) {

          alert(data.errors.join('\n'));
        } else {
          alert('Форма успешно отправлена!');
          this.reset();
        }
      })
      .catch(error => {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при отправке формы.');
      });
  });
}
