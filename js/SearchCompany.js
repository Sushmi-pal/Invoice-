var loc = window.location.href
var name = ((loc.split('&')[0]).split('=')[1]).toUpperCase()


function SearchByCname() {
    fetch("http://localhost/api/searchinvoice?cname=" + `"${name}"`).then(res => res.json()).then(data => {
        tbody = document.getElementById("invoice_details_table")
        op = "<tr>"
        var search_iid=[]
        data.data.forEach(function (data, index) {
            search_iid.push(data['iid'])
            op += `<th scope="row">${index + 1}</th>`
            op += `<td>InvoiceItem${data['iid']}</td>`
            op += `<td>${data['name']}</td>`
            op += `<td><a class="fa fa-eye" href="preview.html#${data['iid']}" style="color: black;" ></a> </td>`

            op += `<td><a class="fa fa-edit" href="update.html#${data['iid']}" style="color: black;"></a> </td>`
            op += `<td><a class="fa fa-trash" onclick="DelInvoice(${data['iid']})" style="color: black"></a> </td>`
            op += "<tr>";
            tbody.innerHTML = op;
            window.search_iid=search_iid
            localStorage.setItem("SearchInvoiceID",search_iid)
        })
    })

}

SearchByCname()


function NewPage(id) {
    let Starting_Inv_ID = (id - 1) * 5
    var storedId = window.search_iid;
    fetch("http://localhost/api/invoicepages?offset=" + storedId[Starting_Inv_ID] + "&limit=5").then(res => res.json()).then(data => {
        tbody = document.getElementById("invoice_details_table")
        localStorage.setItem("total length1", data.data.length)
        op = "<tr>"
        data.data.forEach(function (data, index) {
            op += `<th scope="row">${index + 1}</th>`
            op += `<td>InvoiceItem${data['id']}</td>`
            op += `<td>${data['cname']}</td>`
            op += `<td><a class="fa fa-eye" href="preview.html#${data['id']}" style="color: black;" ></a> </td>`
            op += `<td><a class="fa fa-edit" href="invoice1.html.html#${data['id']}" style="color: black;" ></a> </td>`
            op += `<td><a class="fa fa-trash" onclick="del(${data['id']})" style="color: black;" ></a> </td>`
            op += "<tr>";
            tbody.innerHTML = op;
        })
    })
}


function DelInvoice(id) {
    var DeleteInfo = `Your information will be removed from database.`;
    var confirmation = confirm(DeleteInfo)
    mybody = {"id": id}
    if (confirmation) {
        fetch('http://localhost/api/deleteinvoice', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(mybody)
        }).then(() => {

                SearchByCname()
            }
        ).catch(err => console.log(err));
    }
}