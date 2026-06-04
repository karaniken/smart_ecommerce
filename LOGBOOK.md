# Comprehensive Logbook – Weeks 1 to 5

**Student:** Kennedy Karani  
**Registration:** BBIT/2024/56963  
**Unit:** BIT3208 – Advanced Web Design and Development  

This document combines the weekly logs for Weeks 1 through 5. Each week includes step‑by‑step actions, screenshots, and reflections.



## Week 1 – Local Environment Setup (Arch Linux)


## Objective
Setingt up a working LAMP stack (Linux, Apache, MariaDB, PHP) on my Arch Linux machine, test it with simple HTML and PHP files, connect to a database, install phpMyAdmin, and document everything with screenshots in a GitHub repository.

---

## STEP_BY_STEP PLAN (with explanations)


### 1. Installing Apache, MariaDB, and PHP


I opened a terminal and ran:


```bash
sudo pacman -Syu
This updates all packages on Arch Linux. Then I installed the required software:

bash
sudo pacman -S apache mariadb php php-apache
apache – the web server.

mariadb – the database (MySQL replacement).

php – the PHP language.

php-apache – the module that lets Apache run PHP code.

Then I started both services and enabled them to start automatically on boot:

bash
sudo systemctl start httpd mariadb
sudo systemctl enable httpd mariadb
What I learned: httpd is the Apache service name on Arch. mariadb is the database service.

2. Securing MariaDB (setting no password)
I ran the security script:

bash
sudo mysql_secure_installation
It asked several questions. I pressed Enter for each one, which means I chose no root password. This is safe for local development only. On a real server I would set a strong password.

3. Configuring Apache to work with PHP (and fixing the error)
I edited the main Apache configuration file:

bash
sudo nano /etc/httpd/conf/httpd.conf
What I tried first (and why it failed):
I manually added the line LoadModule php_module modules/libphp.so inside the file, near other LoadModule lines. Then I restarted Apache:

bash
sudo systemctl restart httpd
But I got this error:

text
httpd: Syntax error: Cannot load modules/libphp.so into server: No such file or directory
Why this happened:
On Arch Linux, the php-apache package does not put libphp.so directly in /etc/httpd/modules/. Instead, it provides a configuration file at /etc/httpd/conf/extra/php_module.conf that contains the correct LoadModule line and the handler for .php files.

How I fixed it:
I opened httpd.conf again and removed my manual LoadModule line. Then I went to the very bottom of the file and added:

apache
Include conf/extra/php_module.conf
I saved the file (Ctrl+O, Enter, Ctrl+X) and restarted Apache again:

bash
sudo systemctl restart httpd
This time it worked. I tested by opening http://localhost in my browser – I saw the Apache default page.

Fig 1 – Apache running successfully
https://screenshots/fig1-apache-running.png
Fig 1: Apache default page at http://localhost – confirms the web server is working.

4. Creating Hello World files
Inside my project folder /srv/http/smart_ecommerce/Week1/code/, I created two files.

index.html – a simple HTML page:

html
<!DOCTYPE html>
<html>
<head><title>Week 1 - HTML Hello World</title></head>
<body>
    <h1>Hello World from HTML</h1>
    <p>My local environment is working on Arch Linux.</p>
</body>
</html>
test.php – a PHP page that shows PHP info:

php
<?php
echo "<h1>Hello World from PHP</h1>";
echo "<p>PHP is working correctly.</p>";
phpinfo();
?>
I opened these in my browser:

http://localhost/smart_ecommerce/Week1/code/index.html

http://localhost/smart_ecommerce/Week1/code/test.php

Fig 2 – HTML page
https://screenshots/fig2-hello-html.png
Fig 2: index.html displayed in browser.

Fig 3 – PHP page (top part)
https://screenshots/fig3-hello-php.png
Fig 3: test.php showing the PHP output.

Fig 6 – The phpinfo() details (scrolled down)
https://screenshots/fig6-php-info.png
Fig 6: phpinfo() confirms PHP version 8.5.6 and loaded extensions.

5. Testing database connection from PHP
First, I created a test database in MariaDB. I logged in:

bash
mysql -u root -p
(It asked for a password – I just pressed Enter because I set no password.)

Inside the MySQL prompt, I ran:

sql
CREATE DATABASE test_db;
EXIT;
Then I created db_test.php inside Week1/code/ with this content:

php
<?php
$conn = mysqli_connect("localhost", "root", "", "test_db");
if (!$conn) {
    die("<p style='color:red'>Connection failed: " . mysqli_connect_error() . "</p>");
}
echo "<h2 style='color:green'>Connected successfully to MariaDB!</h2>";
mysqli_close($conn);
?>
I visited http://localhost/smart_ecommerce/Week1/code/db_test.php in my browser.

Fig 4 – Green success message
https://screenshots/fig4-db-connect-success.png
Fig 4: The script shows "Connected successfully to MariaDB!" – this proves PHP can talk to the database.

6. Installing phpMyAdmin (and fixing the 404 error)
I installed phpMyAdmin:

bash
sudo pacman -S phpmyadmin
When I tried to visit http://localhost/phpmyadmin, I got a 404 Not Found error.

Why this happened:
phpMyAdmin was installed, but Apache did not know how to serve it. I needed to create an alias configuration.

How I fixed it:
I created a new Apache configuration file:

bash
sudo nano /etc/httpd/conf/extra/phpmyadmin.conf
I pasted this content:

apache
Alias /phpmyadmin "/usr/share/webapps/phpMyAdmin"
<Directory "/usr/share/webapps/phpMyAdmin">
    DirectoryIndex index.php
    AllowOverride All
    Options FollowSymlinks
    Require all granted
</Directory>
Then I included this file in the main httpd.conf by adding this line at the very bottom:

apache
Include conf/extra/phpmyadmin.conf
I also edited /etc/php/php.ini to uncomment (remove the ; at the beginning) these lines:

ini
extension=iconv
extension=bz2
extension=mysqli
extension=session
Then I set a blowfish secret in /etc/webapps/phpmyadmin/config.inc.php. I opened the file and changed the line to:

php
$cfg['blowfish_secret'] = 'kennedy_karani_week1_secret_12345';
Finally, I restarted Apache:

bash
sudo systemctl restart httpd
After that, http://localhost/phpmyadmin loaded the login page.

Fig 5 – phpMyAdmin login page
https://screenshots/fig5-phpmyadmin.png
Fig 5: phpMyAdmin login screen – now working correctly.

7. Moving screenshots into the project folder
All my screenshots were originally in ~/Downloads/ADVANCED WEB DESIGN./Week1/screenshots/. I copied them to the correct project folder:

bash
cp "/home/scorpion/Downloads/ADVANCED WEB DESIGN./Week1/screenshots/"*.png /srv/http/smart_ecommerce/Week1/screenshots/
Then I fixed file ownership so I could edit files without sudo:

bash
sudo chown -R scorpion:scorpion /srv/http/smart_ecommerce
I verified the files were there:

bash
ls /srv/http/smart_ecommerce/Week1/screenshots/
I saw all the fig*.png files.

8. Pushing everything to GitHub
I initialised a Git repository, added all files, committed, and pushed to my GitHub account:

bash
cd /srv/http/smart_ecommerce
git init -b main
git add .
git commit -m "Week 1 complete: LAMP stack, Hello World, DB test, phpMyAdmin, screenshots, logbook"
git remote add origin https://github.com/kennedykarani/smart_ecommerce.git
git push -u origin main
Now my lecturer can see all my work at https://github.com/kennedykarani/smart_ecommerce.

Reflection (100 words)
This week I successfully set up a full LAMP stack on Arch Linux. The main challenge was configuring Apache to work with PHP – I learned that php-apache must be installed and that the module configuration is best handled by the system's php_module.conf file, not by manually adding LoadModule. Another issue was phpMyAdmin giving a 404; creating the proper Apache alias and enabling the required PHP extensions (iconv, bz2, mysqli, session) solved it. The database connection test proved that PHP can communicate with MariaDB even with an empty password (safe for local development). I also learned how to structure a GitHub repository with weekly folders, code, and labelled screenshots. This logbook demonstrates my progress, troubleshooting ability, and understanding of the development environment.

How I inserted screenshots in an orderly way
For each major step, I:

Took a screenshot using Flameshot (or the PrintScreen key).

Saved the file with a clear name: fig1-apache-running.png, fig2-hello-html.png, etc.

Copied all screenshots into Week1/screenshots/.

In this README.md, I used the Markdown syntax:
![Description](screenshots/filename.png)
followed by a caption line: *Fig X: Explanation of what the screenshot shows.*

I numbered the figures sequentially and referenced them in the text.

This makes it easy for the lecturer to match each screenshot to the corresponding step.

text

---

## ✅ What to do now

1. **Open the file** in your editor:
   ```bash
   nano /srv/http/smart_ecommerce/Week1/README.md


