#!/bin/bash

# Configuration
PORT=8000
URL="http://localhost:$PORT"

echo "--------------------------------------"
echo "🚀 Starting Book Library App..."
echo "--------------------------------------"

# 1. Check if PHP is installed
if ! command -v php &> /dev/null
then
    echo "❌ Error: PHP is not installed. Please install PHP."
    exit 1
fi

# 2. Check if port is already in use
if lsof -Pi :$PORT -sTCP:LISTEN -t >/dev/null ; then
    echo "⚠️  Port $PORT is already in use. Trying to stop old process..."
    lsof -ti :$PORT | xargs kill -9
fi

# 3. Start PHP Server in background
echo "📡 Server starting at $URL"
php -S localhost:$PORT > /dev/null 2>&1 &
PHP_PID=$!

# 4. Wait a second and open browser
sleep 1
echo "🌐 Opening your browser..."
open $URL || xdg-open $URL || echo "Please open $URL manually."

echo "--------------------------------------"
echo "✅ App is running! (Press Ctrl+C to stop)"
echo "--------------------------------------"

# Keep script running to monitor server
wait $PHP_PID
