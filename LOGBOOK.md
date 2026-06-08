# Logbook – SmartShop E‑Commerce Project

**Student:** Kennedy Karani  
**Admission:** BBIT/2024/56963  
**Course:** BIT3208 – Advanced Web Design  
**Lecturer:** Mr. Nyoro  
**Semester:** 3.2  
**GitHub:** https://github.com/karaniken/smart_ecommerce  

---

## Week 1 – Environment Setup (Arch Linux)

**What I did:**

- I installed Apache, MariaDB, PHP on my Arch system. That part was easy because pacman is nice.
- Then I tried to open localhost and Apache showed me the source code of my PHP file instead of running it. I was stuck for a while at this stepand used chat gpt to troubleshoot.
- I googled and used chat gpt a lot and found out that Arch doesn't enable PHP module by default. 
- I had to manually add `Include conf/extra/php_module.conf` inside `/etc/httpd/conf/httpd.conf`. That fixed it.
- Made a simple `index.html` with "Hello World" and a `test.php` with `<?php phpinfo(); ?>` to confirm PHP works.
- Created a database called `test_db` and wrote a small `mysqli_connect()` script. First time I got connection error because I forgot to start MariaDB with `systemctl start mariadb`. Rookie mistake.
- Installed phpMyAdmin from AUR, then got a 404 when trying to access it. I realized I needed to link the config file – added `Include conf/extra/phpmyadmin.conf` to httpd.conf. Finally worked.
- Took screenshots of everything because Mr. Nyoro asks for proof.

**Screenshots:**  

- Fig1: Apache running on localhost  
- Fig2: HTML "Hello World"  
- Fig3: PHP info page  
- Fig4: DB connection success  
- Fig5: phpMyAdmin login  
- Fig6: phpinfo() details  

**Reflection:**  

Setting up LAMP on Arch was challenging not similar to Ubuntu  – Ubuntu does everything automatically. 
On Arch you have to configure almost everything by hand. 
The php_module.conf trick took me forever to find, until i learned the ctrl+w shortcut for searching lines in scripts on arch linux. Then finally seeing phpinfo() output felt good. 
Screenshots saved in `Week1/screenshots/`. I also created GitHub repo as TASK1.

---

## Week 2 – Wireframes & GUI Mockups

**What I did:**

- I used Figma to draw wireframes. But I'm not a designer so I asked Claude AI to give me a rough idea of how an ecommerce layout should look. 
- Then I tried to copy that in Figma. It's not perfect but okay.
- Made wireframes for homepage, login page, dashboard (after login), and product detail page.
- Then I built a static HTML/CSS homepage. I chose a dark header with orange buttons because I saw a YouTube tutorial with that color scheme – looked modern.
- Tested responsiveness using Chrome dev tools. On my laptop after pressing f12  (375px) the cards stack vertically, which is fine.
- Wrote a short `proposal.md` where I listed features: shopping cart, user login, admin panel to manage products. I kept it simple.

**Screenshots:**  

- Fig1: Homepage wireframe  
- Fig2: Login wireframe  
- Fig3: Dashboard wireframe  
- Fig4: Product page wireframe  
- Fig5: Desktop mockup  
- Fig6: Mobile mockup (375px)  

**Reflection:**  

Wireframes helped me think about where everything goes. But honestly my design is not amazing – the colors might be a bit harsh and should be fixed later as learning contiinues. 
The HTML/CSS took me a while but I reffered from Fundamentals of internet and introduction to web design notes  because I kept messing up flexbox alignment. 
I forgot to make the footer stick to bottom at first. 
Mobile view works but the font is a little small on real phone –sgould be increased  later.

---

## Week 3 – JS Validation & PHP Forms


**What I did:**

