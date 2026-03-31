# QuizMaster v2.0 - Online Quiz System

A premium, modern, and comprehensive online quiz management system built with Laravel 12. Designed for schools, universities, and training centers to manage academic structures, create dynamic quizzes, and track student performance with advanced analytics.

## 🌟 Key Features

- **Role-Based Access Control (RBAC)**: Secure separation between System Administrators, Teachers, and Students.
- **Academic Hierarchy**: Complete CRUD system for managing Departments, Majors, Classes, Courses, and User Enrollments.
- **Advanced Assessment Engine**:
  - Support for Multiple Choice, True/False, and Short Answer questions.
  - **AI Integration**: Automatically generate quiz questions from uploaded PDF or Text materials using Google's Gemini AI.
  - Question Banks for reusability.
- **Student Performance Hub**: 
  - Rich visual analytics, grades distribution, and streak tracking.
  - Detailed review of previous quiz attempts.
- **Teacher Grading Workflow**: Manual review mechanisms for short-answer questions, feedback provision, and controlled grade publication.
- **Security & Proctoring**: Tracks window focus loss/tab switching violations during active quiz attempts.
- **System Development Life Cycle (SDLC) Compliant**: Codebase built using responsive modern UI/UX principles, robust routing, and modular components.

---

## 🛠 System Requirements

Ensure your local environment or server meets the following minimum requirements:

- **PHP**: `>= 8.2`
- **Database**: PostgreSQL (Recommended) or MySQL `~8.0` / MariaDB `~10.5`
- **Composer**: Version `2.x`
- **Node.js & NPM**: Required for compiling frontend Vite assets.
- **Required PHP Extensions**: `PDO`, `Mbstring`, `OpenSSL`, `cURL`, `XML`, `ZIP`, `GD`

---

## 🚀 Installation Guide

Follow these step-by-step instructions to set up the Online Quiz System on your machine:

### 1. Clone the project or extract the files
If you have Git installed:
```bash
git clone <repository-url>
cd online-quiz-system-main
```
If not, extract the source zip into your development environments (e.g., `C:\laragon\www\online-quiz-system-main`).

### 2. Install PHP Dependencies (Composer)
```bash
composer install
```
*(If you encounter errors regarding `phpunit`, delete the `vendor/phpunit` folder and run the command again).*

### 3. Install Frontend Dependencies (NPM)
```bash
npm install
npm run build
```

### 4. Setup Environment File
Copy the example environment file and generate a unique application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure Database (.env)
Open your newly created `.env` file and update the database configuration to match your local setup:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=online_quiz_project
DB_USERNAME=postgres
DB_PASSWORD=your_password
```
*Note: Make sure your database server is running and the database `online_quiz_project` is created.*

### 6. Configure AI Service (Optional but Recommended)
To enable the AI Question Generation feature, add your Google Gemini API key:
```env
GEMINI_API_KEY=your_api_key_here
```

### 7. Run Database Migrations and Seeders
This command will create all required database tables and insert initial dummy data (including default admin/teacher/student accounts):
```bash
php artisan migrate:fresh --seed
```

### 8. Link Storage
Create a symbolic link for uploaded files (avatars, course materials) to be accessible from the web:
```bash
php artisan storage:link
```

### 9. Start the Application
Finally, start your local development server:
```bash
php artisan serve
```

You can now visit the application in your browser at: `http://localhost:8000`

---

## 🔐 Default Login Credentials
If you ran the database seeder (`--seed`), you can log in immediately using the following default test accounts.

| Role | Email | Password |
|---|---|---|
| **Administrator** | admin@example.com | password |
| **Teacher** | teacher@example.com | password |
| **Student** | student@example.com | password |

*(Note: Change these immediately if deploying to a live/production server!)*

---

## 🔄 Updating the System
If you are pulling updates via Git or installing new packages, always run the following to clear caches and rebuild:
```bash
composer install
php artisan optimize:clear
npm run build
```

## 📄 License
This system is proprietary software developed for educational management purposes.
