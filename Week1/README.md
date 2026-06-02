# Week 1 – Local Environment Setup (Arch Linux)

** KENNEDY KARANI  
**Registration:** BBIT/2024/56963  
**Date:** 2026-06-02

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
