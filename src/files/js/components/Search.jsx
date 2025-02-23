import { h, render } from "preact";
import { useState, useEffect, useRef } from "preact/hooks";

export function SetWork() {
    const [query, setQuery] = useState('');
    const [data, setData] = useState([]);
    const [filteredData, setFilteredData] = useState([]);
    const searchInputRef = useRef(null);
    const resultsListRef = useRef(null);

    useEffect(() => {
        searchInputRef.current = document.getElementById('search-input');

        if (!searchInputRef.current) {
            console.error("Элемент #search-input не найден!");
            return;
        }

        resultsListRef.current = document.createElement('ul');
        resultsListRef.current.style.position = 'absolute';
        resultsListRef.current.style.border = '1px solid #ccc';
        resultsListRef.current.style.borderRadius = '10px';
        resultsListRef.current.style.width = '100%';
        resultsListRef.current.style.top = '100%';
        resultsListRef.current.style.left = '0';
        resultsListRef.current.style.right = '0';

        resultsListRef.current.style.zIndex = '1000';
        resultsListRef.current.style.backgroundColor = 'black';
        resultsListRef.current.style.listStyle = 'none';
        resultsListRef.current.style.margin = '0';
        resultsListRef.current.style.padding = '0';
        resultsListRef.current.style.color = 'white';
        resultsListRef.current.style.display = 'none';

        resultsListRef.current.style.overflowY = 'auto';
        resultsListRef.current.style.maxHeight = '300px';
        resultsListRef.current.style.visibility = 'hidden';

        searchInputRef.current.parentElement.appendChild(resultsListRef.current);

        const inputWork = (e) => {
            const value = e.target.value.toLowerCase();
            setQuery(value);

            if (value.length >= 3) {
                const filtered = data.filter((item) =>
                    Object.values(item).some(val =>
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
        fetch('/dist/files/php/data/products.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ data: 'true' }),
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
                    ...products.category['park-systems']
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
            ul::-webkit-scrollbar {
                width: 8px;
            }
            ul::-webkit-scrollbar-track {
                background: #222;
                border-radius: 10px;
            }
            ul::-webkit-scrollbar-thumb {
                background: red;
                border-radius: 10px;
            }
            ul::-webkit-scrollbar-thumb:hover {
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
