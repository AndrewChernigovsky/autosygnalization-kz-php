import { h, render } from 'preact';
import { useState, useEffect, useRef } from 'preact/hooks';

export function SetWork() {
  const [query, setQuery] = useState('');
  const [data, setData] = useState([]);
  const [filteredData, setFilteredData] = useState([]);
  const searchInputRef = useRef(null);
  const resultsListRef = useRef(null);
  const debounceTimeoutRef = useRef(null);

  // Таблицы транслитерации
  const cyrillicToLatin = {
    'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh', 'з': 'z',
    'и': 'i', 'й': 'i', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o', 'п': 'p', 'р': 'r',
    'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'ts', 'ч': 'ch', 'ш': 'sh', 'щ': 'sch',
    'ы': 'y', 'э': 'e', 'ю': 'yu', 'я': 'ya', 'ь': '', 'ъ': '', 'ie': 'aй'
  };

  const latinToCyrillic = {
    'a': 'а', 'b': 'б', 'v': 'в', 'g': 'г', 'd': 'д', 'e': 'е', 'yo': 'ё', 'zh': 'ж', 'z': 'з',
    'i': 'и', 'y': 'й', 'k': 'к', 'l': 'л', 'm': 'м', 'n': 'н', 'o': 'о', 'p': 'п', 'r': 'р',
    's': 'с', 't': 'т', 'u': 'у', 'f': 'ф', 'h': 'х', 'ts': 'ц', 'ch': 'ч', 'sh': 'ш', 'sch': 'щ',
    'yu': 'ю', 'ya': 'я', 'ie': 'ай', 'c': 'ц'
  };

  // Специальные правила для брендов и популярных слов
  const specialTransliterations = {
    'старлайн': 'starline',
    'старлаин': 'starline',
    'starlain': 'starline',
    'сигнализация': 'signalizatsiya',
    'автосигнализация': 'avtosignalizatsiya',
    'видеорегистратор': 'videoregistrator',
    'пульт': 'remote',
    'пульты': 'remotes',
    'pult': 'пульт',
    'pults': 'пульты',
    'аксессуары': 'accessories',
    'аксессуар': 'accessory',
    'bluetooth': 'блютуз',
    'keychain': 'брелок',
    'camera': 'камера',
    'security': 'секьюрити',
    'блютуз': 'bluetooth',
    'брелок': 'keychain',
    'камера': 'camera',
    'секьюрити': 'security'
  };

  // Функция транслитерации кириллицы в латиницу
  const transliterateCyrillicToLatin = (text) => {
    const lowerText = text.toLowerCase();

    // Проверяем специальные правила сначала
    for (const [cyrillic, latin] of Object.entries(specialTransliterations)) {
      if (lowerText.includes(cyrillic)) {
        return lowerText.replace(new RegExp(cyrillic, 'g'), latin);
      }
    }

    // Обычная транслитерация с улучшенными правилами
    return lowerText
      .replace(/айн/g, 'ine')  // старлайн → starline
      .replace(/ай/g, 'ai')     // байк → bike
      .replace(/ей/g, 'ey')     // грей → grey
      .split('').map(char => cyrillicToLatin[char] || char).join('');
  };

  // Функция транслитерации латиницы в кириллицу
  const transliterateLatinToCyrillic = (text) => {
    const lowerText = text.toLowerCase();

    // Проверяем обратные специальные правила
    for (const [cyrillic, latin] of Object.entries(specialTransliterations)) {
      if (lowerText.includes(latin)) {
        return lowerText.replace(new RegExp(latin, 'g'), cyrillic);
      }
    }

    // Обычная транслитерация с улучшенными правилами
    let result = lowerText
      .replace(/starline/g, 'старлайн')  // прямое правило для StarLine
      .replace(/line/g, 'лайн')          // line → лайн
      .replace(/ine/g, 'айн')            // ine → айн
      .replace(/ai/g, 'ай')              // ai → ай
      .replace(/ey/g, 'ей');             // ey → ей

    // Сначала заменяем длинные комбинации, потом короткие
    const sortedKeys = Object.keys(latinToCyrillic).sort((a, b) => b.length - a.length);
    sortedKeys.forEach(key => {
      result = result.replace(new RegExp(key, 'g'), latinToCyrillic[key]);
    });

    return result;
  };

  // Функция проверки соответствия с учетом транслитерации
  const matchesWithTransliteration = (text, searchValue) => {
    const lowerText = text.toLowerCase();
    const lowerSearch = searchValue.toLowerCase();

    // Функция проверки частичного совпадения (в начале слов)
    const checkPartialMatch = (textToCheck, searchToCheck) => {
      // Проверяем точное вхождение
      if (textToCheck.includes(searchToCheck)) return true;

      // Проверяем совпадение в начале слов
      const words = textToCheck.split(/\s+/);
      return words.some(word => word.startsWith(searchToCheck));
    };

    // Прямое совпадение
    if (checkPartialMatch(lowerText, lowerSearch)) {
      return true;
    }

    // Транслитерация поискового запроса кириллица -> латиница
    const searchLatin = transliterateCyrillicToLatin(lowerSearch);
    if (checkPartialMatch(lowerText, searchLatin)) {
      return true;
    }

    // Транслитерация поискового запроса латиница -> кириллица
    const searchCyrillic = transliterateLatinToCyrillic(lowerSearch);
    if (checkPartialMatch(lowerText, searchCyrillic)) {
      return true;
    }

    // Транслитерация текста кириллица -> латиница
    const textLatin = transliterateCyrillicToLatin(lowerText);
    if (checkPartialMatch(textLatin, lowerSearch)) {
      return true;
    }

    // Транслитерация текста латиница -> кириллица
    const textCyrillic = transliterateLatinToCyrillic(lowerText);
    if (checkPartialMatch(textCyrillic, lowerSearch)) {
      return true;
    }

    // Дополнительная проверка: кросс-транслитерация
    if (checkPartialMatch(textLatin, searchLatin)) {
      return true;
    }

    if (checkPartialMatch(textCyrillic, searchCyrillic)) {
      return true;
    }

    return false;
  };

  const styles = {
    position: 'absolute',
    border: '1px solid #ccc',
    borderRadius: '10px',
    width: '100%',
    top: '100%',
    left: 0,
    right: 0,
    zIndex: 1000,
    backgroundColor: 'black',
    listStyle: 'none',
    margin: 0,
    padding: 0,
    color: 'white',
    display: 'none',
    overflowY: 'auto',
    maxHeight: '300px',
    visibility: 'hidden',
  };

  useEffect(() => {
    searchInputRef.current = document.getElementById('search-input');

    if (!searchInputRef.current) {
      return;
    }

    resultsListRef.current = document.createElement('ul');
    resultsListRef.current.classList.add('search-menu');

    Object.assign(resultsListRef.current.style, styles);

    searchInputRef.current.parentElement.appendChild(resultsListRef.current);

    // Функция поиска с debounce
    const performSearch = (value) => {
      if (value.length >= 3) {
        const filtered = data.map((item) => {
          // Подсчитаем релевантность (приоритет совпадения)
          let relevanceScore = 0;
          let hasMatch = false;

          Object.values(item).forEach((val) => {
            if (typeof val === 'string') {
              const lowerVal = val.toLowerCase();
              const lowerSearch = value.toLowerCase();

              // Точное совпадение в названии - максимальный приоритет
              if ((item.title || item.name || '').toLowerCase().includes(lowerSearch)) {
                relevanceScore += 100;
                hasMatch = true;
              }

              // Совпадение в начале слова - высокий приоритет  
              if (lowerVal.startsWith(lowerSearch)) {
                relevanceScore += 50;
                hasMatch = true;
              }

              // Обычные совпадения через транслитерацию
              if (matchesWithTransliteration(val, value)) {
                relevanceScore += 10;
                hasMatch = true;
              }
            }
          });

          return hasMatch ? { ...item, relevanceScore } : null;
        }).filter(Boolean);

        // Сортируем по релевантности (сначала самые релевантные) и ограничиваем результаты
        const sortedFiltered = filtered
          .sort((a, b) => b.relevanceScore - a.relevanceScore)
          .slice(0, 100); // Показываем только топ-100 результатов

        setFilteredData(sortedFiltered);
        resultsListRef.current.style.display = 'block';
        resultsListRef.current.style.visibility = 'visible';
      } else {
        setFilteredData([]);
        resultsListRef.current.style.display = 'none';
      }
    };

    const inputWork = (e) => {
      const value = e.target.value.toLowerCase();
      setQuery(value);

      // Отменяем предыдущий таймер debounce
      if (debounceTimeoutRef.current) {
        clearTimeout(debounceTimeoutRef.current);
      }

      // Устанавливаем новый таймер с задержкой 300ms
      debounceTimeoutRef.current = setTimeout(() => {
        performSearch(value);
      }, 300);

      // Показываем результаты немедленно для UX (без задержки скрытия)
      resultsListRef.current.style.display = 'block';
      resultsListRef.current.style.visibility = 'visible';
    };

    const handleClickOutside = (e) => {
      if (
        searchInputRef.current &&
        resultsListRef.current &&
        !searchInputRef.current.contains(e.target) &&
        !resultsListRef.current.contains(e.target)
      ) {
        resultsListRef.current.style.display = 'none';
      }
    };

    searchInputRef.current.addEventListener('input', inputWork);
    document.addEventListener('click', handleClickOutside);

    return () => {
      searchInputRef.current.removeEventListener('input', inputWork);
      document.removeEventListener('click', handleClickOutside);

      // Очищаем таймер debounce при размонтировании
      if (debounceTimeoutRef.current) {
        clearTimeout(debounceTimeoutRef.current);
      }

      if (resultsListRef.current) {
        resultsListRef.current.remove();
      }
    };
  }, [data]);

  useEffect(() => {
    let productsURL = '/server/php/api/products/get_all_products.php';
    let servicesURL = '/server/php/api/services/get_all_services.php';

    Promise.all([
      fetch(productsURL),
      fetch(servicesURL),
    ])
      .then(([productsResponse, servicesResponse]) => {
        if (!productsResponse.ok || !servicesResponse.ok) {
          throw new Error('Ошибка HTTP: ' + productsResponse.status + ' ' + servicesResponse.status);
        }
        return Promise.all([productsResponse.json(), servicesResponse.json()]);
      })
      .then(([productsData, servicesData]) => {
        // Нормализуем данные товаров
        let normalizedProducts = [];
        if (productsData.category) {
          normalizedProducts = [
            ...productsData.category.keychain,
            ...productsData.category['remote-controls'],
            ...productsData.category['park-systems'],
          ];
        }

        // Нормализуем данные услуг - добавляем title и link для совместимости
        const normalizedServices = Object.values(servicesData).map(service => ({
          ...service,
          title: service.name, // Используем name как title для поиска
          link: service.href,  // Используем href как link для навигации
        }));

        // Объединяем нормализованные данные
        setData([...normalizedProducts, ...normalizedServices]);
      })
      .catch((error) => console.error('Ошибка:', error));
  }, []);

  useEffect(() => {
    if (!resultsListRef.current) return;
    resultsListRef.current.innerHTML = '';

    if (query.length < 3) {
      resultsListRef.current.style.display = 'none';
      return;
    }

    if (filteredData.length === 0) {
      const li = document.createElement('li');
      li.style.padding = '8px';
      li.style.textAlign = 'center';
      li.style.color = 'white';
      li.textContent = 'Нет совпадений';
      resultsListRef.current.appendChild(li);
      resultsListRef.current.style.display = 'block';
      resultsListRef.current.style.visibility = 'visible';
      return;
    }

    filteredData.forEach((item) => {
      const li = document.createElement('li');
      li.style.padding = '8px';
      li.style.borderBottom = '1px solid #eee';

      const link = document.createElement('a');
      link.href = item.link;
      link.textContent = item.title;
      link.style.textDecoration = 'none';
      link.style.color = 'white';
      link.style.display = 'block';
      link.style.width = '100%';
      link.style.padding = '8px';
      link.style.transition = 'color 0.3s ease-in-out';

      link.addEventListener('mouseenter', () => {
        link.style.color = 'red';
      });

      link.addEventListener('mouseleave', () => {
        link.style.color = 'white';
      });

      li.appendChild(link);
      resultsListRef.current.appendChild(li);
    });

    resultsListRef.current.style.display = 'block';
    resultsListRef.current.style.visibility = 'visible';

    // Добавляем стилизацию скроллбара через CSS
    const style = document.createElement('style');
    style.innerHTML = `
            ul.search-menu::-webkit-scrollbar {
                width: 8px;
            }
            ul.search-menu::-webkit-scrollbar-track {
                background: #222;
                border-radius: 10px;
            }
            ul.search-menu::-webkit-scrollbar-thumb {
                background: red;
                border-radius: 10px;
            }
            u.search-menul::-webkit-scrollbar-thumb:hover {
                background: darkred;
            }
        `;
    document.head.appendChild(style);
  }, [filteredData, query]);

  return null;
}

export function mountSetWork(elementId) {
  render(h(SetWork), document.getElementById(elementId));
}
