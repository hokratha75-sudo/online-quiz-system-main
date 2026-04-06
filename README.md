# QuizMaster v2.0 - Online Quiz System


🛠 System Requirements / តម្រូវការប្រព័ន្ធ

Ensure your environment meets these minimum requirements:
សូមប្រាកដថាប្រព័ន្ធរបស់អ្នកបំពេញតាមលក្ខខណ្ឌដូចខាងក្រោម៖

- **PHP**: `>= 8.2`
- **Composer**: Version `2.x`
- **Node.js & NPM**: Latest stable version.
- **Database**: PostgreSQL (Recommended) or MySQL `~8.0` / MariaDB `~10.5`
- **Web Server**: Apache / Nginx (Laragon is recommended for Windows users).
- **Required Extensions**: `PDO`, `Mbstring`, `OpenSSL`, `cURL`, `XML`, `ZIP`, `GD`, `BCMath`

---

 ការណែនាំពីវិធីដំឡើង project

### 1. Clone the Project / ទាញយក Project
```bash
git clone <repository-url>
cd online-quiz-system-main
```
Or extract the source zip into your web server directory (e.g., `C:\laragon\www\online-quiz-system-main`).

### 2. Install Dependencies / ដំឡើងបណ្ណាល័យជំនួយ
Run these commands in your project root:
រត់ Command ខាងក្រោមនៅក្នុង Folder របស់ Project៖

```bash
# Install PHP packages
composer install

# Install JS packages and build assets
npm install
npm run build
```

### 3. Environment Setup / កំណត់រចនាសម្ព័ន្ធប្រព័ន្ធ
```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` and update your database credentials:
កែតម្រូវឯកសារ `.env` និងផ្លាស់ប្តូរព័ត៌មាន Database របស់អ្នក៖

```env
DB_CONNECTION=mysql # or pgsql
DB_HOST=127.0.0.1
DB_PORT=3306 # or 5432
DB_DATABASE=online_quiz_system
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database & Storage / រៀបចំ Database និង Storage
```bash
# Create tables and seed initial data
php artisan migrate --seed

# Create storage link for images/files
php artisan storage:link
```

### 5. Run the System / បើកដំណើរការប្រព័ន្ធ
```bash
php artisan serve
```
Visit: `http://localhost:8000`

---

## 🔐 Default Login Credentials / គណនីចូលប្រើប្រាស់លំនាំដើម

| Role | Email | Username | Password |
|:--- |:--- |:--- |:--- |
| **Admin** | `admin@gmail.com` | `admin` | `admin123` |
| **Teacher** | `teacher2@example.com` | `teacher2` | `teacher123` |
| **Student** | `student1@gmail.com` | `student1` | `student123` |

---

## 🛠 Maintenance & Quick Tools / ឧបករណ៍ជំនួយ

If you are using the system locally, you can use these special URLs to manage the database directly from your browser:
ប្រសិនបើអ្នកប្រើប្រាស់ក្នុងម៉ាស៊ីនផ្ទាល់ អ្នកអាចប្រើប្រាស់ Link ពិសេសខាងក្រោមដើម្បីគ្រប់គ្រង Database៖

- **`http://localhost:8000/migrate`**: Run database migrations.
- **`http://localhost:8000/seed`**: Populate database with academic structure & test data.
- **`http://localhost:8000/cleanup-duplicates`**: Automatically find and delete duplicate data (Users, subjects, etc.).
- **`http://localhost:8000/link-storage`**: Repair storage symbolic link if images are not showing.


