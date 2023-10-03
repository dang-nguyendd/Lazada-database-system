window.onload = function authCheck() {
    const username = localStorage.getItem("username")
    const role = localStorage.getItem("userRole")
    const path = window.location.pathname
    const rootFolder = "/frontend/"
    const rolePath = path.substring(rootFolder.length, rootFolder.length+3)

    if (!username || !role) {
        console.log("not logged in")
        document.getElementById("logoutBtn").style.display = "none"
    
        if (path !== rootFolder + "login.html"
        && path !== rootFolder + "signup.html"
        && path !== rootFolder + "signup_customer.html"
        && path !== rootFolder + "signup_shipper.html"
        && path !== rootFolder + "signup_vendor.html"){
            document.querySelector("main").innerHTML = "<h3>Please login to view this page</h3>"
        } else {

            
        }

    } else {
        console.log("logged in")
        document.getElementById("loginBtn").style.display = "none"
        document.getElementById("signupBtn").style.display = "none"
        if (rolePath === "ven" && role !== "Vendor") {

        }

        if (rolePath === "cus" && role !== "Customer") {

        }

        if (rolePath === "shi" && role !== "Shipper") {

        }
    }
}

function submitLogin() {
    const user = document.getElementById('username').value;
    const pwd = document.getElementById('password').value
    const data = {
        username: user,
        password: pwd
    }

    let status;

    fetch('http://127.0.0.1:8080/models/api/auth/login.php', {
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
            console.log(data)
            localStorage.setItem("userID", data.id)
            localStorage.setItem("username", data.username)
            localStorage.setItem("userRole", data.role)
            if (data.role == "Vendor") {
                window.location.replace("./vendor/index.html")
            }
            if (data.role == "Customer") {
                window.location.replace("./customer/index.html")
            }
            if (data.role == "Shipper") {
                window.location.replace("./shipper/index.html")
            }
        } else {
            alert(data.message)
            console.log(data.message)
        }
    })
    .catch((error) => console.log(error))
}

function authLogout() {
    localStorage.removeItem("userID")
    localStorage.removeItem("username")
    localStorage.removeItem("userRole")
    window.location.reload()
}