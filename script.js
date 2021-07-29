window.addEventListener('load', (event) => {
    fetchData();
});

async function fetchData(params = null) {
    try {
        let url = `http://localhost/api_test/api/area/get_areas.php`;
        if (params != null) { url += params };
        const res = await fetch(url);
        const api = await res.json();
        flashMessage("Fetching Data");
        const keys = Object.keys(api.data[0]);
        const data = api.data;
        setTable(keys, data);
        setPagination(api);

        const reload_button = document.querySelector('#reload-btn');
        const reload_text = document.querySelector('#reload');
        const formData = new FormData();
        formData.append('rows', api.meta.total_rows);
        reload_text.innerText = '';
        reload_button.classList.remove("show");

        // TODO: Fix This
        clearInterval(check_db);
        const check_db = await setInterval(() => {
            fetch('http://localhost/api_test/api/area/check_updates.php', { method: 'POST', body: formData})
            .then(res => {
                if (res.ok) {
                    return res.json();
                } else {
                    console.log('Failed to check for updates');
                }
            })
            .then(check => {
                console.log(check);
                if (check.status == true) {
                    reload_text.innerText = 'Database Updated. Click To Reload Page';
                    reload_button.classList.add("show");
                    reload_button.setAttribute('onclick', 'fetchData()');
                    clearInterval(check_db);
                }
            })
            .catch(err => {
                clearInterval(check_db);
                console.log(err);
            });
        }, 3000);
    } catch (err) {
        console.log(err);
    }
}

function flashMessage(text) {
    const snackbar = document.querySelector('#snackbar');
    snackbar.innerText = text;
    snackbar.classList.add('show');
    setTimeout(() => {
        snackbar.classList.remove('show');
    }, 1100);
}

function setTable(keys, data) {
    const table_header = document.querySelector('#table-header');
    const table_body = document.querySelector('#table-body');

    //Fill Header
    table_header.innerHTML = '';
    keys.forEach(key => {
        table_header.innerHTML += `<th>${key}</th>`;
    });

    // Fill Data
    table_body.innerHTML = '';
    data.forEach(row => {
        let rows = '';
        rows += '<tr>';
        for (let index = 0; index < keys.length; index++) {
            rows += `<td>${row[keys[index]]}</td>`;
        }
        rows += '</tr>';
        table_body.innerHTML += rows;
    });
}

function setPagination(api) {
    const first_page = document.querySelector('#first-page');
    const prev_page = document.querySelector('#prev-page');
    const next_page = document.querySelector('#next-page');
    const last_page = document.querySelector('#last-page');
    const current_page = document.querySelector('#current-page');
    const page_counter = document.querySelector('#page-count');
    const total_counter = document.querySelector('#total-count');
    const jump_button = document.querySelector('#jump-page');

    let page_current = parseInt(api.meta.current_page);
    let page_total = parseInt(api.meta.total_pages);
    let fetched_rows = parseInt(api.meta.rows);
    let total_rows = parseInt(api.meta.total_rows);
    let url = api.meta.url;
    let input_page = '';

    current_page.value = page_current;
    // current_page.setAttribute('value', page_current);
    current_page.setAttribute('placeholder', page_current);
    page_counter.innerText = `of ${page_total}`;
    total_counter.innerText = `Showing ${(fetched_rows * page_current) - fetched_rows + 1} - ${(fetched_rows * page_current)} out of ${total_rows}`;

    if (page_current == 1) {
        prev_page.disabled = true;
        first_page.disabled = true;
    }
    else
    {
        prev_page.disabled = false;
        first_page.disabled = false;
    }

    if (page_current == page_total) {
        next_page.disabled = true;
        last_page.disabled = true;
    }
    else
    {
        next_page.disabled = false;
        last_page.disabled = false;
    }

    console.log(input_page);
    current_page.addEventListener('input', (e) => {
        input_page = e.data
        jump_button.setAttribute('onclick', `fetchData('?page=${input_page}')`);
    });
    first_page.setAttribute('onclick', `fetchData('?page=1')`);
    prev_page.setAttribute('onclick', `fetchData('?page=${page_current - 1}')`);
    next_page.setAttribute('onclick', `fetchData('?page=${page_current + 1}')`);
    last_page.setAttribute('onclick', `fetchData('?page=${page_total}')`);
}