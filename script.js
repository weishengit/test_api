const API_URL = 'http://localhost/api_test/api/area/get_areas.php';
const FLASH_MESSAGE = document.querySelector('#flash');
const Pagination = document.querySelector('#pagination');
const table = document.querySelector('#table-body');



function fetchData (url) {
    fetch(url)
        .then(res => {
            if (res.ok) {
                FLASH_MESSAGE.innerText = 'Data Successfully Fetched';
                return res.json();
            }
            else {
                FLASH_MESSAGE.innerText = 'Error Fetching Data';
            }
        })
        .then(data => {
            links = `
                <button id="first-page"> << </button>
                <button id="prev-page"> Prev </button>
                <button> ${data.meta.current_page} </button>
                <button id="next-page"> Next </button>
                <button id="last-page"> >> </button>
            `;
            pagination.innerHTML = links;

            rows = '';
            data.data.forEach(row => {
                rows += `
                    <tr>
                        <td>${row.id}</td>
                        <td>${row.title}</td>
                        <td>${row.created_at}</td>
                        <td>${row.updated_at}</td>
                    </tr>
                `;
            });

            table.innerHTML = rows;

            const first_page = document.querySelector('#first-page');
            const prev_page = document.querySelector('#prev-page');
            const next_page = document.querySelector('#next-page');
            const last_page = document.querySelector('#last-page');

            if (data.meta.current_page == 1) {
                prev_page.disabled = true;
                first_page.disabled =true
            }

            if (data.meta.current_page == data.meta.total_pages) {
                next_page.disabled = true;
                last_page.disabled = true;
            }

            first_page.addEventListener('click', e => {
                fetchData(data.links.first)
            });

            prev_page.addEventListener('click', e => {
                fetchData(data.links.prev)
            });

            next_page.addEventListener('click', e => {
                fetchData(data.links.next)
            });

            last_page.addEventListener('click', e => {
                fetchData(data.links.last)
            });
        })
        .catch(error => {
            FLASH_MESSAGE.innerText = `Fetch Error: ${error}`;
        });
}

fetchData(API_URL);