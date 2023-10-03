# Lazada-Database

## **Table of contents**
- [Lazada-Database](#lazada-database)
  - [**Table of contents**](#table-of-contents)
  - [**Members**](#members)
  - [**Installation**](#installation)
  - [**Video Link**](#video-link)
  - [**Sitemap**](#sitemap)
  - [**Misc Info**](#misc-info)
    - [**Files & Folders structure**](#files--folders-structure)
  - [**Final checklist**](#final-checklist)


## **Members**

- Anh Tran - s3914138 - Contribution: 5
- Khang Nguyen - s3802040 - Contribution: 5
- Nguyen Nguyen - s3759957 - Contribution: 5
- Phuc Nguyen - s3819660 - Contribution: 5
  
## **Installation**

**Steps:**

  1. Run `database/table.sql` to Create Tables & Views
  2. Run `database/auth.sql` to Create & Grant User Roles
  3. Run `database/data.sql` to Insert all necessary data
  4. Run `database/functions.sql` to Create all necessary functions, triggers, and stored procedures
  5. Import `database/mongoData.json` to Insert all necessary custom attributes data for Products
  6. Run `php -S 127.0.0.1:8080` to start the PHP Server API
  7. Run `frontend/login.html` with Live Server to start the Frontend Web Client

**Demo login info:**
All users' password: `myPassword`

- Customer: `customer1`
- Vendor: `vendor1`
- Shipper: `shipper1`


**Technologies:**

- Databases: MySQL 8, MongoDB 6
- Web application: PHP 8, HTML, CSS, Vanilla Javascript

## **Video Link**


## **Sitemap**

Guest
`index.html`
`login.html`
`signup.html`
`signup_customer.html`
`signup_shipper.html`
`signup_vendor.html`

Customer
`customer/index.html`
`customer/search_vendor.html`
`customer/vendor_store.html`

Vendor
`vendor/index.html`
`vendor/add.html`
`vendor/edit.html`

Shipper
`shipper/index.html`


## **Misc Info**

### **Files & Folders structure**

- `database` - SQL codes and mock data
- `frontend` - Website frontend client
- `models` - Website backend PHP server
- `models/vendor` - folder of Composer package manager tool
- `models/config` - Config for MongoDB & MySQL database connection settings
- `models/api` - API routes controllers
- `models/src` - Logic gateway classes and functions for APIs
- `models/src/auth` - Logic for Login/SignUp
- `models/src/product` - Logic for Create, Update, List, and Search products
- `models/src/Customer.php` - Logic for Buy a Product / Create a new Order
- `models/src/DistanceVendorCustomer.php` - Logic for calculating distance between vendor and customer
- `models/src/Shipper.php` - Logic for Update and Get Orders from Hub
- `models/src/UserInfo.php` - Utility logic for getting a user's information

## **Final checklist**

- [x] Trigger: Auto assign the nearest Distribution Hub to a Vendor
- [x] Trigger: Auto assign the nearest Distribution Hub to an Order == Customer
- [x] Security: Implement RBAC at database level, password hashing, prepared statements
- [x] SQL codes and database are good to submit
- [x] Login/Registration Page
- [x] Vendor Page:
  - [x] List all own products
  - [x] Add a product with custom attributes
  - [x] Edit a product with custom attributes
  - [x] Error handlings
- [ ] Customer Page:
  - [x] View All Products HIGHEST PERFORMANCE POSSIBLE
    - [x] Sort by createdDate Descending
    - [x] Pagination
  - [x] Search Products by Name and Price HIGHEST PERFORMANCE POSSIBLE
  - [x] Search Products with custom attributes
  - [x] Search Vendors by Distance
  - [x] View vendor page
  - [x] *Buy a product API and Frontend*
    - [x] Transaction, Trigger, Locking 
    - [ ] <span style="color:orange;">**a 10-30s wait time**</span>
    - [x] Show a success message alert from frontend to let users know
- [ ] Shipper Page:
  - [x] View orders at the hub
  - [x] Update order status
    - [x] Prevent 2 shippers from updating the same product
    - [ ] <span style="color:orange;">**10-30s wait time**</span>****