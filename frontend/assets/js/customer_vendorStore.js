const PAGESIZE = 5;
var totalPages = 0;
const BASE_URL = "http://127.0.0.1";
const PORT = "8080";
var prev = document.getElementById("previousIcon");
var next = document.getElementById("nextIcon");
var text = "";
var cardListLoaded = [];
let vendorInfo = {};

// Initialize search elements
// initSearch();
// First load all products
// firstLoad();
getProductsByVendorID();
// Hide success message
showSuccessMessage(false);

// Function to hide the loader
function hideLoader() {
  document.getElementById("loading").style.display = "none";
  // Hide content
  document.getElementById("cardListContainer").style.display = "flex";
  document.getElementById("store-name").innerHTML =
    "Store: " + vendorInfo.vName;
}

function showLoader() {
  document.getElementById("loading").style.display = "block";
  // Hide content
  document.getElementById("cardListContainer").style.display = "none";
}

// Get products by vendor ID
function getProductsByVendorID() {
  var url = `${BASE_URL}:${PORT}/models/api/getProductsByVendorID.php`;
  const data = {
    vendorID: localStorage.getItem("vendorID"),
  };
  fetch(url, {
    method: "POST", // or 'PUT'
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((response) => response.json())
    .then((data) => {
      // console.log("Success:", data);
      var totalPages = data.products.length;
      vendorInfo = data.user;

      // Calculate pagination
      if (totalPages % PAGESIZE == 0) {
        totalPages = totalPages / PAGESIZE;
      } else {
        totalPages = totalPages / PAGESIZE + 1;
      }
      // Hide pagination navigation if total page is <= 1
      if (totalPages < 2) {
        document.getElementById("navigation").innerHTML = "";
      }

      // Get card list from fetched data
      cardListLoaded = data.products;
      // load element from the list
      // loadProductCard(cardListLoaded);
      getItemList(1);

      for (i = 1; i <= totalPages; i++) {
        if (i == 1) {
          text += `<li class="page-item"><a class="page-link active" value=${
            cardListLoaded[i - 1].orderID
          } onclick="pressNavigation(this), getItemList(${i})">${i}</a></li>`;
          continue;
        }
        text += `<li class="page-item"><a class="page-link" value=${
          cardListLoaded[i - 1].orderID
        } onclick="pressNavigation(this), getItemList(${i})">${i}</a></li>`;
      }
      prev.insertAdjacentHTML("afterend", text);

      // Hide loader
      hideLoader();

      // if first load
      //   if (isFirstLoad) {
      //     generatePagination(totalPages);
      //   }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

// Delay function
function delay(time) {
  return new Promise((resolve) => setTimeout(resolve, time));
}

// Example POST method implementation:
function createOrder(product) {
  // show loader
  showLoader();

  // console.log("userID", localStorage.getItem("userID"))

  const data = {
    oStatus: "Ready",
    customerID: localStorage.getItem("userID"),
    vendorID: product.vendorID,
    productID: product.productID,
  };

  let url = `${BASE_URL}:${PORT}/models/api/customerInsertOrder.php`;
  fetch(url, {
    method: "POST", // or 'PUT'
    headers: {
      "Content-Type": "application/json",
    },
    mode: "cors",
    body: JSON.stringify(data),
  })
    .then((response) => response)
    .then((data) => {
      console.log("Success:", data);

      // hide loader
      hideLoader();

      // Alert buy successfully
      showSuccessMessage(true);
      delay(1500).then(() => showSuccessMessage(false));
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function getItemList(page) {
  removePageItem();

  var cardList = [];
  for (
    let i = 0 + (page - 1) * PAGESIZE;
    i < PAGESIZE + (page - 1) * PAGESIZE;
    i++
  ) {
    // if card item is not null
    if (cardListLoaded[i]) cardList.push(cardListLoaded[i]);
  }

  loadProductCard(cardList);
}

function removePageItem() {
  document.getElementById("cardListContainer").innerHTML = "";
}

// Press next navigation
function pressNext(reference) {
  var item = document.querySelector(".page-link.active");
  var pageNo = parseInt(item.innerHTML) + 1;
  var elements = document.getElementsByClassName("page-link");
  // console.log("remove active from", item);
  // console.log("pageNo", pageNo);

  if (pageNo < elements.length - 1) {
    item.classList.remove("active");
    elements[pageNo].classList.add("active");

    // Get product by page if no search has been made
    getItemList(pageNo);
  }

  // for (var i = 1; i < elements.length - 1; i++) {
  //   console.log("elements", elements[i])
  // }
}

// Prev prev navigation
function pressPrev(reference) {
  var item = document.querySelector(".page-link.active");
  var pageNo = parseInt(item.innerHTML) - 1;
  var elements = document.getElementsByClassName("page-link");

  if (pageNo > 0) {
    item.classList.remove("active");
    elements[pageNo].classList.add("active");

    // Get product by page if no search has been made
    getItemList(pageNo);
  }
}

// Handle navigation press
function pressNavigation(reference) {
  // var item = document.getElementsByClassName('active')[0]
  // item.classList.remove("active");
  // reference.classList.add("active");

  var item = document.querySelector(".page-link.active");
  // console.log("remove active from", item);
  item.classList.remove("active");
  reference.classList.add("active");
  // console.log("add active to", reference);

  var pageNo = reference.innerHTML;

  // Get product by page if no search has been made
  //   getProductsByPage(PAGESIZE, pageNo - 1, false);
}

// Generate pagination
function generatePagination(totalPages) {
  var previousIcon = document.getElementById("previousIcon");
  var text = "";

  //   var pageItems = document.getElementsByClassName("page-number");
  //   if (pageItems.length > 0) {
  //     console.log("pageItems", pageItems.length);
  //     for (let i = 0; i < pageItems.length; i++) {
  //       console.log("remove", i);
  //       //   pageItems[i].remove();
  //     }
  //   }
  document.getElementById("page-numbers").innerHTML = "";

  text = `<div id="page-numbers">`;
  for (i = 1; i <= totalPages; i++) {
    if (i == 1) {
      text += `<li class="page-item page-number"><a class="page-link active" value=${i} onclick="pressNavigation(this)"  href="#">${i}</a></li>`;
      continue;
    }
    // console.log(i + 1)
    text += `<li class="page-item page-number"><a class="page-link" value=${i} onclick="pressNavigation(this)"  href="#">${i}</a></li>`;
  }
  text += `</div>`;
  previousIcon.insertAdjacentHTML("afterend", text);
}

// Generate product cards
function loadProductCard(cardList) {
  var cardListContainer = document.getElementById("cardListContainer");
  cardListContainer.innerHTML = "";

  cardList.map((card) => {
    cardListContainer.innerHTML += returnCard(card);
  });
}

// Handle buy button click
function handleBuy(reference) {
  const idStr = reference.id;
  const productId = Number(
    idStr.substring(idStr.indexOf("btn-buy") + 8, idStr.length)
  );
  // alert("buy product id " + productId);

  cardListLoaded.map((e) => {
    if (e.productID === productId) createOrder(e);
  });
}

// Show success message
function showSuccessMessage(isShown) {
  if (isShown) {
    document.getElementById("create-order-message").style.display = "flex";
    return;
  }
  document.getElementById("create-order-message").style.display = "none";
}

// Delay function
function delay(time) {
  return new Promise((resolve) => setTimeout(resolve, time));
}

// Format a date to YYYY-MM-DD (or any other format)
function padTo2Digits(num) {
  return num.toString().padStart(2, "0");
}

// Format date
function formatDate(date) {
  return [
    date.getFullYear(),
    padTo2Digits(date.getMonth() + 1),
    padTo2Digits(date.getDate()),
  ].join("-");
}

// Get card element
function returnCard(card) {
  // return `<div class="card" style="width: 18rem;">
  //             <img src= ${card.image} class="card-img-top" alt="..."
  //             width="50%" height="50%">
  //             <div class="card-body">
  //                 <h5 class="card-title">${card.pName}</h5>
  //             </div>
  //             <div class="card-body">
  //                 <h3>${card.price}$</h3>
  //                 <a href="#" class="btn btn-primary" id="btn-buy-${card['productID']}" onclick="handleBuy(this)">Buy now</a>
  //             </div>
  //         </div>`
  return `<div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">${card.pName}</h5>
                    <p class="card-title">Created date: <b>${formatDate(
                      new Date(card.createdDate)
                    )}</b></p>
                </div>
                <div class="card-body">
                    <h3>${card.price}$</h3>
                    <a href="#" class="btn btn-primary" id="btn-buy-${
                      card["productID"]
                    }" onclick="handleBuy(this)">Buy now</a>
                </div>
            </div>`;
}
