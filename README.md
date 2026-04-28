# Online Quiz System

A modern, feature-rich online quiz platform built with **Laravel 12**, **PostgreSQL**, **Tailwind CSS**, and **Alpine.js**.

---

## 🛠 System Requirements

Ensure your environment meets these minimum requirements before installing:

| Requirement | Version |
|---|---|
| **PHP** | `>= 8.2` |
| **Composer** | `2.x` |
| **Node.js & NPM** | Latest stable |
| **Database** | PostgreSQL `>= 13` (Recommended) |
| **Web Server** | Apache / Nginx (Laragon recommended on Windows) |
| **PHP Extensions** | `PDO`, `pgsql`, `Mbstring`, `OpenSSL`, `cURL`, `XML`, `ZIP`, `GD`, `BCMath` |

---

## 🚀 Installation Guide (For New Setup)

Follow these steps to set up the project on your machine.

### Step 1 — Clone the Project

```bash
cd C:\laragon\www
git clone https://github.com/YOUR_USERNAME/online-quiz-system-main.git
cd online-quiz-system-main
```

---

### Step 2 — Install PHP Dependencies

```bash
composer install
```

---

### Step 3 — Install Node.js Dependencies & Build Assets

```bash
npm install
npm run dev
```

---

### Step 4 — Setup Environment File

```bash
copy .env.example .env
php artisan key:generate
```

Open `.env` and update your database credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=online_quiz_project
DB_USERNAME=postgres
DB_PASSWORD=your_password_here
```

---

### Step 5 — Create the Database

Open **CMD** and navigate to PostgreSQL:

```cmd
cd "C:\Program Files\PostgreSQL\18\bin"
psql -U postgres
```

Inside the `psql` prompt, run:

```sql
CREATE DATABASE online_quiz_project;
\q
```

---

### Step 6 — Import Database from Backup

If you received a `backup.sql` file from the project owner:

```cmd
cd "C:\Program Files\PostgreSQL\18\bin"
psql -U postgres -d online_quiz_project -f "C:\path\to\backup.sql"
```

> Enter your PostgreSQL password when prompted.

**OR** — Run migrations + seeders from scratch:

```bash
php artisan migrate --seed
php artisan storage:link
```

---

### Step 7 — Start the Application

```bash
php artisan serve
```

🌐 Open your browser: **http://localhost:8000**

---

## 🔐 Default Login Credentials

| Role | Email | Username | Password |
|:---|:---|:---|:---|
| **Admin** | `admin@gmail.com` | `admin` | `admin123` |
| **Teacher** | `teacher2@example.com` | `teacher2` | `teacher123` |
| **Student** | `student1@gmail.com` | `student1` | `student123` |

---

## 💾 Database Backup (For Project Owner)

To export the database for sharing with others:

```cmd
cd "C:\Program Files\PostgreSQL\18\bin"
pg_dump -U postgres -f "C:\Users\asusx\Desktop\backup.sql" online_quiz_project
```

Send the generated `backup.sql` file to your friend along with this README.

---

## ✅ Quick Setup Checklist

| # | Step | Done |
|---|---|---|
| 1 | Clone project from GitHub | ☐ |
| 2 | `composer install` | ☐ |
| 3 | `npm install && npm run dev` | ☐ |
| 4 | Copy `.env.example` → `.env` + generate key | ☐ |
| 5 | Edit DB settings in `.env` | ☐ |
| 6 | Create database in PostgreSQL | ☐ |
| 7 | Import `backup.sql` (or run `migrate --seed`) | ☐ |
| 8 | `php artisan serve` | ☐ |
| 9 | Open **http://localhost:8000** ✅ | ☐ |

---

## 🛠 Maintenance & Quick Tools

If running locally, these special URLs are available for database management:

| URL | Action |
|---|---|
| `http://localhost:8000/migrate` | Run database migrations |
| `http://localhost:8000/seed` | Populate database with test data |
| `http://localhost:8000/cleanup-duplicates` | Remove duplicate records |
| `http://localhost:8000/link-storage` | Fix storage symbolic link |

---

## 🧰 Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | Laravel 12 (PHP 8.2) |
| **Database** | PostgreSQL 18 |
| **Frontend** | Blade, Tailwind CSS, Alpine.js |
| **Auth** | Laravel built-in Auth |
| **Assets** | Vite + Node.js |

---

> Built with ❤️ — QuizMaster v2.0 © 2026