## Week 2 – UI/UX and Design Foundations


## Objective
Design wireframes and GUI mockups for the Smart E-Commerce web application, plan the project proposal, and document everything with screenshots.

## Step-by-Step Actions

### 1. Wireframes
I used claude running as part of figma make - a web application builder that lets you create React + Tailwind apps through conversation (online)
to sketch four key screens:

- **Homepage** – product grid, navigation.  
  ![Homepage wireframe](screenshots/fig1-wireframe-home.png)  
  *Fig 1: Wireframe of the homepage showing product cards and top navigation.*

- **Login page** – email and password fields, register link.  
  ![Login wireframe](screenshots/fig2-wireframe-login.png)  
  *Fig 2: Wireframe of the login screen.*

- **Dashboard** – welcome message, order history link, account settings.  
  ![Dashboard wireframe](screenshots/fig3-wireframe-dashboard.png)  
  *Fig 3: Wireframe of the customer dashboard.*

- **Product page** – product image, description, price, add-to-cart button.  
  ![Product page wireframe](screenshots/fig4-wireframe-product.png)  
  *Fig 4: Wireframe of the single product view.*

### 2. GUI Mockup (HTML/CSS)
I built a responsive homepage mockup using HTML and CSS (no backend). The design uses a dark blue header, orange buttons, and a flexible product grid. I tested it on desktop and mobile.

