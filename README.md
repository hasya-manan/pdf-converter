📄 PDF & DOCX Converter (Laravel + Vue)
A simple web application that allows users to seamlessly convert PDF files to Word (DOCX) and Word files to PDF.

This project was built as a practical learning exercise to practice full-stack development and cloud deployment using Render.

🚀 Tech Stack
Backend: Laravel 12 (PHP 8.3)

Frontend: Vue 3 (Composition API) + Tailwind CSS + Vite

Conversion Engine: Paperdoc (Pure PHP)

Database: SQLite

Deployment: Render (Docker)

Version Control: GitHub

✨ Features
Drag & Drop UI: Modern, responsive interface for uploading files.

Bidirectional Conversion: Supports both PDF-to-Word and Word-to-PDF.

File Validation: Ensures users only upload supported file types and sizes (Max 10MB).

Secure Downloads: Files are processed, served via a secure route, and deleted immediately after download to save server space.

💻 Local Development Setup
If you clone this repository to a new computer, follow these steps to get it running locally:

1. Prerequisites

PHP 8.2+ installed

Composer installed

Node.js & npm installed

Ensure the PHP zip extension is enabled in your php.ini.

2. Install Dependencies

bash
# Install PHP packages
composer install

# Install frontend packages (Vue, Vite, Tailwind)
npm install
3. Environment Setup

bash
# Copy the example environment file
cp .env.example .env

# Generate a new application key
php artisan key:generate
4. Database Setup (SQLite)
Because this project uses SQLite, you must create the database file manually on a new device before running migrations:

bash
# Create the empty database file (Windows PowerShell)
New-Item -ItemType File -Path database/database.sqlite

# Run the migrations to create the tables
php artisan migrate
5. Link Storage

bash
# Create the symlink for file uploads
php artisan storage:link
6. Run the Application
You need two separate terminals running simultaneously:

Terminal 1 (Frontend/Vite):

bash
npm run dev
Terminal 2 (Backend/Laravel):

bash
php artisan serve
Visit http://localhost:8000 in your browser.

☁️ Deployment (Render)
This project is configured to be deployed as a Docker Web Service on Render.

How it works:

Code is pushed to GitHub.

Render detects changes and builds the Docker image using the provided Dockerfile.

Render executes the scripts/00-laravel-deploy.sh script, which installs Composer dependencies, builds the Vue production assets (npm run build), and caches the Laravel configuration.

Environment variables (like APP_KEY) are injected securely via the Render Dashboard.

⚠️ Free Tier Limitations
Because this is deployed on Render's free tier:

Ephemeral Filesystem: Uploaded files are not permanently stored. If the server restarts or goes to sleep, the files are lost.

Cold Starts: The server sleeps after 15 minutes of inactivity. The first request to wake it up may take 30-60 seconds.

🧠 Lessons Learned
Frontend Builds in Production: During local development, Vite serves assets dynamically. In production, npm run build is mandatory to generate the public/build/manifest.json file so Laravel can serve the compiled Vue/CSS files.

Environment Variables: Never commit the .env file to GitHub. Production secrets (like APP_KEY) must be passed via the hosting provider's dashboard.

Configuration Caching: In production, php artisan config:cache must be run after environment variables are injected, otherwise Laravel will use stale or missing configuration.

Database Files are Ignored: .sqlite files are ignored by Git to prevent committing local data. On a new device, you must manually create the database.sqlite file and run migrations.