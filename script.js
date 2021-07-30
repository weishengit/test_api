let url_string = window.location.href;
let url = new URL(url_string);
let page = url.searchParams.get("page");
let page_num = "";

if(page != null){
    page_num = "?page="+page;
    history.pushState({page},'',window.location)
}

const API_URL = 'http://localhost/api_test/api/area/get_areas.php'+page_num;
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
                const url = new URL(window.location);
                let page_num = parseInt(api.meta.current_page) + 1;
                url.searchParams.set('page', page_num);
                window.history.pushState({page_num}, '', url);
            });

            prev_page.addEventListener('click', e => {
                fetchData(data.links.prev)
                const url = new URL(window.location);
                let page_num = parseInt(api.meta.current_page) + 1;
                url.searchParams.set('page', page_num);
                window.history.pushState({page_num}, '', url);
            });

            next_page.addEventListener('click', e => {
                fetchData(data.links.next)
                const url = new URL(window.location);
                let page_num = parseInt(api.meta.current_page) + 1;
                url.searchParams.set('page', page_num);
                window.history.pushState({page_num}, '', url);
            });

            last_page.addEventListener('click', e => {
                fetchData(data.links.last)
                const url = new URL(window.location);
                let page_num = parseInt(api.meta.current_page) + 1;
                url.searchParams.set('page', page_num);
                window.history.pushState({page_num}, '', url);
            });
        })
        .catch(error => {
            FLASH_MESSAGE.innerText = `Fetch Error: ${error}`;
        });
}

fetchData(API_URL);

window.addEventListener('popstate', evt => {
    fetchData(API_URL);
})