**Desktop view**  
![Desktop mockup](screenshots/fig5-gui-mockup.png)  
*Fig 5: GUI mockup of the homepage on a desktop screen.*

**Mobile responsive view** (using browser dev tools)  
![Mobile view](screenshots/fig6-mobile-view.png)  
*Fig 6: Same page rendered on a 375px wide screen – cards stack vertically.*

### 3. Project Proposal

I wrote `proposal.md` (see in this folder) summarizing the theme, features, technologies, and planned folder structure.

### 4. Technologies Selected

- PHP + MySQL (MariaDB) as backend.
- Bootstrap 5 for responsive design.
- Git/GitHub for version control.

## Reflection (100 words)

This 3 hours I focused on planning the user experience before coding.

1.I learned that wireframes help visualise the layout and user flow without getting lost in technical details. 

2.I used figma + claude ai to create simple but clear wireframes for the homepage, login, dashboard, and product page.

3.The HTML/CSS mockup gave me a chance to test colour schemes and responsiveness – I chose a dark header with orange call-to-action buttons for contrast. 

4.I also wrote a project proposal to clarify the scope. All designs are saved in the `Week2/screenshots/` folder and referenced in this logbook.


## Week 3 – JavaScript & Basic PHP Foundations


## Objective


Implementing client-side form validation with JavaScript, DOM manipulation, and basic PHP form processing.

## Step-by-Step Actions

### 1. JavaScript Form Validation


I Created `login.html` with:

- Email validation (non-empty and proper format).
- Password strength checker (weak <6, medium 6-7 with letters, strong >=8 with uppercase+number).
- Real-time error messages.

**Fig 1** – Validation errors
  
![Validation errors](screenshots/fig1-js-validation.png)  
*Fig 1: Empty email and short password show red error messages.*

**Fig 2** – Password strength indicator
  
![Password strength](screenshots/fig2-password-strength.png)  
*Fig 2: "Strong password" appears when criteria met.*

### 2. PHP Form Processing


Created `process.php` to receive POST data and display a welcome message.

**Fig 3** – PHP output after form submission
  
![PHP processor](screenshots/fig3-php-form-processor.png)  
*Fig 3: Shows submitted email and password length.*

### 3. Database Connection Test


Copied `db_test.php` from Week 1 and tested again.

**Fig 4** – Connection success
  
