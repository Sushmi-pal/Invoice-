function EditInvoice(id) {
    localStorage.setItem("invoice_id", id)
}

function DeleteInvoice(id) {
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
        }).catch(err => console.log(err));
    }
}

function SearchByCname() {
    fetch("http://localhost/api/companylist").then(res => res.json()).then(data => {
            console.log(data)
            tbody = document.getElementById('invoice_details_table')
            console.log('tbody', tbody)
            localStorage.setItem("total length1", data.data.length)
            minarr1 = new Array()
            let op = "<tr>"
            var c = new Array()
            var ind = 0
            data.data.forEach(function (data) {


                    if (!c.includes(data['invoice_id'])) {
                        c.push(data['invoice_id'])
                        op += `<th scope="row">${ind + 1}</th>`
                        op += `<td>InvoiceItem${data['invoice_id']}</td>`
                        op += `<td>${data['company_name']}</td>`
                        op += `<td><a class="fa fa-eye" href="preview.html#${data['invoice_id']}" style="color: black;" ></a> </td>`
                        op += `<td><a class="fa fa-edit" href="update.html#${data['invoice_id']}" onclick="EditInvoice(${data['invoice_id']})" style="color: black;" ></a> </td>`
                        op += `<td><a class="fa fa-trash" onclick="del(${data['invoice_id']})" style="color: black;" ></a> </td>`
                        op += "<tr>";
                        tbody.innerHTML = op;
                        localStorage.setItem("minID1", minarr1[0])
                        var ss = JSON.stringify(minarr1)
                        console.log(ss.length)
                        minID1 = `${data['invoice_id']}`
                        if (`${data['invoice_id']}` < minID1) {
                            minID1 = `${data['invoice_id']}`
                        }
                        minarr1.push(minID1)


                        ind++
                        localStorage.setItem("tt", c.length)
                        localStorage.setItem("allIDs1", c);
                        console.log('ccc', c)

                    }

                }
            )
        }
    )

}

SearchByCname()

