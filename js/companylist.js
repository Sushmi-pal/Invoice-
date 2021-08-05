let CommonName = localStorage.getItem("CommonName")

function CompanyList(column_name, order) {
    fetch(CommonName + "getcompany?sort=" + column_name + "&order=" + order).then(res => res.json()).then(data => {
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
    }).catch(err => console.log(err));

}

CompanyList('name', 'desc')

function EditCompany(id) {
    let cname = document.getElementById('cname')
    let address = document.getElementById('company_address')
    let email = document.getElementById('company_email')
    let city = document.getElementById('company_city')
    let phone = document.getElementById('phone')
    let image_id = document.getElementById('image_id')
    localStorage.setItem("CompanyId", id)

    fetch(CommonName + "getcompany?id=" + id).then(res => res.json()).then(data => {
        console.log(data)
        cname.value = data.data[0]['name']
        address.value = data.data[0]['address']
        email.value = data.data[0]['email']
        city.value = data.data[0]['city']
        phone.value = data.data[0]['contact']
        image_id.src = "../uploads/" + data.data[0]['file_name']
        console.log(image_id.src)
    }).catch(err => console.log(err))
}

function UpdateCompany() {
    let name = document.getElementById('cname')
    let address = document.getElementById('company_address')
    let email = document.getElementById('company_email')
    let city = document.getElementById('company_city')
    let phone = document.getElementById('phone')
    let id = localStorage.getItem("CompanyId")
    let file = document.querySelector('[type=file]').files[0]['name']

    let body = {
        id: id,
        name: name.value,
        address: address.value,
        email: email.value,
        contact: phone.value,
        city: city.value,
        file_name: file,
        file_image: localStorage.getItem("UpdateImage")

    }


    fetch(CommonName + "companyupdate", {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(body)

    }).then(res => res.json()).then(() => {
        CompanyList()
    }).catch(err => console.log(err));
}

function DeleteCompany(id) {
    let RemoveInfo = `Your information will be removed from database.`;
    let Confirmation = confirm(RemoveInfo)
    let body = {
        id: id
    }
    if (Confirmation) {
        fetch(CommonName + "companydelete", {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)

        }).then(res => res.json()).then(() => {
            CompanyList()
        })
    } else {
        alert("Company details not deleted")
    }
}

function ChangeImage() {
    const file = document.querySelector('[type=file]').files[0]
    const reader = new FileReader();
    const preview = document.querySelector('img');
    let file_ext = file['name'].split('.')[1].toLowerCase()
    let accepted_extension = ['jpg', 'jpeg', 'png']
    reader.addEventListener("load", function () {
        preview.style.width = "100%";
        preview.src = reader.result;
        localStorage.setItem("UpdateImage", preview.src)
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
    if (!accepted_extension.includes(file_ext)) {
        let msg = document.getElementById('msg')
        msg.innerHTML = `.${file_ext} file is not accepted`
    }
    else{
        let msg = document.getElementById('msg')
        msg.innerHTML=''
    }

}
