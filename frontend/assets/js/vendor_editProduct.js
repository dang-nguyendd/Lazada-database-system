const BASE_URL = "http://127.0.0.1";
const PORT = "8080";
var product = JSON.parse(localStorage.getItem("product"));
var attributes = [];
var product_name = document.getElementById("pName");
var product_price = document.getElementById("price");

// Fetch product
fetchProduct(product.productID);

window.onload = function countAttributes() {
    sessionStorage.setItem("attributeCounts", 0);
}
var customAttributes = document.getElementById('custom-attributes');
var add_more_fields = document.getElementById('add_more_fields');
var remove_fields = document.getElementById('remove_fields');

add_more_fields.onclick = function addCustomAttributes() {
    sessionStorage.setItem("attributeCounts", parseInt(sessionStorage.getItem("attributeCounts")) + 1);
    let count = sessionStorage.getItem("attributeCounts");
    const newDiv = document.createElement("div");
    newDiv.setAttribute('class', 'attributeSet d-flex mb-3');
    newDiv.setAttribute('id', 'attributeID' + count);
    customAttributes.appendChild(newDiv);

    const attributeNameId = "attribute" + count + "Name";
    const attributeName = document.createElement('input');
    attributeName.setAttribute('type', 'text');
    attributeName.setAttribute('name', attributeNameId);
    attributeName.setAttribute('id', attributeNameId);
    attributeName.setAttribute('class', 'form-control me-2');
    attributeName.setAttribute('placeholder', 'Attribute Name');
    newDiv.appendChild(attributeName);

    const attributeValueId = "attribute" + count + "Value";
    const attributeValue = document.createElement('input');
    attributeValue.setAttribute('type', 'text');
    attributeValue.setAttribute('name', attributeValueId);
    attributeValue.setAttribute('id', attributeValueId);
    attributeValue.setAttribute('class', 'form-control');
    attributeValue.setAttribute('placeholder', 'Attribute Value');
    newDiv.appendChild(attributeValue);
}

remove_fields.onclick = function removeCustomAttribute() {
    var input_tags = customAttributes.getElementsByTagName('div');
    if (input_tags.length > 0) {
        customAttributes.removeChild(input_tags[(input_tags.length) - 1]);
    }
}

// Show data
function showData(product) {
    product_name.value = product.pName;
    product_price.value = product.price;

    var count = Object.keys(attributes).length;
    sessionStorage.setItem("attributeCounts", count);
    attributes && Object.keys(attributes).forEach(e => {
        const newDiv = document.createElement("div");
        newDiv.setAttribute('class', 'attributeSet d-flex mb-3');
        newDiv.setAttribute('id', 'attributeID' + parseInt(count));
        customAttributes.appendChild(newDiv);

        const attributeNameId = "attribute" + count + "Name";
        const attributeName = document.createElement('input');
        attributeName.setAttribute('type', 'text');
        attributeName.setAttribute('name', attributeNameId);
        attributeName.setAttribute('id', attributeNameId);
        attributeName.setAttribute('class', 'form-control me-2');
        attributeName.setAttribute('placeholder', 'Attribute Name');
        attributeName.value = e;
        newDiv.appendChild(attributeName);

        const attributeValueId = "attribute" + count + "Value";
        const attributeValue = document.createElement('input');
        attributeValue.setAttribute('type', 'text');
        attributeValue.setAttribute('name', attributeValueId);
        attributeValue.setAttribute('id', attributeValueId);
        attributeValue.setAttribute('class', 'form-control');
        attributeValue.setAttribute('placeholder', 'Attribute Value');
        attributeValue.value = attributes[e];
        newDiv.appendChild(attributeValue);
    });
}

// Fetch product
function fetchProduct(productID) {
    var url = `${BASE_URL}:${PORT}/models/api/getSingleProduct.php`;
    let status = 0;
    fetch(url, {
            method: "POST", // or 'PUT'
            headers: {
                "Content-Type": "application/json",
            },
            mode: 'cors',
            body: JSON.stringify({
                productID: productID
            }),
        })
        .then((response) => {
            status = response.status
            return response.json()
        })
        .then((data) => {
            // console.log("Success:", data);

            if (status == 200) {
                // Get attributes
                attributes = data.attributes;

                // Show data
                showData(product);
            } else {
                alert(data.message)
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
}

function editProduct() {
    const pName = document.getElementById('pName').value
    const price = document.getElementById('price').value
    const inputData = {productID: product.productID,pName, price, vendorID: localStorage.getItem("userID"), attributes: {}};
    let status;
    const attributeSet = document.getElementsByClassName('attributeSet');
    for (let i = 0; i < attributeSet.length; i++) {
        const inputs = attributeSet[i].getElementsByTagName('input')
        const attributeName = inputs[0].value
        const attributeValue = inputs[1].value
        inputData.attributes[attributeName] = attributeValue
    }
    console.log("inputData", inputData)

    fetch(`${BASE_URL}:${PORT}/models/api/productUpdate.php`, {
        method: 'POST', // or 'PUT'
        headers: {
            'Content-Type': 'application/json',
        },
        mode: 'cors',
        body: JSON.stringify(inputData),
    })
    .then((response) => {
        status = response.status
        console.log(response)
        return response.json()
    })
    .then((data) => {
        if (status == 200) {
            alert(data.message)
        } else {
            alert(data.message)
        }
    })
    .catch((error) => console.log(error))
}