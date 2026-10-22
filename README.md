# 📄 PDF ⇄ DOCX Converter

> A full-stack web app to convert PDF files to Word (DOCX) and vice versa — built with Laravel 12, Vue 3, and deployed on Render.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue-3-4FC08D?style=flat&logo=vue.js&logoColor=white)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat&logo=docker&logoColor=white)](https://docker.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

![Demo Screenshot](docs/screenshot.png)

---

## ✨ Features

- 🖱️ **Drag & Drop UI** — modern, responsive interface
- 🔄 **Bidirectional conversion** — PDF → DOCX and DOCX → PDF
- ✅ **File validation** — type & size checks (max 10 MB)
- 🔒 **Secure downloads** — files deleted immediately after download
- 🐳 **Docker-ready** — one-command deployment on Render

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12 (PHP 8.3) |
| Frontend | Vue 3 (Composition API) + Tailwind CSS + Vite |
| Conversion | Paperdoc (pure PHP) |
| Database | SQLite |
| Deployment | Render (Docker) |

## 🚀 Local Development

### Prerequisites
- PHP 8.2+ with `zip` extension enabled
- Composer
- Node.js & npm

### Setup

```bash
# 1. Clone & install dependencies
git clone https://github.com/hasya-manan/pdf-converter.git
cd pdf-converter
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Database (SQLite)
touch database/database.sqlite   # Windows: New-Item -ItemType File -Path database/database.sqlite
php artisan migrate

# 4. Storage link
php artisan storage:link

``

# Terminal 1 — Vite dev server
npm run dev

# Terminal 2 — Laravel
php artisan serve