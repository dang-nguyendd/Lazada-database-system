const PAGESIZE = 10;
const BASE_URL = "http://127.0.0.1";
const PORT = "8080";
var nameSearch = "";
var minPriceSearch = -1;
var maxPriceSearch = -1;
var attrSearch = "";
var attrNameSearch = "";
var cardListLoaded = [];

// Initialize search elements
initSearch();
// First load all products
firstLoad();
// Hide success message
showSuccessMessage(false);

// Function to hide the loader
function hideLoader() {
  document.getElementById("loading").style.display = "none";
  // Hide content
  document.getElementById("cardListContainer").style.display = "flex";
}

function showLoader() {
  document.getElementById("loading").style.display = "block";
  // Hide content
  document.getElementById("cardListContainer").style.display = "none";
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

// Initialize search elements
function initSearch() {
  // Search bar
  var searchButton = document.getElementById("search-button");
  // Add event listener to button click
  searchButton?.addEventListener("click", handleSearchName);
  // Search by custom attribute
  var searchButtonAttr = document.getElementById("search-button-attribute");
  searchButtonAttr?.addEventListener("click", handleSearchAttr);
  // Search by price
  var searchButtonPrice = document.getElementById("search-button-price");
  searchButtonPrice?.addEventListener("click", handleSearchPrice);
}

// Clear Search by name input
function clearSearchByNameInput() {
  document.getElementById("search-input").value = "";
}

// Clear Search by price input
function clearSearchByPriceInput() {
  document.getElementById("search-input-min").value = "";
  document.getElementById("search-input-max").value = "";
}

// Clear Search by custom attributes input
function clearSearchByCustomAttrInput() {
  document.getElementById("search-input-custom").value = "";
  document.getElementById("search-input-attribute").value = "";
}

// handle search button click
function handleSearchName() {
  const searchInput = document.getElementById("search-input");
  const inputValue = searchInput.value;
  nameSearch = inputValue;
  minPriceSearch = -1;
  attrSearch = "";
  attrNameSearch = "";

  // Clear other search
  clearSearchByPriceInput();
  clearSearchByCustomAttrInput();

  // Get products based on current search
  getProductsByName(PAGESIZE, 0, inputValue, true);
}

// Fetch products from url
function fetchProducts(url, data, isFirstLoad) {
  // console.log(url, data, isFirstLoad)
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

      var totalPages = data.totalPages;
      cardListLoaded = data.productsInPage;
      // load element from the list
      loadProductCard(cardListLoaded);

      // if first load
      if (isFirstLoad) {
        generatePagination(totalPages);
      }

      // hide loader
      hideLoader();
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

// Get product by name
function getProductsByName(pageSize, pageNo, productName, isFirstLoad) {
  var url_link = `${BASE_URL}:${PORT}/models/api/productSearchByName.php/`;
  const data = {
    pageSize: pageSize,
    pageNo: pageNo,
    nameSearch: productName,
  };
  fetchProducts(url_link, data, isFirstLoad);
}

// handle search button click
function handleSearchPrice() {
  const searchMin = document.getElementById("search-input-min");
  const searchMax = document.getElementById("search-input-max");
  minPriceSearch = searchMin.value;
  maxPriceSearch = searchMax.value;
  nameSearch = "";
  attrSearch = "";

  // Show all if min and max is null
  if (!minPriceSearch || !maxPriceSearch) {
    firstLoad();
    return;
  }

  // Clear other search
  clearSearchByNameInput();
  clearSearchByCustomAttrInput();
  // Get products by search
  getProductsByPrice(PAGESIZE, 0, minPriceSearch, maxPriceSearch, true);
}

// Get product by price range
function getProductsByPrice(pageSize, pageNo, minPrice, maxPrice, isFirstLoad) {
  var url_link = `${BASE_URL}:${PORT}/models/api/productSearchByPrice.php/`;
  const data = {
    pageSize: pageSize,
    pageNo: pageNo,
    priceLower: minPrice,
    priceUpper: maxPrice,
  };
  fetchProducts(url_link, data, isFirstLoad);
}

// handle search button click
function handleSearchAttr() {
  const searchInput = document.getElementById("search-input-custom");
  const searchAttr = document.getElementById("search-input-attribute");
  nameSearch = "";
  minPriceSearch = -1;
  attrSearch = searchInput.value;
  attrNameSearch = searchAttr.value;

  // Show all if min and max is null
  if (!attrSearch || !attrNameSearch) {
    firstLoad();
    return;
  }
  // Clear other search
  clearSearchByNameInput();
  clearSearchByPriceInput();
  // Get products by search
  getProductsByAttr(PAGESIZE, 0, attrSearch, attrNameSearch, true);
}

// Get product by custom attributes
function getProductsByAttr(
  pageSize,
  pageNo,
  searchValue,
  searchAttr,
  isFirstLoad
) {
  var url_link = `${BASE_URL}:${PORT}/models/api/productSearchByCustom.php/`;
  const data = {
    pageSize: pageSize,
    pageNo: pageNo,
    searchAttr: searchAttr,
    searchValue: searchValue,
  };
  fetchProducts(url_link, data, isFirstLoad);
}

// Get all products by page
function getProductsByPage(pageSize, pageNo, isFirstLoad) {
  var url_link = `${BASE_URL}:${PORT}/models/api/productGetByPage.php/`;
  const data = {
    pageSize: pageSize,
    pageNo: pageNo,
  };
  fetchProducts(url_link, data, isFirstLoad);
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
    getProductsByPage(PAGESIZE, pageNo - 1, false);
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
    getProductsByPage(PAGESIZE, pageNo - 1, false);
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

  // Check if search by name
  if (nameSearch.trim().length !== 0) {
    getProductsByName(PAGESIZE, pageNo - 1, nameSearch, false);
    return;
  }
  // Check if search by price
  if (minPriceSearch >= 0) {
    getProductsByPrice(
      PAGESIZE,
      pageNo - 1,
      minPriceSearch,
      maxPriceSearch,
      false
    );
    return;
  }

  // Check if search by custom attributes
  if (attrSearch.trim().length !== 0 || attrNameSearch.trim().length !== 0) {
    getProductsByAttr(PAGESIZE, pageNo - 1, attrSearch, attrNameSearch, false);
    return;
  }

  // Get product by page if no search has been made
  getProductsByPage(PAGESIZE, pageNo - 1, false);
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

function firstLoad() {
  var pageNo = 0;
  getProductsByPage(PAGESIZE, pageNo, true);
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

  cardListLoaded.map((e) => {
    if (e.productID === productId) createOrder(e);
  });
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
                      card.productID
                    }" onclick="handleBuy(this)">Buy now</a>
                </div>
            </div>`;
}
