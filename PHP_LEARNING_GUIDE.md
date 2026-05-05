# PHP Learning & Defense Guide

This guide explains how every file in the **Book Library** project works and how they are connected.

## 1. Project Map: Who calls Who?

### The "Core" Connection
Almost every file starts with `require_once "library.php"`. 
- **Why?** This ensures that common functions (like navigation) and security constants (like `JWT_SECRET`) are available everywhere.

### The "OOP" Chain
1. **The Controller (e.g., login.php)** 
   - Receives data from the user (`$_POST`).
   - Creates a **DAO Object** (`$userDAO = new UserDAO()`).
2. **The DAO (e.g., UserDAO.php)**
   - Takes the data and prepares a SQL query.
   - Creates a **Database Object** to talk to MySQL.
3. **The Database (Database.php)**
   - Opens the secure PDO tunnel to the MySQL server.

---

## 2. File-by-File Breakdown

### `library.php` (Global Helpers)
- **`render_nav()`**:
  - *PHP Concept:* `if/else` logic + Session handling.
  - *Connection:* Called in the HTML part of every page to show the menu.
- **`createJWT()` / `verifyJWT()`**:
  - *PHP Concept:* Data encoding and Encryption.
  - *Connection:* `login.php` calls `createJWT` to sign you in. `profile.php` calls `verifyJWT` to make sure you're still "allowed" in.
- **`showStars($rating)`**:
  - *PHP Concept:* `for` loops.
  - *Connection:* Used in `index.php` to turn a number (like 5) into stars (*****).

### `classes/Database.php` (The Connection)
- **`__construct()`**: 
  - *PHP Concept:* Magic methods.
  - *Connection:* Sets up the PDO connection string (DSN). It's the "foundation" of every database action.

### `classes/UserDAO.php` (Security Logic)
- **`login($email, $password)`**:
  - *PHP Concept:* `password_verify()`.
  - *Connection:* It pulls the hashed password from the DB and compares it to what the user typed.
- **`create($name, $email, $password)`**:
  - *PHP Concept:* `password_hash()`.
  - *Connection:* Called by `register.php`. It scrambles the password before it ever touches the database.

### `classes/Uploader.php` (File Security)
- **`upload($file)`**:
  - *PHP Concept:* File system functions (`fopen`, `fread`, `move_uploaded_file`).
  - *Connection:* Validates that the file is a real image (using Magic Bytes) and moves it to the `uploads/` folder.

---

## 3. How to Learn from the Code

### How to display a list (Looping)
Look at `index.php`. Find the `foreach ($displayBooks as $book)` line.
- **Explanation:** PHP takes the "Array" of books and "loops" through them one by one, creating a new HTML "card" for each book.

### How to protect a page (Authorization)
Look at `manage_books.php` (at the top).
- **Explanation:** 
  1. It checks if the `$_SESSION['token']` exists.
  2. It uses `verifyJWT` to decode the token.
  3. It checks if `$userData['role'] === 'admin'`.
  4. If any of these fail, it stops the script (`die()`) or redirects.

### How to prevent Hacking (SQL Injection)
Look at any query in `BookDAO.php`. 
- **Example:** `SELECT * FROM books WHERE id = ?`
- **Explanation:** The `?` is a placeholder. By using `prepare()` and `execute()`, the user's input is never mixed with the SQL command. This is the #1 rule of web security.

---

## 4. Key Terms for your Defense
- **PDO:** PHP Data Objects (Secure DB connection).
- **DAO:** Data Access Object (The class that handles SQL).
- **JWT:** JSON Web Token (The secure "ID card" for the user).
- **Stateless:** A system (like JWT) that doesn't need to ask the database "Who is this?" on every single page load.
- **Hashing:** Turning a password into a scrambled string that cannot be reversed (Bcrypt).
