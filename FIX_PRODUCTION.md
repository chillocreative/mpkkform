# Step-by-Step Fix for https://mpkk.borang.click/

## Current Status: ❌ BLANK PAGE

### What's Wrong:
- The site is serving source code files instead of built files
- Node.js application is not running or misconfigured
- Files are in the wrong directory structure

---

## SOLUTION: Complete Redeployment

### Step 1: Clean Up Current Installation

1. Login to **cPanel**
2. Go to **Setup Node.js App**
3. If you see an app for this domain:
   - Click **Stop App**
   - Click **Delete** (or **Destroy**)
4. Go to **File Manager**
5. Navigate to the folder where you uploaded files
6. **Delete all files** in that folder (make a backup if needed)

### Step 2: Upload Fresh Package

1. Download `MPKK-Form-cPanel-Complete.zip` from your project
2. In **File Manager**, navigate to your home directory
3. Create a new folder: `mpkkform` (or any name you prefer)
4. Enter that folder
5. Click **Upload**
6. Upload `MPKK-Form-cPanel-Complete.zip`
7. Select the ZIP file → Click **Extract**
8. Delete the ZIP file after extraction

### Step 3: Verify File Structure

Your folder should now contain:
```
mpkkform/
├── dist/
│   ├── index.html
│   └── assets/
│       ├── index-XDQ89qvN.css
│       └── index-DsGWycC6.js
├── server.js
├── db.js
├── setup_db.js
├── schema.sql
├── package.json
├── .env.example
├── CPANEL_README.md
└── other files...
```

### Step 4: Configure Environment

1. In File Manager, find `.env.example`
2. Right-click → **Rename** to `.env`
3. Right-click `.env` → **Edit**
4. Update with your database info:
   ```env
   DB_HOST=localhost
   DB_USER=your_cpanel_prefix_dbuser
   DB_PASSWORD=your_db_password
   DB_NAME=your_cpanel_prefix_mpkkform
   PORT=3001
   GEMINI_API_KEY=your_api_key
   ```
5. **Save** the file

**Important:** Replace `your_cpanel_prefix` with your actual cPanel username!

### Step 5: Setup Database

1. Go to **MySQL Database Wizard**
2. Create database: `mpkkform`
3. Create user with a strong password
4. Grant **ALL PRIVILEGES**
5. Go to **phpMyAdmin**
6. Select your database
7. Click **Import**
8. Upload `schema.sql` from your folder
9. Click **Go**

### Step 6: Create Node.js Application

1. Go to **Setup Node.js App**
2. Click **Create Application**
3. Fill in:
   - **Node.js version**: 18.x or higher
   - **Application mode**: Production
   - **Application root**: `mpkkform` (the folder you created)
   - **Application URL**: Select your domain `mpkk.borang.click`
   - **Application startup file**: `server.js`
4. Click **Create**

### Step 7: Install Dependencies

1. After creation, you'll see the app details page
2. Scroll down to find **Run NPM Install** button
3. Click it
4. Wait for installation to complete (1-2 minutes)
5. Check for "Success" message

### Step 8: Start Application

1. On the same page, click **Start App**
2. Wait for status to change to "Running"
3. Click the **Open** link to test

### Step 9: Verify It Works

1. Visit https://mpkk.borang.click/
2. You should see the registration form
3. Try filling and submitting the form
4. Check if data appears in the database

---

## If It Still Doesn't Work

### Check Error Logs

1. In **File Manager**, go to your `mpkkform` folder
2. Look for `stderr.log` or `error.log`
3. Open it to see error messages
4. Common errors:
   - **Database connection failed**: Check `.env` credentials
   - **Port already in use**: cPanel will assign a port automatically
   - **Module not found**: Run NPM Install again

### Alternative: Static Deployment (No Database)

If Node.js doesn't work, you can deploy just the frontend:

1. Go to **File Manager**
2. Navigate to `public_html` (or your domain's root)
3. Delete everything in it
4. Go to your `mpkkform/dist` folder
5. Select all files (index.html and assets folder)
6. Click **Copy**
7. Navigate to `public_html`
8. Click **Paste**
9. Visit your site

**Note:** This will show the form but won't save data to database.

---

## Need Help?

1. Check `stderr.log` in your application folder
2. Verify `.env` has correct database credentials
3. Ensure database user has all privileges
4. Make sure Node.js version is 18 or higher
5. Contact your hosting support if Node.js features are not available

---

## Quick Checklist

- [ ] Old files deleted
- [ ] New package uploaded and extracted
- [ ] `.env` file configured
- [ ] Database created
- [ ] `schema.sql` imported
- [ ] Node.js app created
- [ ] NPM Install completed
- [ ] App started and running
- [ ] Site accessible and working

---

**Follow these steps carefully and your site will work!** 🚀
