import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const table = document.querySelector('.table-container table');
    if (!table) return;

    const headers = table.querySelectorAll('th');
    const rows = table.querySelectorAll('tbody tr');

    headers.forEach(header => {
        header.addEventListener('click', () => {
            const index = Array.prototype.indexOf.call(headers, header);
            const sortedRows = Array.from(rows).sort((a, b) => {
                const aText = a.children[index]?.textContent.trim() || '';
                const bText = b.children[index]?.textContent.trim() || '';
                return aText.localeCompare(bText);
            });

            const tbody = table.querySelector('tbody');
            if (!tbody) return;

            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }

            sortedRows.forEach(row => tbody.appendChild(row));
        });
    });

    rows.forEach(row => {
        row.addEventListener('click', () => {
            const popup = document.querySelector('.popup');
            const overlay = document.querySelector('.popup-overlay');
            if (!popup || !overlay) return;

            const details = Array.from(row.children).map(cell => cell.textContent.trim());

            let popupContent = '';
            const headerText = document.querySelector('.header')?.textContent || '';

            console.log(headerText);
            console.log(details);

            if (headerText.includes('Latest 50 New Releases')) {
                popupContent = `
                    <p><strong>Artist:</strong> ${details[0]}</p>
                    <p><strong>Title:</strong> ${details[1]}</p>
                    <p><strong>Release Date:</strong> ${details[2]}</p>
                    <p><strong>Genre:</strong> ${details[3]}</p>
                    <p><strong>Label:</strong> ${details[4]}</p>
                    <p><strong>Email:</strong> ${details[5]}</p>
                    <p><strong>Instagram:</strong> ${details[6]}</p>
                    <p><strong>Facebook:</strong> ${details[7]}</p>
                    <p><strong>Website:</strong> ${details[8]}</p>
                    <p><strong>YouTube:</strong> ${details[9]}</p>
                `;
                document.querySelector('.popup-header').textContent = details[1] || '';
            } else if (headerText.includes('Artists')) {
                popupContent = `
                    <p><strong>Name:</strong> ${details[0]}</p>
                    <p><strong>Email:</strong> ${details[1]}</p>
                    <p><strong>Instagram:</strong> ${details[2]}</p>
                    <p><strong>Facebook:</strong> ${details[3]}</p>
                    <p><strong>Website:</strong> ${details[4]}</p>
                    <p><strong>YouTube:</strong> ${details[5]}</p>
                `;
                document.querySelector('.popup-header').textContent = details[0] || '';
            }

            console.log(popupContent)

            document.querySelector('.popup-body').innerHTML = popupContent;

            popup.style.display = 'block';
            overlay.style.display = 'block';
        });
    });

    document.querySelector('.popup-close')?.addEventListener('click', () => {
        document.querySelector('.popup').style.display = 'none';
        document.querySelector('.popup-overlay').style.display = 'none';
    });

    document.querySelector('.popup-overlay')?.addEventListener('click', () => {
        document.querySelector('.popup').style.display = 'none';
        document.querySelector('.popup-overlay').style.display = 'none';
    });
});
