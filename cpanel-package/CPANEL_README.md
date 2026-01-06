# ========================================
# MPKK FORM - CPANEL DEPLOYMENT PACKAGE
# ========================================

## 📦 Package Contents

This is a complete, production-ready package for deploying the MPKK Registration Form to cPanel.

### Files Included:
- `dist/` - Production frontend (React app)
- `server.js` - Express backend server
- `db.js` - MySQL database connection
- `setup_db.js` - Database initialization script
- `schema.sql` - Database schema
- `package.json` - Production dependencies
- `.env.example` - Environment configuration template
- `setup.sh` - Automated setup script (optional)

---

## 🚀 QUICK START GUIDE

### Step 1: Create Database in cPanel

1. Login to **cPanel**
2. Go to **MySQL Database Wizard**
3. Create database: `mpkkform` (note the full name with prefix)
4. Create user with strong password
5. Grant **ALL PRIVILEGES** to user
6. Go to **phpMyAdmin** → Select your database → Import → Upload `schema.sql`

### Step 2: Upload Files to cPanel

1. Go to **File Manager** in cPanel
2. Navigate to your home directory
3. Create folder: `mpkkform`
4. Upload this entire package (or ZIP file)
5. Extract if uploaded as ZIP

### Step 3: Configure Environment

1. In File Manager, go to `mpkkform` folder
2. Rename `.env.example` to `.env`
3. Edit `.env` with your credentials:

```env
DB_HOST=localhost
DB_USER=cpanel_username_dbuser
DB_PASSWORD=your_database_password
DB_NAME=cpanel_username_mpkkform
PORT=3001
GEMINI_API_KEY=your_api_key_here
```

**Important:** Replace `cpanel_username` with your actual cPanel username prefix!

### Step 4: Setup Node.js Application

1. In cPanel, go to **Setup Node.js App**
2. Click **Create Application**
3. Configure:
   - **Node.js Version:** 18.x or higher
   - **Application Mode:** Production
   - **Application Root:** `mpkkform`
   - **Application URL:** Your domain/subdomain
   - **Application Startup File:** `server.js`
4. Click **Create**

### Step 5: Install Dependencies

1. After app creation, scroll to **Detected configuration file**
2. Click **Run NPM Install**
3. Wait for completion (may take 1-2 minutes)

### Step 6: Start Application

1. Click **Start App** button
2. Wait for status to show "Running"
3. Click **Open** to test your application

---

## ✅ VERIFICATION CHECKLIST

After deployment, verify:

- [ ] Database created and schema imported
- [ ] `.env` file configured with correct credentials
- [ ] Node.js app created in cPanel
- [ ] Dependencies installed successfully
- [ ] Application status shows "Running"
- [ ] Can access the registration form
- [ ] Can submit a test registration
- [ ] Can view the list page (`/senarai`)
- [ ] Data appears in database (check phpMyAdmin)

---

## 🔧 TROUBLESHOOTING

### Application won't start
**Check:**
- `stderr.log` in application folder for errors
- Database credentials in `.env` are correct
- Database user has all privileges
- Node.js version is 18 or higher

**Fix:**
```bash
# Via SSH or Terminal in cPanel
cd ~/mpkkform
cat stderr.log  # Check for errors
```

### Blank page or 404
**Check:**
- `dist` folder exists and contains files
- `dist/index.html` exists
- Browser console for errors

**Fix:**
- Re-upload `dist` folder
- Clear browser cache
- Restart Node.js app

### Database connection error
**Check:**
- Database name includes cPanel prefix
- User has privileges on database
- `.env` credentials match cPanel database

**Fix:**
```bash
# Test database connection
cd ~/mpkkform
node -e "require('./db.js').then(pool => pool.query('SELECT 1')).then(() => console.log('✅ Connected')).catch(err => console.error('❌ Error:', err))"
```

### Port already in use
**Fix:**
- cPanel manages ports automatically
- If needed, change PORT in `.env`
- Restart the application

---

## 📝 MANUAL SETUP (Alternative)

If automatic setup doesn't work, use manual method:

### Via SSH:
```bash
cd ~/mpkkform
cp .env.example .env
nano .env  # Edit with your credentials
npm install --production
node setup_db.js  # Initialize database
node server.js  # Test locally
```

### Via cPanel Terminal:
```bash
cd mpkkform
cp .env.example .env
# Edit .env using File Manager
npm install --production
node setup_db.js
```

---

## 🔄 UPDATING THE APPLICATION

To update after making changes:

1. **Build locally:**
   ```bash
   npm run build
   ```

2. **Upload new files:**
   - Upload new `dist` folder (overwrite)
   - Upload modified backend files if changed

3. **Restart app:**
   - In cPanel Node.js App → Click **Restart**

---

## 🔒 SECURITY NOTES

- ✅ `.env` file is NOT in Git (contains secrets)
- ✅ Use strong database passwords
- ✅ Enable SSL/HTTPS in cPanel for your domain
- ✅ Keep Node.js and dependencies updated
- ✅ Regularly backup your database

---

## 📊 DATABASE BACKUP

### Via phpMyAdmin:
1. Go to phpMyAdmin
2. Select your database
3. Click **Export**
4. Choose **Quick** method
5. Click **Go**

### Via SSH (if available):
```bash
mysqldump -u username -p database_name > backup.sql
```

---

## 🌐 DOMAIN CONFIGURATION

### Using Subdomain:
1. In cPanel → **Subdomains**
2. Create: `mpkk.yourdomain.com`
3. Point to `mpkkform` folder
4. Update Node.js App URL

### Using Main Domain:
1. Point domain to `mpkkform` folder
2. Update Node.js App URL
3. Configure in cPanel

---

## 📞 SUPPORT

### Application Issues:
- Check GitHub repository
- Review error logs in `stderr.log`

### cPanel Issues:
- Contact your hosting provider
- Check cPanel documentation

### Database Issues:
- Use phpMyAdmin for direct access
- Check user privileges
- Verify connection settings

---

## 📋 SYSTEM REQUIREMENTS

- **Hosting:** cPanel with Node.js support
- **Node.js:** Version 18.x or higher
- **Database:** MySQL 5.7+ or MariaDB 10.3+
- **Memory:** Minimum 512MB RAM
- **Disk Space:** ~50MB for application

---

## 🎯 FEATURES

✅ User registration form
✅ Real-time input validation
✅ Uppercase name formatting
✅ Digits-only phone number
✅ MySQL database storage
✅ Separate list view page
✅ Mobile responsive design
✅ AI-powered welcome messages (optional)

---

## 📄 LICENSE & CREDITS

MPKK Registration Form
Version 1.0.0

Built with:
- React + Vite
- Express.js
- MySQL
- TailwindCSS

---

**Ready to deploy? Follow the Quick Start Guide above! 🚀**
