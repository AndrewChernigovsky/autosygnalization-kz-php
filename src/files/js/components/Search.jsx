import { h, render } from "preact";
import { useState, useEffect } from "preact/hooks";
import htm from "htm";

const html = htm.bind(h);

export function GetData() {
    const [data, setData] = useState([]);

    useEffect(() => {
        fetch('/dist/files/php/data/products.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ data: 'true' }),
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Ошибка HTTP: ${response.status}`);
            }
            return response.text();
        })
        .then((text) => {
            return JSON.parse(text);
        })
        .then((products) => {
            if (products.category) {
                setData(prevData => [
                    ...prevData,
                    ...products.category.keychain,
                    ...products.category['remote-controls'],
                    ...products.category['park-systems']
                ]);
            } else {
                console.error('Ошибка: Неверная структура данных', products);
            }
        })
        .catch((error) => console.error('Ошибка:', error));
    }, []);

    return html`
        <div>
            <h2>Список товаров</h2>
            ${data.length > 0 
                ? data.map((item) => html`<div key=${item.id}>${item.title}</div>`) // Исправлено: item.name → item.title
                : html`<p>Загрузка...</p>`
            }
        </div>
    `;
}

// Функция для вставки компонента
export function mountGetData(elementId) {
    render(h(GetData), document.getElementById(elementId));
}
