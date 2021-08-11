let CommonName = localStorage.getItem("CommonName")


function DeleteInvoice(id) {
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
        }).catch(err => console.log(err));
    }
}

function SearchByCname() {
    fetch(CommonName + "companylist").then(res => res.json()).then(data => {
            let tbody = document.getElementById('invoice_details_table')
            localStorage.setItem("total length1", data.data.length)
            let MinArr1 = []
            let op = "<tr>"
            let UniqueIid = []
            let index = 0
            data.data.forEach(function (data) {
                    if (!UniqueIid.includes(data['invoice_id'])) {
                        UniqueIid.push(data['invoice_id'])
                        op += `<th scope="row">${index + 1}</th>`
                        op += `<td>InvoiceItem${data['invoice_id']}</td>`
                        op += `<td>${data['company_name']}</td>`
                        op += `<td><a class="fa fa-eye" href="preview.html#${data['invoice_id']}" style="color: black;" ></a> </td>`
                        op += `<td><a class="fa fa-edit" href="update.html#${data['invoice_id']}" onclick="EditInvoice(${data['invoice_id']})" style="color: black;" ></a> </td>`
                        op += `<td><a class="fa fa-trash" onclick="DeleteInvoice(${data['invoice_id']})" style="color: black;" ></a> </td>`
                        op += "<tr>";
                        tbody.innerHTML = op;
                        localStorage.setItem("minID1", MinArr1[0])
                        let MinID1 = `${data['invoice_id']}`
                        if (`${data['invoice_id']}` < MinID1) {
                            MinID1 = `${data['invoice_id']}`
                        }
                        MinArr1.push(MinID1)
                        index++
                        localStorage.setItem("tt", UniqueIid.length.toString())
                        localStorage.setItem("allIDs1", UniqueIid.toString());
                    }
                }
            )
        }
    )
}

function EditInvoice(id){
    localStorage.setItem("invoice_id",id)
}
SearchByCname()

