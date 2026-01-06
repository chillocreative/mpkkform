#!/bin/bash

# Quick health check script for MPKK Form

echo "🔍 MPKK Form - Health Check"
echo "=============================="
echo ""

# Check if .env exists
if [ -f .env ]; then
    echo "✅ .env file exists"
else
    echo "❌ .env file missing!"
    exit 1
fi

# Check if dist folder exists
if [ -d dist ]; then
    echo "✅ dist folder exists"
else
    echo "❌ dist folder missing!"
    exit 1
fi

# Check if node_modules exists
if [ -d node_modules ]; then
    echo "✅ node_modules exists"
else
    echo "⚠️  node_modules missing - run: npm install"
fi

# Check Node.js version
NODE_VERSION=$(node -v)
echo "✅ Node.js version: $NODE_VERSION"

# Check if server.js exists
if [ -f server.js ]; then
    echo "✅ server.js exists"
else
    echo "❌ server.js missing!"
    exit 1
fi

# Check if db.js exists
if [ -f db.js ]; then
    echo "✅ db.js exists"
else
    echo "❌ db.js missing!"
    exit 1
fi

echo ""
echo "=============================="
echo "Health check complete!"
echo "=============================="
