# MPKK Form - Deployment Package

This package contains everything needed to deploy the MPKK Registration application to cPanel.

## What's Included

- `dist/` - Production-ready frontend files
- `server.js` - Express backend server
- `db.js` - Database connection configuration
- `package.json` - Node.js dependencies
- `schema.sql` - Database schema
- `.env.example` - Environment variables template

## Quick Start

1. **Read DEPLOY.md** - Complete deployment instructions
2. **Setup Database** - Create MySQL database in cPanel
3. **Upload Files** - Extract this package to your cPanel directory
4. **Configure .env** - Rename `.env.example` to `.env` and update credentials
5. **Install Dependencies** - Run NPM Install in cPanel Node.js App
6. **Start Application** - Start the app in cPanel

## Requirements

- cPanel with Node.js 18+ support
- MySQL database
- Domain or subdomain

## Support

See DEPLOY.md for detailed instructions and troubleshooting.
