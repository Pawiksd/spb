import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const table = document.querySelector('.table-container table');
    const headers = table.querySelectorAll('th');
    const rows = table.querySelectorAll('tbody tr');

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const index = Array.prototype.indexOf.call(headers, header);
            const sortedRows = Array.from(rows).sort((a, b) => {
                const aText = a.children[index].textContent.trim();
                const bText = b.children[index].textContent.trim();
                return aText.localeCompare(bText);
            });

            while (table.querySelector('tbody').firstChild) {
                table.querySelector('tbody').removeChild(table.querySelector('tbody').firstChild);
            }

            sortedRows.forEach(row => table.querySelector('tbody').appendChild(row));
        });
    });

    rows.forEach(row => {
        row.addEventListener('click', () => {
            const popup = document.querySelector('.popup');
            const overlay = document.querySelector('.popup-overlay');
            const details = Array.from(row.children).map(cell => cell.textContent.trim());

            document.querySelector('.popup-header').textContent = details[1]; // Title
            document.querySelector('.popup-body').innerHTML = `
                <p><strong>Artist:</strong> ${details[0]}</p>
                <p><strong>Release Date:</strong> ${details[2]}</p>
                <p><strong>Genre:</strong> ${details[3]}</p>
                <p><strong>Label:</strong> ${details[4]}</p>
                <p><strong>Email:</strong> ${details[5]}</p>
                <p><strong>Instagram:</strong> ${details[6]}</p>
                <p><strong>Facebook:</strong> ${details[7]}</p>
                <p><strong>Website:</strong> ${details[8]}</p>
                <p><strong>YouTube:</strong> ${details[9]}</p>
            `;

            popup.style.display = 'block';
            overlay.style.display = 'block';
        });
    });

    document.querySelector('.popup-close').addEventListener('click', () => {
        document.querySelector('.popup').style.display = 'none';
        document.querySelector('.popup-overlay').style.display = 'none';
    });

    document.querySelector('.popup-overlay').addEventListener('click', () => {
        document.querySelector('.popup').style.display = 'none';
        document.querySelector('.popup-overlay').style.display = 'none';
    });
});
