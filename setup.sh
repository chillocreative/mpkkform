#!/bin/bash

# MPKK Form - cPanel Deployment Setup Script
# This script helps automate the deployment process

echo "=========================================="
echo "MPKK Form - cPanel Deployment Setup"
echo "=========================================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found!"
    echo "Creating .env from .env.example..."
    cp .env.example .env
    echo "✅ .env file created"
    echo ""
    echo "⚠️  IMPORTANT: Edit .env file with your database credentials!"
    echo ""
fi

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing dependencies..."
    npm install --production
    echo "✅ Dependencies installed"
else
    echo "✅ Dependencies already installed"
fi

echo ""
echo "=========================================="
echo "Setup Complete!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Edit .env file with your database credentials"
echo "2. Run: node setup_db.js (to create database tables)"
echo "3. Run: node server.js (to start the application)"
echo ""
