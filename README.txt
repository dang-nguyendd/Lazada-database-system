Link: https://rmiteduau-my.sharepoint.com/:v:/g/personal/s3914138_rmit_edu_vn/EZLs-5042k1LjcGDjt5cY5kBJUDE7wSiegAtYweY6p-0Ag?e=Rxcr1J 
 
Member:
- Anh Tran - s3914138 - Contribution: 5
- Khang Nguyen - s3802040 - Contribution: 5
- Nguyen Nguyen - s3759957 - Contribution: 5
- Phuc Nguyen - s3819660 - Contribution: 5

Installation
Steps:

  1. Run `database/table.sql` to Create Tables & Views
  2. Run `database/functions.sql` to Create all necessary functions, triggers, and stored procedures
  3. Run `database/data.sql` to Insert all necessary data
  4. Import `database/mongo_data.json` to MongoDB to Insert all necessary custom attributes data for Products
  5. Run `database/auth.sql` to Create & Grant User Roles
  6. Run `php -S 127.0.0.1:8080` to start the PHP Server API
  7. Run `frontend/login.html` with Live Server to start the Frontend Web Client

Demo login info:
All users' password: `myPassword`
- Customer: `customer1`
- Vendor: `vendor1`
- Shipper: `shipper1`

Technologies:
- Databases: MySQL 8, MongoDB 6
- Web application: PHP 8, HTML, CSS, Vanilla Javascript
