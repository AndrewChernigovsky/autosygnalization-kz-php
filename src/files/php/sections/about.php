<?php
$distPath = $_SERVER['DOCUMENT_ROOT'] . '/dist';

if (is_dir($distPath)) {
  $currentUrl = "http://localhost:3000/dist/index.php";
  $pathFile = "http://localhost:3000/dist";
} else {
  $currentUrl = "/index.php";
  $pathFile = "";
}

$imagePath = $pathFile . '/assets/images/'
  ?>

<section class="about" id="about">
  <div class="container">
    <div class="about__wrapper">
      <img src="<?php echo $imagePath . 'avatar-1.avif' ?>" alt="аватар Изосимова Андрея Андреевича" width="300"
        height="300">
      <div class="about__text">
        <h2>КТО Я ТАКОЙ</h2>
        <p class="base-text">Сайт менялся и меняется всю мою осозанную жизнь. Его предназначение и место в жизнях людей
          каждое десятилетей несет новую роль. Также и сейчас, раньше сайт был заменой социальным сетям в каком-то
          смысле, он решал вопрос продаж как практически один из немногих онлайн-инструментов в интернете. </p>
        <p class="base-text">Сегодня таких инструментов сотни и сайт уже требует более четкого понимания почему нужно
          использовать именно его и кому он подойдет, а кому нет. К созданию сайтов я пришел не сразу, постепенно эта
          тема мелькала в моей жизни начиная с 14 лет, всегда привлекала возможность, творить в жизнь и делится тем, что
          у тебя в голове.</p>
      </div>
    </div>


  </div>
</section>
<section class="about">
  <div class="container">
    <div class="about__wrapper">
      <div class="about__text about-second">
        <p class="base-text">Меня зовут Андрей Андреевич Изосимов, я из города Санкт-Петербург, сейчас живу на два
          города Санкт-Петербург - Псков. Уже более 5 лет я в теме веб-разработке, более 3 лет активной работы с
          сайтами. И более 2 лет являюсь наставником, преподавателем по созданию веб-сайтов. Из самых успешных студентов
          я набирал себе команду, объеденные общим делом и идеей, мы нашли связи и набрали других специалистов, которые
          необходимы для полноценной реализации каждой услуги.
        </p>
        <ol class="about__advantages">
          <li>Помог вырасти более 300+ студентам за 2 года в ШТМЛ Академии</li>
          <li>Был спикером онлайн на аудиторию 150+ студентов </li>
          <li>свыше 30+ проектов различного уровня сложности.</li>
        </ol>
        <div class="blockquote">
          <blockquote cite="https://vk.com/andrey_andreevich_official">
            <p class="base-text">

              "Моя цель помочь Вам улучшить свой уровень жизни. Сайт это инструмент, который при
              грамотной настройки будет приносить Вам деньги, репутацию, прирост клиентской базы."
            </p>
          </blockquote>
          <p class="base-text">
            <cite>
              (c) Андрей Андреевич
            </cite>
          </p>
        </div>
      </div>

      <img src="<?php echo $imagePath . 'avatar-2.avif' ?>" alt="аватар Изосимова Андрея Андреевича" width="500"
        height="500">

    </div>

  </div>
</section>