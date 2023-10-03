function customerSignUp() {
    const fullname = document.getElementById('name').value;
    const username = document.getElementById('username').value;
    const pwd = document.getElementById('password').value;
    const address = document.getElementById('address').value;
    const long = document.getElementById('long').value;
    const lat = document.getElementById('lat').value;
    const role = "Customer";
    let status;

    const data = {
        name: fullname,
        username,
        password: pwd,
        address,
        long: parseFloat(long),
        lat: parseFloat(lat),
        role,
        hubID: 0
    }

    fetch('http://127.0.0.1:8080/models/api/auth/register.php', {
        method: 'POST', // or 'PUT'
        headers: {
            'Content-Type': 'application/json',
        },
        mode: 'cors',
        body: JSON.stringify(data),
    })
    .then((response) => {
        status = response.status
        return response.json()
    })
    .then((data) => {
        if (status == 200) {
            alert(data.message)
            console.log(data.message)
        } else {
            alert(data.message)
            console.log(data.message)
        }
    })
    .catch((error) => console.log(error))

}

function vendorSignUp() {
    const fullname = document.getElementById('name').value;
    const username = document.getElementById('username').value;
    const pwd = document.getElementById('password').value;
    const address = document.getElementById('address').value;
    const long = document.getElementById('long').value;
    const lat = document.getElementById('lat').value;
    const role = "Vendor";
    let status;

    const data = {
        name: fullname,
        username,
        password: pwd,
        address,
        long: parseFloat(long),
        lat: parseFloat(lat),
        role,
        hubID: 0
    }

    fetch('http://127.0.0.1:8080/models/api/auth/register.php', {
        method: 'POST', // or 'PUT'
        headers: {
            'Content-Type': 'application/json',
        },
        mode: 'cors',
        body: JSON.stringify(data),
    })
    .then((response) => {
        status = response.status
        return response.json()
    })
    .then((data) => {
        if (status == 200) {
            alert(data.message)
            console.log(data.message)
        } else {
            alert(data.message)
            console.log(data.message)
        }
    })
    .catch((error) => console.log(error))

}

function shipperSignUp() {
    const username = document.getElementById('username').value;
    const pwd = document.getElementById('password').value;
    const hubID = document.getElementById('hubID').value;
    const role = "Shipper";
    let status;

    const data = {
        name: "",
        username,
        password: pwd,
        address: "",
        long: "",
        lat: "",
        role,
        hubID: parseInt(hubID)
    }

    fetch('http://127.0.0.1:8080/models/api/auth/register.php', {
        method: 'POST', // or 'PUT'
        headers: {
            'Content-Type': 'application/json',
        },
        mode: 'cors',
        body: JSON.stringify(data),
    })
    .then((response) => {
        status = response.status
        return response.json()
    })
    .then((data) => {
        if (status == 200) {
            alert(data.message)
            console.log(data.message)
        } else {
            alert(data.message)
            console.log(data.message)
        }
    })
    .catch((error) => console.log(error))

}