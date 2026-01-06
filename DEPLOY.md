# Deployment Guide for cPanel

This application is a **Node.js** web application (React Frontend + Express Backend).

## 1. Prepare Database
1. Log in to cPanel.
2. Go to **MySQL Database Wizard**.
3. Create a new database (e.g., `username_mpkk_db`).
4. Create a new user (e.g., `username_admin`) and password.
5. Add user to database with **All Privileges**.
6. Go to **phpMyAdmin**, select the new database.
7. Click **Import** and upload `schema.sql` from this project folder.

## 2. Prepare Files
1. Run `npm run build` locally to generate the `dist` folder.
2. Create a ZIP file containing:
   - `dist/` folder
   - `server.js`
   - `db.js`
   - `package.json`
   - `.env` (you will edit this later)
   
   *Do NOT include `node_modules`.*

## 3. Upload to cPanel
1. Go to **File Manager** in cPanel.
2. Create a folder (e.g., `mpkk-form`).
3. Upload and extract your ZIP file there.

## 4. Configure Node.js App
1. Go to **Setup Node.js App** in cPanel.
2. Click **Create Application**.
3. **Node.js Version**: Select 18.x or later.
4. **Application Mode**: Production.
5. **Application Root**: `mpkk-form` (the folder you created).
6. **Application Startup File**: `server.js`
7. Click **Create**.
8. Once created, click **Stop App** (if running).

## 5. Install Dependencies
1. Scroll down to "Detected configuration file" (package.json).
2. Click **Run NPM Install**.

## 6. Configure Environment
1. In the Node.js App settings (or by editing `.env` file via File Manager):
2. Update the `.env` file with your database credentials:
   ```
   DB_HOST=localhost
   DB_USER=username_admin
   DB_PASSWORD=your_password
   DB_NAME=username_mpkk_db
   PORT=3000 (managed by cPanel usually, but good to have)
   GEMINI_API_KEY=your_key
   ```
   
## 7. Start Application
1. Click **Start App**.
2. Click **Open** to verify it works.

## Troubleshooting
- If you see a blank page, ensure `dist/index.html` is being served correctly.
- Check `stderr.log` in the application root for errors.
