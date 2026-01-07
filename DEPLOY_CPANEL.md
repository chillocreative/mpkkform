# Deploying MPKKform to cPanel

Follow these steps to deploy the MPKK Attendance Form to your cPanel hosting.

## 1. Prepare Files
1.  Ensure you have the latest zip file (or create one containing all files in this folder **except** `.git`, `.env`, and `node_modules` if any).
2.  **Recommended**: Rename the folder or zip file to something simple like `attendance`.

## 2. Upload to cPanel
1.  Log in to **cPanel**.
2.  Go to **File Manager**.
3.  Navigate to `public_html`.
4.  Create a new folder (e.g., `kehadiran`) or upload directly to `public_html` if this is the main site.
5.  **Upload** the zip file and **Extract** it.

## 3. Database Setup
1.  Go to **MySQL® Database Wizard** in cPanel.
2.  **Step 1**: Create a new database (e.g., `myuser_mpkk`).
3.  **Step 2**: Create a new user (e.g., `myuser_admin`) and generate a strong password. **COPY THIS PASSWORD**.
4.  **Step 3**: Assign the user to the database and check **ALL PRIVILEGES**.

## 4. Import Database Schema
1.  Go to **phpMyAdmin** in cPanel.
2.  Click on your new database on the left sidebar (`myuser_mpkk`).
3.  Click the **Import** tab.
4.  Choose the file `database_schema.sql` (included in the upload).
5.  Click **Go**.

## 5. Configure Application
1.  Go back to **File Manager**.
2.  Find the `database.php` file on your server.
3.  Right-click and select **Edit**.
4.  Update the lines at the top with your new credentials:
    ```php
    $host = 'localhost';
    $dbname = 'myuser_mpkk';    // Your new DB name
    $username = 'myuser_admin'; // Your new DB user
    $password = 'YourPassword'; // The password you copied
    ```
5.  Save changes.

## 6. Security (IMPORTANT)
The `results.php` and `delete_bulk.php` pages are **publicly accessible** by default. To prevent unauthorized access:

### Option A: Directory Privacy (Recommended)
1.  In cPanel, go to **Directory Privacy**.
2.  Select `public_html` (or the folder where you installed the app).
3.  Check "Password protect this directory".
4.  Create a user (e.g., `admin`) and password.
5.  Now, the entire app (or specific folder) will require a login.

### Option B: Separate Admin Folder
Move `results.php`, `export.php`, and `delete_bulk.php` into a subfolder (e.g., `admin/`) and protect just that folder using Directory Privacy. Note: You will need to update the `require '../database.php'` path in those files if you move them.

## 7. Verify
Visit your website (e.g., `www.yourdomain.com/kehadiran`) and try to submit a form.
