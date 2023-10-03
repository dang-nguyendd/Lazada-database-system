window.onload = function countAttributes() {
    sessionStorage.setItem("attributeCounts", 0);
}
var customAttributes =  document.getElementById('custom-attributes');
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
    attributeName.setAttribute('type','text');
    attributeName.setAttribute('name', attributeNameId);
    attributeName.setAttribute('id',attributeNameId);
    attributeName.setAttribute('class','form-control me-2');
    attributeName.setAttribute('placeholder','Attribute Name');
    newDiv.appendChild(attributeName);
    
    const attributeValueId = "attribute" + count + "Value";
    const attributeValue = document.createElement('input');
    attributeValue.setAttribute('type','text');
    attributeValue.setAttribute('name', attributeValueId);
    attributeValue.setAttribute('id',attributeValueId);
    attributeValue.setAttribute('class','form-control');
    attributeValue.setAttribute('placeholder','Attribute Value');
    newDiv.appendChild(attributeValue);
}

remove_fields.onclick = function removeCustomAttribute() {
    var input_tags = customAttributes.getElementsByTagName('div');
    if(input_tags.length > 0) {
        customAttributes.removeChild(input_tags[(input_tags.length) - 1]);
    }
}

function addProduct() {
    const pName = document.getElementById('pName').value
    const price = document.getElementById('price').value
    const inputData = {pName, price, vendorID: localStorage.getItem("userID"), attributes: {}};
    let status;
    const attributes = document.getElementsByClassName('attributeSet');
    for (let i = 0; i < attributes.length; i++) {
        const inputs = attributes[i].getElementsByTagName('input')
        const attributeName = inputs[0].value
        const attributeValue = inputs[1].value
        inputData.attributes[attributeName] = attributeValue
    }
    // console.log(inputData)

    fetch('http://127.0.0.1:8080/models/api/productInsert.php', {
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
            console.log(data.message)
        } else {
            alert(data.message)
            console.log(data)
        }
    })
    .catch((error) => console.log(error))
}