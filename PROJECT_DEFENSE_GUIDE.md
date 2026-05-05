# Updated Project Defense Guide: Book Library System

## 1. Professional Architecture (Homework 9 & 10)
*   **Decoupled Logic:** Every major component is now separated for clarity.
    *   `classes/`: Contains the logic (DAOs, Uploader).
    *   `library.php`: Contains global helper functions.
    *   `style.css`: Centralized UI design (Separation of concerns).
*   **Security Constants:** Sensitive keys (like `JWT_SECRET`) are now defined as constants in one central place (`library.php`), making them easier to protect and update.

## 2. Advanced Security (Homework 10)
### Enhanced Login Logic
*   The `login.php` file no longer contains hardcoded secrets or raw DB logic. It delegates the work to `UserDAO.php` and `library.php`.
*   **Session Security:** Sessions are initialized only when needed, and JWT tokens are used to verify user identity without repeated database hits.

### Secure File Upload (Homework 5 & 7)
*   **Fix Implemented:** Added automatic directory creation (`mkdir`) and write-permission checks (`is_writable`) to ensure uploads always work.
*   **Security Layers:** 
    1.  **Extension check** (.jpg, .png).
    2.  **MIME type verification**.
    3.  **Magic Bytes inspection** (reads actual file content to prevent disguised scripts).

## 3. Visuals & UI
*   **External CSS:** All styling moved to `style.css`.
*   **Benefits:** This makes the site faster to load (caching) and ensures the code in your `.php` files is much shorter and easier for the teacher to read during the defense.

## 4. How to Explain Your Improvements:
If asked **"What did you improve since the last version?"**, you should say:
> "I moved the project towards a more professional structure. I separated the CSS into its own file to follow the **Separation of Concerns** principle. I also centralized my security configurations and improved the file uploader with deeper content validation (Magic Bytes) and better server-side error handling to ensure a robust user experience."
