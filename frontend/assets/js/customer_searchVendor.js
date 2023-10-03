// Search bar
var searchButton = document.getElementById("search-button");
// Add event listener to button click
searchButton?.addEventListener("click", handleSearch);

// handle search button click
function handleSearch() {
  const searchInput = document.getElementById("search-input");
  const inputValue = searchInput.value;

  getVendorByDistance(inputValue, 1);
}

function getVendorByDistance(searchRadiusm, customerID) {
  const data = {
    searchRadius: searchRadiusm,
    customerID: customerID,
  };

  var url_link = `http://localhost:8080/models/api/searchVendorByDistance.php/`;
  fetch(url_link, {
      method: "POST", // or 'PUT'
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    })
    .then((response) => response.json())
    .then((data) => {
      // console.log("Success:", data.vendors);
      loadVendorCards(data.vendors);
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

// var cardList = [];
// for (let i = 1; i <= 2; i++) {
//   var object = {
//     id: i - 1,
//     image: "https://cdn-amz.woka.io/images/I/71hIfcIPyxS.jpg",
//     name: `name ${i}`,
//     address: `address ${i}`,
//     coordinates: `coordinates ${i}`,
//     username: `username ${i}`,
//     password: `password ${i}`,
//   };
//   cardList.push(object);
// }
// console.log(cardList)

function loadVendorCards(cardList) {
  // load element from the list
  var cardListContainer = document.getElementById("cardListContainer");
  cardListContainer.innerHTML = "";
  cardList.map((card) => {
    cardListContainer.innerHTML += returnCard(card);
  });
}

// Handle buy button click
function handleSelectVendor(reference) {
  const idStr = reference.id;
  const vendorID = Number(
    idStr.substring(idStr.indexOf("btn-select") + 11, idStr.length)
  );
  // alert("select vendor id " + vendorID);
  // Save to local storage
  localStorage.setItem("vendorID", vendorID)
  // Redirect
  location.replace("./vendor_store.html")
}

function returnCard(card) {
  let distance = card.distance.toFixed(2);
  return `<div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">${card.vName}</h5>
                <p class="card-text"> ${card.vAddress} </p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Distance: <b>${distance} km</b></li>
            </ul>
            <div class="card-body">
                <a href="#" class="btn btn-primary" id="btn-select-${card.vendorID}" onclick="handleSelectVendor(this)">Visit vendor</a>
            </div>
        </div>`;
}