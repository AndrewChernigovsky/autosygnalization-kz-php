import { h, render } from 'preact';
import { useState, useEffect, useRef } from 'preact/hooks';

export function SetWork() {
  const [query, setQuery] = useState('');
  const [data, setData] = useState([]);
  const [filteredData, setFilteredData] = useState([]);
  const searchInputRef = useRef(null);
  const resultsListRef = useRef(null);

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
      console.error('Элемент #search-input не найден!');
      return;
    }

    resultsListRef.current = document.createElement('ul');
    resultsListRef.current.classList.add('search-menu');

    Object.assign(resultsListRef.current.style, styles);

    searchInputRef.current.parentElement.appendChild(resultsListRef.current);

    const inputWork = (e) => {
      const value = e.target.value.toLowerCase();
      setQuery(value);

      if (value.length >= 3) {
        const filtered = data.filter((item) =>
          Object.values(item).some(
            (val) =>
              typeof val === 'string' && val.toLowerCase().includes(value)
          )
        );
        setFilteredData(filtered);
      } else {
        setFilteredData([]);
      }

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
      if (resultsListRef.current) {
        resultsListRef.current.remove();
      }
    };
  }, [data]);

  useEffect(() => {
    fetch('/server/php/api/products/get_all_products.php', {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    })
      .then((response) => {
        if (!response.ok) throw new Error(`Ошибка HTTP: ${response.status}`);
        return response.json();
      })
      .then((products) => {
        if (products.category) {
          const allProducts = [
            ...products.category.keychain,
            ...products.category['remote-controls'],
            ...products.category['park-systems'],
          ];
          setData(allProducts);
        } else {
          console.error('Ошибка: Неверная структура данных', products);
        }
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

// export function SetWork() {
//   const [data, setData] = useState([]);
//   const [query, setQuery] = useState('');
//   const [requaredData, setRequaredData] = useState([]);

//   const searchRef = useRef(null);
//   const requaredListRef = useRef(null);

//   // 🚀 Загружаем данные при монтировании
//   useEffect(() => {
//     fetch('/server/php/data/products.php', {
//       method: 'POST',
//       headers: { 'Content-Type': 'application/json' },
//       body: JSON.stringify({ data: 'true' }),
//     })
//       .then((response) => response.json())
//       .then((products) => {
//         if (products.category) {
//           const allProducts = [
//             ...products.category.keychain,
//             ...products.category['remote-controls'],
//             ...products.category['park-systems'],
//           ];
//           setData(allProducts);
//         }
//       })
//       .catch((error) => console.error('Ошибка:', error));
//   }, []);

//   // 🚀 Инициализация поискового поля и его событий
//   useEffect(() => {
//     searchRef.current = document.getElementById('search-input');

//     if (!searchRef.current) {
//       console.error('Элемент #search-input не найден!');
//       return;
//     }

//     // 🔥 Создаём выпадающий список
//     requaredListRef.current = document.createElement('ul');
//     Object.assign(requaredListRef.current.style, {
//       position: 'absolute',
//       border: '1px solid #ccc',
//       borderRadius: '10px',
//       width: '100%',
//       top: '100%',
//       left: '0',
//       zIndex: '1000',
//       backgroundColor: 'black',
//       listStyle: 'none',
//       margin: '0',
//       padding: '0',
//       color: 'white',
//       display: 'none',
//       overflowY: 'auto',
//       maxHeight: '300px',
//       visibility: 'hidden',
//     });

//     searchRef.current.parentElement.appendChild(requaredListRef.current);

//     // 🚀 Событие ввода текста
//     const handleInput = (e) => {
//       setQuery(e.target.value.toLowerCase());
//     };

//     searchRef.current.addEventListener('input', handleInput);

//     return () => {
//       searchRef.current.removeEventListener('input', handleInput);
//       requaredListRef.current.remove();
//       requaredListRef.current = null;
//     };
//   }, []);

//   // 🚀 Фильтрация данных при изменении `query`
//   useEffect(() => {
//     if (query.length >= 3) {
//       const filtered = data.filter((item) =>
//         Object.values(item).some(
//           (val) => typeof val === 'string' && val.toLowerCase().includes(query)
//         )
//       );
//       setRequaredData(filtered);
//     } else {
//       setRequaredData([]);
//     }
//   }, [query, data]);

//   // 🚀 Показываем список при фокусе, скрываем при blur
//   useEffect(() => {
//     const handleFocus = () => {
//       if (query.length >= 3) {
//         requaredListRef.current.style.display = 'block';
//         requaredListRef.current.style.visibility = 'visible';
//       }
//     };

//     const handleBlur = (event) => {
//       if (!searchRef.current.contains(event.relatedTarget)) {
//         requaredListRef.current.style.display = 'none';
//         requaredListRef.current.style.visibility = 'hidden';
//       }
//     };

//     if (searchRef.current) {
//       searchRef.current.addEventListener('focus', handleFocus);
//       searchRef.current.addEventListener('blur', handleBlur, true);
//     }

//     // 🚀 Если инпут уже активен при изменении `query`, вызываем handleFocus
//     if (document.activeElement === searchRef.current) {
//       handleFocus();
//     }

//     return () => {
//       if (searchRef.current) {
//         searchRef.current.removeEventListener('focus', handleFocus);
//         searchRef.current.removeEventListener('blur', handleBlur, true);
//       }
//     };
//   }, [query]);

//   // 🚀 Обновляем список элементов при изменении `requaredData`
//   useEffect(() => {
//     if (!requaredListRef.current) return;

//     requaredListRef.current.innerHTML = '';

//     if (query.length >= 3) {
//       if (requaredData.length === 0) {
//         const li = document.createElement('li');
//         li.style.padding = '8px';
//         li.style.textAlign = 'center';
//         li.style.color = 'white';
//         li.textContent = 'Нет совпадений';
//         requaredListRef.current.appendChild(li);
//       } else {
//         requaredData.forEach((item) => {
//           const li = document.createElement('li');
//           li.style.padding = '8px';
//           li.style.borderBottom = '1px solid #eee';

//           const link = document.createElement('a');
//           link.href = item.link;
//           link.textContent = item.title;
//           Object.assign(link.style, {
//             textDecoration: 'none',
//             color: 'white',
//             display: 'block',
//             width: '100%',
//             padding: '8px',
//             transition: 'color 0.3s ease-in-out',
//           });

//           link.addEventListener('mouseenter', () => {
//             link.style.color = 'red';
//           });

//           link.addEventListener('mouseleave', () => {
//             link.style.color = 'white';
//           });

//           li.appendChild(link);
//           requaredListRef.current.appendChild(li);
//         });
//       }
//     }
//   }, [requaredData, query]);

//   return null;
// }

export function mountSetWork(elementId) {
  render(h(SetWork), document.getElementById(elementId));
}
