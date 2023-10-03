const PAGESIZE = 5;
const BASE_URL = "http://127.0.0.1";
const PORT = "8080";
const shipperID = localStorage.getItem("userID");

// FETCH API
fetchOrders(shipperID);

// Fetch Orders based on ShipperID
function fetchOrders(shipperID) {
    const url_link = `${BASE_URL}:${PORT}/models/api/shipperGetAllOrders.php`;
    fetch(url_link, {
            method: "POST", // or 'PUT'
            headers: {
                "Content-Type": "application/json",
            },
            mode: 'cors',
            body: JSON.stringify({
                shipperID: shipperID
            }),
        })
        .then((response) => response.json())
        .then((data) => {
            console.log("Success", data)
            if (!data.length) {
                return;
            }
            hideLoader();
            show(data);
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

// Function to hide the loader
function hideLoader() {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('products').style.display = 'block';
}

// Function to show the loader
function showLoader() {
    document.getElementById('loading').style.display = 'block';
    document.getElementById('products').style.display = 'none';
}

// Navigate to edit product page with a query parameter
function editProduct(id) {
    window.alert("Edit order ID " + id);
}

// Handle order status change
function handleStatusChange(ref) {
    var orderID = ref.id.substring(ref.id.lastIndexOf('-') + 1);
    var updatedStatus = document.getElementById(`select-status-${orderID}`).value;
    console.log(updatedStatus)

    // Update orders status
    updateOrderStatus(orderID, updatedStatus);
}

// Update order status
function updateOrderStatus(orderID, oStatus) {
    const url_link = `${BASE_URL}:${PORT}/models/api/shipperUpdateOrder.php`;

    // Show loader
    showLoader()

    fetch(url_link, {
            method: "POST", // or 'PUT'
            headers: {
                "Content-Type": "application/json",
            },
            mode: 'cors',
            body: JSON.stringify({
                orderID: orderID,
                oStatus: oStatus
            }),
        })
        .then((response) => response.json())
        .then((data) => {
            console.log("Success", data)
            if (!data.length) {
                return;
            }

            // Hide loader
            hideLoader();

            // Fetch orders for updates to show
            fetchOrders(shipperID);;
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

// Function to define innerHTML for HTML table
function show(data) {
    let tab =
        `<thead class="table-light">
      <tr></tr>
        <th scope="col">Order ID</th>
        <th scope="col">Customer ID</th>
        <th scope="col">Customer</th>
        <th scope="col">Hub ID</th>
        <th scope="col">Product</th>
        <th scope="col">Created</th>
        <th scope="col">Status</th>
      </tr>
    </thead>`;

    // Loop to access all rows 

    var selectHtml = "";
    for (let r of data) {
        if (r.oStatus === "Ready") {
            selectHtml = `<option value="Ready" selected>Ready</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Canceled">Canceled</option>`;
        } else if (r.oStatus === "Shipped") {
            selectHtml = `<option value="Ready">Ready</option>
                            <option value="Shipped" selected>Shipped</option>
                            <option value="Canceled">Canceled</option>`;
        } else {
            selectHtml = `<option value="Ready">Ready</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Canceled" selected>Canceled</option>`;
        }
        tab += `<tr> 
        <td>${r.orderID}</td>
        <td>${r.customerID}</td>
        <td>${r.cName}</td>
        <td>${r.hubID} </td>
        <td>${r.pName} </td>
        <td>${r.dateCreated} </td>
        
        <td>
            <select class="form-select" name="status" id="select-status-${r.orderID}" onchange="handleStatusChange(this)">
                ${selectHtml}
            </select>
        </td>

    </tr>`;
    }
    // Setting innerHTML as tab variable
    document.getElementById("products").innerHTML = tab;
}