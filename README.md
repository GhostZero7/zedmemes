# ZedMemes - Zambia's Premier Meme Hub

ZedMemes is a full-featured meme-sharing web app built with PHP, MySQL, and TailwindCSS. Users can register, log in, upload memes, react, comment, and browse memes in an engaging, mobile-optimized interface with dark/light mode support.

![ZedMemes Screenshot](screenshot.png)

---

## ðŸŒŸ Features

- **User Registration & Login**
- **Upload Memes** (images only)
- **Like & Upvote Memes**
- **Comment on Memes**
- **Infinite Scroll + Load More**
- **Dark Mode / Light Mode Toggle**
- **Fully Responsive UI**
- **Secure Session-Based Auth**
- **TailwindCSS-powered design**

---

## Folder Structure

```
zedmemes/
index.php              # Main frontend file (HTML+PHP)
db.php                 # Database connection using PDO
register.php           # Handles user signup
login.php              # Handles login and sets session
logout.php             # Destroys session
upload.php             # Handles meme image uploads
fetch_memes.php        # Returns memes as JSON (paginated)
react.php              # Handles like/upvote
add_comment.php        # Add a comment to a meme
fetch_comments.php     # Get comments for a meme
uploads/               # Uploaded meme images
README.md              # This file
```

---

## Setup Instructions

### 1. Requirements
- PHP 7.4+ (with PDO)
- MySQL or MariaDB
- XAMPP/WAMP/LAMP (or similar)
- Composer (optional)

### 2. Clone the Project

```bash
git clone https://github.com/GhostZero7/zedmemes.git
cd zedmemes
```

### 3. Import the Database

1. Open **phpMyAdmin** (or use MySQL CLI)
2. Create a database called `zedmemes`
3. Import the provided SQL dump:
   ```
   zedmemes.sql
   ```

### 4. Configure Database (if needed)

Check `db.php` and update credentials if different:

```php
$host = '127.0.0.1';
$db   = 'zedmemes';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
```

> For XAMPP, default is `root` with no password and port 3307.

### 5. Create Uploads Directory if missing

```bash
mkdir uploads
chmod 755 uploads
```

### 6. Start the Server

If using PHP's built-in server:

```bash
php -S localhost:8000
```

Open in your browser:

```
http://localhost:8000/index.php
```

---

## Security Tips

- Passwords are hashed using `password_hash()`
- Sessions secured via `session_start()` and `$_SESSION`
- SQL is parameterized to prevent injection
- Basic error suppression in production

---

## Screenshots

![Dark Mode](screenshots/dark-mode.png)
![Upload Modal](screenshots/upload.png)
![Mobile View](screenshots/mobile.png)

---

## Acknowledgments

- [TailwindCSS](https://tailwindcss.com)
- [Font Awesome](https://fontawesome.com)
- [PHP.net](https://www.php.net)

---

## Feedback

Spotted a bug? Have suggestions? [Open an issue](https://github.com/GhostZero7/zedmemes/issues) or submit a PR. Let's build the Zambian meme community together!
