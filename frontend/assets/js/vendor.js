const PAGESIZE = 5;
const BASE_URL = "http://127.0.0.1";
const PORT = "8080";
const vendorID = localStorage.getItem("userID");
var productList = [];
let vendorInfo = {};

// FETCH API

const url_link = `${BASE_URL}:${PORT}/models/api/getProductsByVendorID.php`;

fetch(url_link, {
        method: "POST", // or 'PUT'
        headers: {
            "Content-Type": "application/json",
        },
        mode: 'cors',
        body: JSON.stringify({
            vendorID: vendorID
        }),
    })
    .then((response) => response.json())
    .then((data) => {
        console.log("Success:", data)
        if (!data) {
            return;
        }
        productList = data.products;
        hideLoader();
        show(data.products)
        vendorInfo = data.user
        document.getElementById("vendor-name").innerHTML = data.user.vName
    })
    .catch((error) => {
        console.error("Error:", error);
    });
// Function to hide the loader
function hideLoader() {
    document.getElementById('loading').style.display = 'none';
}

// Navigate to edit product page with a query parameter
function editProduct(productID) {
    // window.alert("Edit product ID " + productID );
    var selectedProduct = productList.find((element) => {
        return element.productID === productID;
    });
    localStorage.setItem("product", JSON.stringify(selectedProduct));
    location.replace("./edit.html");
}

// Function to define innerHTML for HTML table
function show(data) {
    let tab =
        `<thead class="table-light">
      <tr>
        <th scope="col">Thumbnail</th>
        <th scope="col">ID</th>
        <th scope="col">Name</th>
        <th scope="col">Price</th>
        <th scope="col">Action</th>
      </tr>
    </thead>`;

    // Loop to access all rows 
    for (let r of data) {
        tab += `<tr> 
        <td><img src="https://cdn-amz.woka.io/images/I/71hIfcIPyxS.jpg" width="50" height="50" class="thumbnail" /> </td>
        <td>${r.productID}</td>
        <td>${r.pName}</td> 
        <td>$ ${r.price} </td>
        <td> <button type="button" class="btn btn-success" onclick="editProduct(${r.productID})">Edit</button> </td>

    </tr>`;
    }
    // Setting innerHTML as tab variable
    document.getElementById("products").innerHTML = tab;
}