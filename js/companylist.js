function first() {
    fetch("http://localhost/api/getcompany").then(res => res.json()).then(data => {
        var comptable = document.getElementById('comptable')
        op = "<tr>"
        data.data.forEach(function (data, index) {
            op += `<th scope="row">${index + 1}</th>`
            op += `<td>${data['name']}</td>`
            op += `<td>${data['address']}, ${data['city']}</td>`
            op += `<td>${data['email']}</td>`
            op += `<td>${data['contact']}</td>`
            op += `<td><a class="fa fa-edit abc"  data-bs-toggle="modal" data-bs-target="#editModal" onclick="EditCompany(${data['id']})" style="color: black"></a>/ <a class="fa fa-trash" onclick="DeleteCompany(${data['id']})" style="color: black;" ></a> </td>`
            op += "<tr>";
            comptable.innerHTML = op;
        })
    })

}

first()

function EditCompany(id) {
    localStorage.setItem("compid", id)
    var cname = document.getElementById('cname')
    var address = document.getElementById('address')
    var email = document.getElementById('email')
    var city = document.getElementById('city')
    var phone = document.getElementById('phone')
    fetch("http://localhost/api/getcompany?id=" + id).then(res => res.json()).then(data => {
        cname.value = data.data[0]['name']
        address.value = data.data[0]['address']
        email.value = data.data[0]['email']
        city.value = data.data[0]['city']
        phone.value = data.data[0]['contact']
    })
}

function UpdateCompany() {
    var id = localStorage.getItem("compid")
    var name = document.getElementById('cname')
    var address = document.getElementById('address')
    var email = document.getElementById('email')
    var city = document.getElementById('city')
    var phone = document.getElementById('phone')
    mybody = {
        id: id,
        name: name.value,
        address: address.value,
        email: email.value,
        contact: phone.value,
        city: city.value

    }
    console.log(mybody)
    console.log(name.value)
    fetch("http://localhost/api/companyupdate", {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(mybody)

    }).then(res => res.json()).then(data => {
        console.log(data)
        first()
    })
}

function DeleteCompany(id) {
    var RemoveInfo = `Your information will be removed from database.`;
    var Confirmation = confirm(RemoveInfo)
    mybody = {
        id: id
    }
    console.log(mybody)
    if (Confirmation) {
        fetch("http://localhost/api/companydelete", {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(mybody)

        }).then(res => res.json()).then(data => {
            first()
        })
    } else {
        alert("Company details not deleted")
    }
}