![DB connection](screenshots/fig4-db-connection.png)  
*Fig 4: Confirms PHP still connects to MariaDB.*

### 4. DOM Manipulation


Created `dom_demo.html` with live text preview and hide/show/toggle buttons.

**Fig 5** – Live preview
  
![DOM dynamic](screenshots/fig5-dom-dynamic.png)  
*Fig 5: Typed text appears instantly in preview div.*

## Reflection

This week I combined frontend JavaScript with backend PHP basics. I learned how to validate forms before submission to improve user experience. 
The password strength checker uses regex to evaluate complexity. On the PHP side, I processed the form data and displayed a simple welcome message. 
The DOM manipulation exercise showed how to change page content without reloading – useful for interactive interfaces.
All files are in `Week3/code/` and screenshots are labelled. Next week I will build a complete login system with sessions.


## Week 4 – Server‑Side Authentication


## Objective

Implementing user registration and login with secure password hashing, session management, and protected pages.

## Step-by-Step Actions

### 1. Database Setup

Created `users` table with `email` and `hashed password` columns.

### 2. Registration Page (`register.php`)

- Takes email, password, confirmation.
- Validates input, hashes password with `password_hash()`.
- Inserts into database.

**Fig 1** – Registration form
  
![Register](screenshots/fig1-register.png)


**Fig 4** – Validation error  

![Register error](screenshots/fig4-validation-error.png)


### 3. Login Page (`login.php`)

- Verifies email and password using `password_verify()`.
- Starts session on success.

**Fig 2** – Login form  

![Login](screenshots/fig2-login.png)

**Fig 5** – Login error  

![Login error](screenshots/fig5-login-error.png)

### 4. Protected Dashboard

Checks session; redirects to login if not authenticated.

**Fig 3** – Dashboard (after login)  

![Dashboard](screenshots/fig3-dashboard.png)

### 5. Logout

Destroys session and redirects to login.

## Reflection

This week I built a secure authentication system using PHP sessions and password hashing. 
I learned that passwords should never be stored in plain text – `password_hash()` and `password_verify()` make it 
easy to implement. 

The registration page includes client‑side and server‑side validation. 
The dashboard page checks for a valid session before granting access. 
Using PHP’s built‑in server for testing was safe and avoided Apache configuration issues. Next week I will add full 
CRUD operations.


## Week 5 – CRUD Operations (Products)


## Objective

Implement full CRUD (Create, Read, Update, Delete) for a product catalog using PHP and MySQL (PDO). Integrate with the existing authentication system from Week 4.

## Step-by-Step Actions

### 1. Database Setup

Created a `products` table in `test_db` with columns: id, name, description, price, stock, created_at. Inserted four sample products.

### 2. Product Listing (Read)

`products.php` fetches all products and displays them in an HTML table with Edit and Delete links.  

**Fig 1** – Product list  
![Products list](screenshots/fig1-products-list.png)


### 3. Add Product (Create)

`add_product.php` contains a form. On submission, it inserts a new record using a PDO prepared statement.  

**Fig 2** – Add product form  
![Add product](screenshots/fig2-add-product.png)


### 4. Edit Product (Update)

`edit_product.php` loads the existing product data into a form. After submission, it updates the record.  

**Fig 3** – Edit product  
![Edit product](screenshots/fig3-edit-product.png)

### 5. Delete Product (Delete)

`delete_product.php` receives an ID, runs a DELETE query, and redirects back to the product list. A JavaScript confirmation dialog prevents accidental deletion.  

**Fig 4** – Delete confirmation  
![Delete confirmation](screenshots/fig4-delete-confirmation.png)


### 6. Final CRUD Success
After adding, editing, and deleting, the product list reflects all changes.  

**Fig 5** – Updated product list  
![CRUD complete](screenshots/fig5-crud-complete.png)

## Reflection (100 words)

This week I built a complete CRUD system for managing products. I used PDO with prepared statements to prevent SQL injection. 
The authentication from Week 4 was integrated so only logged‑in users can access the product pages. 
The process of adding, editing, and deleting records is the backbone of any data‑driven web application. 
I also improved the user interface with simple CSS. All code is in the `code/` folder and screenshots are labelled. 

Next, I will focus on enhancing the frontend with search and pagination.


---
*End of logbook – all weeks 1‑5 are included.*
