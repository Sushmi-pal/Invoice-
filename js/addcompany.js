
$("#cname"). on ("keyup paste",function (){
    if (spanname.innerText=='Company name is empty'){
        spanname.innerText=''
    }
})

$("#address").on ("keyup paste", function (){
    if (spanaddress.innerText=='Address field is empty'){
        spanaddress.innerText=''
    }
})

$("#city").on ("keyup paste",function (){
    if (spancity.innerText=='City name is empty'){
        spancity.innerText=''
    }
})

$("#phone").on ("keyup paste",function (){
    if (spancontact.innerText=='Contact number field is empty'){
        spancontact.innerText=''
    }
})

$("#email").on("keyup paste", function (){
    if (spanemail.innerText=='Email field is empty'){
        spanemail.innerText=''
    }
})




$(document).ready(function (){
    $("#addPostForm").validate({
        rules:{
            cname:{
                required:true,
                minlength:3
            },
            address:{
                required: true,
                minlength:2
            },
            email:{
                email:true,
                required:true
            },
            city:{
                required:true
            },
            phone:{
                required:true,
                minlength:10
            }
        }
    })
})


function vemail(){
    var email=document.getElementById('email')
    fetch("http://localhost/mvctry/emailvalidate", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({"email":email.value})
    }).then(res => res.json()).then(data => {
        document.getElementById('spanemail').innerText=data['Message']})
    if (document.getElementById('spanemail').innerText=="Email address already exists"){
        return false
    }
    else{
        return true
    }
}

$("body").click(function (){
    vemail()
})

function vaddress(){
    if (address.value==''){
        document.getElementById('spanaddress').innerText="Address field is empty"
        return false
    }
    if (address.value.length<2){
        return false
    }
    console.log('address',address.value)
    return true
}

function vcname(){
    if (cname.value==''){
        document.getElementById('spanname').innerText="Company name is empty"
        return false
    }
    document.getElementById('spanname').innerText=''
    return true
}

function vcity(){
    if (city.value==''){
        document.getElementById('spancity').innerText="City name is empty"
        return false
    }
    document.getElementById('spancity').innerText=''
    return true
}

function vphone(){
    if (phone.value==''){
        document.getElementById('spancontact').innerText="Contact number field is empty"
        return false
    }
    document.getElementById('spancontact').innerText=''
    return true
}

function emailempty(){
    if (document.getElementById('email').value==''){
        document.getElementById('spanemail').innerText="Email field is empty"
        return false
    }
    else{
        return true
    }

}



document.getElementById('addPostForm').addEventListener('submit',function (e) {
    e.preventDefault()
    var name = document.getElementById('cname')
    var address = document.getElementById('address')
    var email = document.getElementById('email')
    var city = document.getElementById('city')
    var phone = document.getElementById('phone')

    const myPost = {
        email: email.value,
        name: name.value,
        address: address.value,
        contact: phone.value,
        city: city.value
    }

    vcname()
    vaddress()
    emailempty()
    vcity()
    vphone()
    console.log(emailempty())
    console.log("vemail",vemail())


    if (vaddress() && vcname() && emailempty() && vcity() && vphone() && vemail()) {
        fetch("http://localhost/mvctry/companycreate", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(myPost)
        }).then(res => res.json()).then(data => {
            console.log(data)
            alert("Your information has been saved")
            window.location.href="invoice1.html"

        })}
})

$("#email"). on ("keyup keypress paste",function (){
    vemail()
})
