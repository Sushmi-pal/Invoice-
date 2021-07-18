function CompanyList() {
    fetch("http://localhost/api/getcompany").then(res => res.json()).then(data => {
        let CompanyTable = document.getElementById('CompanyTable')
        let op = "<tr>"
        data.data.forEach(function (data, index) {
            op += `<th scope="row">${index + 1}</th>`
            op += `<td>${data['name']}</td>`
            op += `<td>${data['address']}, ${data['city']}</td>`
            op += `<td>${data['email']}</td>`
            op += `<td>${data['contact']}</td>`
            op += `<td><a class="fa fa-edit abc"  data-bs-toggle="modal" data-bs-target="#editModal" onclick="EditCompany(${data['id']})" style="color: black"></a>/ <a class="fa fa-trash" onclick="DeleteCompany(${data['id']})" style="color: black;" ></a> </td>`
            op += "<tr>";
            CompanyTable.innerHTML = op;
        })
    }).catch(error => console.log(error))

}

CompanyList()

function EditCompany(id) {
    let cname = document.getElementById('cname')
    let address = document.getElementById('address')
    let email = document.getElementById('email')
    let city = document.getElementById('city')
    let phone = document.getElementById('phone')
    localStorage.setItem("CompanyId", id)

    fetch("http://localhost/api/getcompany?id=" + id).then(res => res.json()).then(data => {
        cname.value = data.data[0]['name']
        address.value = data.data[0]['address']
        email.value = data.data[0]['email']
        city.value = data.data[0]['city']
        phone.value = data.data[0]['contact']
    })
}

function UpdateCompany() {
    let name = document.getElementById('cname')
    let address = document.getElementById('address')
    let email = document.getElementById('email')
    let city = document.getElementById('city')
    let phone = document.getElementById('phone')
    let id = localStorage.getItem("CompanyId")
    let body = {
        id: id,
        name: name.value,
        address: address.value,
        email: email.value,
        contact: phone.value,
        city: city.value

    }

    fetch("http://localhost/api/companyupdate", {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(body)

    }).then(res => res.json()).then(() => {
        CompanyList()
    })
}

function DeleteCompany(id) {
    let RemoveInfo = `Your information will be removed from database.`;
    let Confirmation = confirm(RemoveInfo)
    let body = {
        id: id
    }
    if (Confirmation) {
        fetch("http://localhost/api/companydelete", {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)

        }).then(res => res.json()).then(() => {
            CompanyList()
        }).catch(error => console.log(error))
    } else {
        alert("Company details not deleted")
    }
}