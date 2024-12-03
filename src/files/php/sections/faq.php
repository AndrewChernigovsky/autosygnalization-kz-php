<section class="faq" id="faq">
  <div class="container">
    <h2>вопросы-ответы</h2>
    <ol class="tabs-faq list-style-none">
      <li class="active">Какая информация нужна для расчета стоимости разработки сайта?</li>
      <li>От чего зависит цена на сайт?</li>
      <li>Как долго будет идти разработка сайта</li>
      <li>Используешь ли ты сайты на Wordpress и Joomla?</li>
    </ol>
    <hr>
    <ol class="tabs-content list-style-none">
      <li class="active">Здесь необходимо тщательно подготовиться к совместной работе, подробности, описание
        функционального и технического заданий - залог успеха</li>
      <li>Главная причина, которая может менять итоговую стоимость сайта это фактически затраченные часы на разработку.
        На цену влияет также тип сайта и его функционал. Иногда случается так, что лендинг может стоить дороже
        корпоративного сайта, потому что была необходимость в уникальном сложном функционале, дизайне и т д. На такие
        узконаправленные моменты может уйти несколько рабочих дней</li>
      <li>Подсчет рабочих часов необходимых для разработки сайта производится в функциональном задании, которое я
        формирую по результатам обработанной заявки или детального диалога. На создание сайта может уйти как два дня так
        и несколько месяцев. Такой разброс во времени происходит из-за разного подхода к созданию сайта и необходимому
        функционалу</li>
      <li>Нет. Много лишнего кода, это затрудняет вести правильную разработку, делать оптимизацию и оказывать поддержку
        таким
        сайтам</li>
    </ol>
  </div>
  <style>
    .tabs-faq {
      display: grid;
      gap: 10px;
    }

    .tabs-faq li {
      text-transform: none;
      min-height: 30px;
      background-color: rgba(35, 55, 255, 0.3);
      display: flex;
      align-items: center;
      padding: 5px 10px;
      cursor: pointer;
      color: white;
      transition: 0.3s ease-in-out;
    }

    .tabs-faq li:not(.active):hover {
      background-color: rgba(255, 255, 255, 1);
      color: black;
    }

    .tabs-faq li.active {
      background-color: rgba(35, 55, 255, 0.8);
      color: darkorange;
    }

    .tabs-content li {
      overflow: hidden;
      height: 0;
      text-transform: none;
    }

    .tabs-content li.active {
      color: darkorange;
      background-color: rgba(35, 55, 255, 0.3);
      padding: 10px;
      height: auto;
    }
  </style>
  <script>
    const questions = document.querySelectorAll('.tabs-faq li');
    const answers = document.querySelectorAll('.tabs-content li');

    function clearQuestionAnswers() {
      questions.forEach((question, index) => {
        question.classList.remove('active');
        answers[index].classList.remove('active');
      })
    }

    questions.forEach((question, index) => question.addEventListener('click', () => {
      clearQuestionAnswers()
      question.classList.add('active')
      answers[index].classList.toggle('active')
    }));
  </script>
</section>