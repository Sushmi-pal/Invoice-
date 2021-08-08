let loc = window.location.href
let name = ((loc.split('&')[0]).split('=')[1]).toUpperCase()
let CommonName = localStorage.getItem("CommonName")

function SearchByCname(column_name, order) {

    fetch(CommonName + "invoicepages?cname=" + name + "&sort=" + column_name + "&order=" + order).then(res => res.json()).then(data => {
        var id = localStorage.getItem("number")
        let tbody = document.getElementById("invoice_details_table")
        let op = "<tr>"
        let SearchIid = []
        data.data.forEach((data, index) => {
            console.log(data['cname'])
            SearchIid.push(data['iid'])
            op += `<th scope="row">${index + 1}</th>`
            op += `<td>InvoiceItem${data['iid']}</td>`
            op += `<td>${data['cname']}</td>`
            op += `<td><a class="fa fa-eye" href="preview.html#${data['iid']}" style="color: black;" ></a> </td>`
            op += `<td><a class="fa fa-edit" href="update.html#${data['iid']}" style="color: black;"></a> </td>`
            op += `<td><a class="fa fa-trash" onclick="DelInvoice(${data['iid']})" style="color: black"></a> </td>`
            op += "<tr>";
            tbody.innerHTML = op;
            window.search_iid = SearchIid
            localStorage.setItem("SearchInvoiceID", SearchIid.toString())
        })
    }).catch(err => console.log(err))
}


function NewPage(id) {
    let Starting_Inv_ID = (id - 1) * 5
    let storedId = window.search_iid;
    console.log(storedId)
    let column_name = localStorage.getItem('column_name')
    let order = localStorage.getItem('order')
    fetch(CommonName + "invoicepages?offset=" + storedId[Starting_Inv_ID] + "&limit=5&sort=" + column_name + "&order=" + order).then(res => res.json()).then(data => {
        let tbody = document.getElementById("invoice_details_table")
        localStorage.setItem("total length1", data.data.length)
        let op = "<tr>"
        data.data.forEach((data, index) => {
            op += `<th scope="row">${index + 1}</th>`
            op += `<td>InvoiceItem${data['id']}</td>`
            op += `<td>${data['cname']}</td>`
            op += `<td><a class="fa fa-eye" href="preview.html#${data['id']}" style="color: black;" ></a> </td>`
            op += `<td><a class="fa fa-edit" href="invoice1.html.html#${data['id']}" style="color: black;" ></a> </td>`
            op += `<td><a class="fa fa-trash" onclick="DelInvoice(${data['id']})" style="color: black;" ></a> </td>`
            op += "<tr>";
            tbody.innerHTML = op;
        })
    }).catch(err => console.log(err))
}


function DelInvoice(id) {
    let DeleteInfo = `Your information will be removed from database.`;
    let confirmation = confirm(DeleteInfo)
    let body = {"id": id}
    if (confirmation) {
        fetch(CommonName + 'deleteinvoice', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)
        }).then(() => {
                SearchByCname()
            }
        ).catch(err => console.log(err));
    }
}

