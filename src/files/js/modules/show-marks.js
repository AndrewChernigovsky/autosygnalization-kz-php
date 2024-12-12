const marksList = document.getElementById('marks-list');

export function showMarks() {
    const clonedList = marksList.cloneNode(true);
    marksList.appendChild(clonedList);

    marksList.classList.add('scrolling');
}