- Built a login form with JavaScript validation. I made sure email is not empty and contains '@' symbol.
- For password, I added a strength meter: weak/medium/strong. I used regex for that – copied from StackOverflow because I don't know regex well. 
- It checks length, uppercase, lowercase, numbers.
- Errors show up without page reload (innerHTML stuff).
- Made a `process.php` that just prints the submitted email and password (but I know I should never print password in real life – this was just for testing).
- Tested my database connection again from Week 1 – still works, phew.
- Also made a DOM demo: a textarea where you type and a preview div updates live. Plus two buttons to hide/show the preview. That was actually fun to code.

**Screenshots:**  

- Fig1: Validation errors (empty email, short password)  
- Fig2: Password strength "Strong"  
- Fig3: PHP welcome message after submit  
- Fig4: DB connection success  
- Fig5: Live text preview with hide/show  

**Reflection:**  

JavaScript validation is cool because users don't have to wait for the server to tell them they made a mistake. 
The regex part was annoying – I tried to write my own but it broke everything so I just copied from chat gpt generated one then tweaked a little. 
I still don't fully understand how it works but it does the job. 
The DOM manipulation worked on first try . I'm starting to see how frontend sends data to backend via POST. 
One thing I forgot: I didn't sanitize the output in process.php.

---

## Week 4 – Login System (Sessions & Hashing)

**What I did:**

- Made `register.php` with email, password, confirm password fields.
- Used `password_hash()` to store passwords – I read that storing plain text is stupidly insecure so I did this right.
- Created a `users` table in MariaDB with columns: id, email, password_hash, created_at.
- `login.php` checks if email exists, then verifies with `password_verify()`.
- If correct, starts a session and stores user email in `$_SESSION['user']`.
- `dashboard.php` checks if session exists; if not, redirects to login. This protects the page.
- `logout.php` just destroys session and redirects.
- I tested everything using PHP built-in server (`php -S localhost:8000`) because my Apache is still acting weird sometimes.

**Screenshots:**  

- Fig1: Registration form  
- Fig2: Login form  
- Fig3: Dashboard after login  
- Fig4: Password mismatch error  
- Fig5: Invalid login error  

**Reflection:**  

Password hashing is something I'll always do from now on. It's easy to implement using python and other languages – just one function call. 
Sessions are basically arrays stored on the server, each linked to a cookie on the browser. 
I had a bug where after login it didn't redirect – I forgot to call `session_start()` at the top of dashboard.php. 
Took me a while to figure out. The built-in server saved me because Apache was giving me "permission denied" on session files. I need to fix that later. 
Now I understand how to protect pages by checking session variables.

---

## Week 5 – CRUD on Products

**What I did:**

- Created a `products` table: id, name, description, price, stock_quantity.
- `products.php` lists all products in an HTML table (READ operation). I added a simple while loop with `fetch(PDO::FETCH_ASSOC)`.
- `add_product.php` has a form that inserts a new product (CREATE). Used PDO prepared statements with `?` placeholders.
- `edit_product.php` loads the product data by ID, shows a pre-filled form, and updates on submit (UPDATE).
- `delete_product.php` asks for confirmation with JavaScript popup, then removes the product (DELETE).
- Reused the session check from Week 4 – only logged-in users can add/edit/delete products. Guests can only view the product list.
- I had a huge problem with the UPDATE query – I kept getting syntax error. I asked ChatGPT and it showed me that I forgot the `WHERE id = ?` clause, I made the correction.

**Screenshots:**  

- Fig1: Product list (sample data)  
- Fig2: Add product form  
- Fig3: Edit product form (pre-filled)  
- Fig4: Delete confirmation popup  
- Fig5: Updated list after CRUD  

**Reflection:**  

CRUD is really the heart of any web app. Prepared statements are easy once you understand them – just put `?` where the user input goes, then bind parameters. 
The hardest part was debugging the UPDATE query because the error message wasn't clear. 
I also forgot to redirect back to products.php after adding a new product, so users would see a blank page. 
Fixed that with `header('Location: products.php')`. 
I'm happy that now I can manage products like a real admin panel. 
Next time I want to add search and pagination because the list will get long.

---

**End of logbook**
