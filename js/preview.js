let invoice_id = window.location.href.split('#')[1]
let CommonName = localStorage.getItem("CommonName")
fetch(CommonName + "companylist?id=" + invoice_id).then(res => res.json()).then(data => {
    $("#name").append(`${data.data[0]['company_name']}`);
    $("#address").append(`${data.data[0]['address']}, `);
    $("#address").append(`${data.data[0]['city']}`);
    $("#contact").append(`${data.data[0]['contact']}`);
    $("#email").append(`${data.data[0]['email']}`);
    let invoice_id = data.data[0]['invoice_id']
    let invoice_id_str = invoice_id.toString()

    if (invoice_id_str.length == 1) {

        $("#invoice-id").append(`INV00${data.data[0]['invoice_id']}`);
    } else {
        $("#invoice-id").append(`INV0${invoice_id_str}`);
    }
    let date = data.data[0]['created_at']
    date = date.slice(0, 10).split('-').reverse().join('/')
    duedate = date.split('/')
    if (duedate[1] != 12) {
        let month = parseInt(duedate[1]) + 1
        let add_zero = month.toString().length == 1 ? '0' + month : month
        var duedate = duedate[0] + '/' + add_zero + '/' + duedate[2]
    } else {
        let month = parseInt(duedate[1]) + 1
        let year = parseInt(duedate[2]) + 1
        let add_zero = month.toString().length == 1 ? '0' + month : month
        var duedate = duedate[0] + '/' + add_zero + '/' + year
    }
    $("#date").append(date);
    $("#duedate").append(duedate);
}).catch(err=>console.log(err))




