# Deployment Guide for cPanel

This application is a **Node.js** web application (React Frontend + Express Backend with MySQL).

## Prerequisites
- cPanel hosting with Node.js support (v18 or later)
- MySQL database access
- SSH access (optional, but recommended)

---

## 1. Prepare Database

1. Log in to **cPanel**
2. Go to **MySQL Database Wizard**
3. Create a new database:
   - Database name: `mpkkform` (or `username_mpkkform` if cPanel adds prefix)
4. Create a database user:
   - Username: `mpkk_admin` (or similar)
   - Password: Create a strong password
5. Add user to database with **All Privileges**
6. Go to **phpMyAdmin**
7. Select your new database
8. Click **Import** tab
9. Upload `schema.sql` from this project

**Note:** Keep your database name, username, and password - you'll need them later.

---

## 2. Build Production Files

**On your local machine**, run:

```bash
npm run build
```

This creates an optimized `dist` folder with your frontend files.

---

## 3. Prepare Deployment Package

Create a folder with these files ONLY:

```
deployment-package/
├── dist/              (entire folder from build)
├── server.js
├── db.js
├── package.json
├── schema.sql
└── .env.example       (rename to .env after upload)
```

**DO NOT INCLUDE:**
- `node_modules/`
- `src/` or any source files
- `.git/`
- Development files

Create a `.env.example` file with this content:
```
DB_HOST=localhost
DB_USER=your_db_user
DB_PASSWORD=your_db_password
DB_NAME=your_db_name
PORT=3001
GEMINI_API_KEY=your_gemini_api_key
```

Compress this folder as `mpkkform.zip`

---

## 4. Upload to cPanel

1. Log in to **cPanel**
2. Open **File Manager**
3. Navigate to your home directory (usually `/home/username/`)
4. Create a new folder: `mpkkform`
5. Enter the `mpkkform` folder
6. Click **Upload** and upload `mpkkform.zip`
7. Select the ZIP file and click **Extract**
8. Delete the ZIP file after extraction

---

## 5. Configure Environment Variables

1. In File Manager, navigate to your `mpkkform` folder
2. Rename `.env.example` to `.env`
3. Right-click `.env` and select **Edit**
4. Update with your actual database credentials:
   ```
   DB_HOST=localhost
   DB_USER=your_actual_db_user
   DB_PASSWORD=your_actual_db_password
   DB_NAME=your_actual_db_name
   PORT=3001
   GEMINI_API_KEY=your_actual_api_key
   ```
5. Save the file

---

## 6. Setup Node.js Application

1. In cPanel, go to **Setup Node.js App**
2. Click **Create Application**
3. Configure:
   - **Node.js Version**: 18.x or later
   - **Application Mode**: Production
   - **Application Root**: `mpkkform`
   - **Application URL**: Choose your domain or subdomain
   - **Application Startup File**: `server.js`
4. Click **Create**

---

## 7. Install Dependencies

1. After creating the app, you'll see the app details page
2. Scroll down to find the **Run NPM Install** button
3. Click it and wait for installation to complete
4. This will install all required packages from `package.json`

---

## 8. Start the Application

1. On the Node.js App page, click **Start App**
2. Wait for the status to show "Running"
3. Click the **Open** link to view your application
4. Test the registration form and list page

---

## 9. Setup Database (First Time Only)

If you haven't imported the schema yet:

1. SSH into your server (or use cPanel Terminal)
2. Navigate to your app folder:
   ```bash
   cd ~/mpkkform
   ```
3. Run the setup script:
   ```bash
   node setup_db.js
   ```

This will create the database and tables automatically.

---

## Troubleshooting

### Application won't start
- Check `stderr.log` in your application folder
- Verify `.env` file has correct database credentials
- Ensure Node.js version is 18 or higher

### Blank page or 404 errors
- Verify `dist` folder was uploaded correctly
- Check that `server.js` is serving static files from `dist`
- Clear browser cache

### Database connection errors
- Verify database name, user, and password in `.env`
- Check that database user has all privileges
- Ensure `schema.sql` was imported successfully

### Port conflicts
- cPanel usually manages ports automatically
- If needed, change PORT in `.env` to an available port

---

## Updating the Application

To update your application after making changes:

1. Build locally: `npm run build`
2. Upload new `dist` folder to cPanel (overwrite existing)
3. If you changed backend code, upload new `server.js` or `db.js`
4. In cPanel Node.js App, click **Restart**

---

## Important Notes

- **Security**: Never commit `.env` file to Git
- **Backups**: Regularly backup your database via phpMyAdmin
- **Monitoring**: Check application logs regularly in cPanel
- **SSL**: Enable SSL certificate for your domain in cPanel for HTTPS

---

## Support

For issues specific to:
- **cPanel**: Contact your hosting provider
- **Application**: Check the GitHub repository
- **Database**: Use phpMyAdmin or contact hosting support
