# Sistem Kuisioner Kesehatan Mental - Laravel

## Overview

Sistem kuisioner kesehatan mental berbasis web yang terintegrasi dengan AI untuk prediksi kondisi mental (Kesehatan Mental Normal / Kesehatan Mental Terganggu) menggunakan algoritma SVM (Support Vector Machine).

## Tech Stack

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL
- **Frontend**: Blade Templates, TailwindCSS
- **AI Backend**: Flask API (Python)
- **Machine Learning**: SVM Classifier

## Features

- ✅ User Authentication (Login/Register)
- ✅ Quiz Type: Self Efficacy (Self-Efficacy + Well-Being)
- ✅ AI-Powered Prediction (Kesehatan Mental Normal / Kesehatan Mental Terganggu)
- ✅ Well-Being Subscale Analysis
- ✅ Quiz History
- ✅ Real-time Prediction Results
- ✅ Responsive Design

## Project Structure

```
kuisioner-mentalhealth/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php       # Authentication logic
│   │       ├── HomeController.php       # Dashboard logic
│   │       └── QuizController.php       # Quiz & AI integration
│   ├── Models/
│   │   ├── User.php                    # User model
│   │   ├── Question.php                # Question model
│   │   ├── Answer.php                  # Answer model
│   │   └── QuizResult.php              # Quiz result model
│   └── Services/
│       └── FlaskApiService.php         # Flask API communication
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php           # Main layout
│       ├── auth/
│       │   ├── login.blade.php         # Login page
│       │   └── register.blade.php      # Register page
│       ├── quiz/
│       │   ├── index.blade.php         # Quiz selection
│       │   ├── start.blade.php         # Quiz questions
│       │   └── result.blade.php        # AI prediction results
│       ├── home.blade.php              # Dashboard
│       └── landing.blade.php           # Landing page
├── database/
│   ├── migrations/                     # Database migrations
│   └── seeders/                        # Database seeders
├── routes/
│   └── web.php                         # Web routes
├── .env                                # Environment configuration
├── composer.json                       # PHP dependencies
└── README.md                           # This file
```

## Installation

### Prerequisites

1. PHP 8.2 or higher
2. Composer
3. MySQL 5.7+ or MariaDB 10.3+
4. Node.js & NPM (for assets)

### Step 1: Clone Repository

```bash
cd D:\Skripsi\project\kuisioner-mentalhealth
```

### Step 2: Install Dependencies

```bash
composer install
npm install
```

### Step 3: Environment Setup

1. **Copy environment file**:
   ```bash
   cp .env.example .env
   ```

2. **Generate application key**:
   ```bash
   php artisan key:generate
   ```

3. **Configure .env file**:
   ```env
   APP_NAME="Kuisioner Mental Health"
   APP_ENV=local
   APP_KEY=base64:...
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kuisioner_mentalhealth
   DB_USERNAME=root
   DB_PASSWORD=

   # Flask API Configuration
   FLASK_API_URL=http://127.0.0.1:5000
   ```

### Step 4: Database Setup

1. **Create database**:
   ```sql
   CREATE DATABASE kuisioner_mentalhealth;
   ```

2. **Run migrations**:
   ```bash
   php artisan migrate
   ```

3. **Run seeders** (optional):
   ```bash
   php artisan db:seed
   ```

### Step 5: Build Assets

```bash
npm run build
```

### Step 6: Start Development Server

```bash
php artisan serve
```

Application will be available at `http://localhost:8000`

## Configuration

### Flask API Integration

The system integrates with Flask API for AI predictions. Configure in:

**1. `.env` file**:
```env
FLASK_API_URL=http://127.0.0.1:5000
```

**2. `config/services.php`**:
```php
'flask' => [
    'url' => env('FLASK_API_URL', 'http://127.0.0.1:5000'),
],
```

### Starting Flask API

Before using the application, start the Flask API:

```bash
cd D:\Skripsi\project\backend
python app.py
```

Flask will run on `http://127.0.0.1:5000`

## Database Schema

### Users Table
```sql
- id
- name
- email
- password
- created_at
- updated_at
```

### Questions Table
```sql
- id
- type (self_efficacy)
- scale_type (likert_4, likert_7)
- question_text
- order
- is_active
- created_at
- updated_at
```

### Answers Table
```sql
- id
- user_id
- question_id
- score
- updated_at
```

### Quiz Results Table
```sql
- id
- user_id
- quiz_type (self_efficacy)
- total_score
- max_score
- category (high_well_being, low_well_being)
- feedback
- prediction_data (JSON) ← AI prediction results
- completed_at
```

## Routes

### Authentication Routes
```php
GET  /login          - Login page
POST /login          - Login submit
GET  /register       - Register page
POST /register       - Register submit
POST /logout         - Logout
```

### Home Routes
```php
GET /home            - User dashboard (auth required)
```

### Quiz Routes
```php
GET  /quiz                    - Quiz selection page
GET  /quiz/start/{type}       - Start quiz (self_efficacy)
POST /quiz/submit/{type}      - Submit quiz answers
GET  /quiz/result/{type}      - View quiz results
GET  /quiz/history            - Quiz history
```

## Usage Guide

### For Users

#### 1. Register & Login

1. Go to `http://localhost:8000`
2. Click "Register" to register
3. Fill in name, email, and password
4. Click "Login" to login

#### 2. Start Quiz

1. After login, you'll see the quiz option
2. Choose **Self Efficacy Factor**: Self-efficacy + Well-being (with AI prediction)

#### 3. Complete Quiz

1. Read each question carefully
2. Answer **10 Self-Efficacy questions** (likert 4 scale)
3. Answer **18 Well-Being questions** (likert 7 scale)
4. Click "Submit" to submit

#### 4. View Results

**For Self Efficacy Quiz**:

- **AI Prediction**: Kesehatan Mental Normal / Kesehatan Mental Terganggu
- **Confidence Chart**: Persentase probabilitas High vs Low Well-Being
- **Top Features Chart**: Faktor paling berpengaruh dari hasil kuisioner
- **Penjelasan Hasil**: Penjelasan otomatis berbasis AI
- **Well-Being Subscales**:
  - Autonomy (Kemandirian)
  - Environmental Mastery (Penguasaan lingkungan)
  - Personal Growth (Pengembangan diri)
  - Positive Relations (Hubungan positif)
  - Purpose in Life (Tujuan hidup)
  - Self Acceptance (Penerimaan diri)

#### 5. Quiz History

- View all completed quizzes
- See past results
- Compare progress over time

### For Developers

#### Adding New Questions

1. **Create migration**:
   ```bash
   php artisan make:migration add_new_questions
   ```

2. **Insert questions**:
   ```php
   DB::table('questions')->insert([
       'type' => 'self_efficacy',
       'scale_type' => 'likert_4',
       'question_text' => 'Pertanyaan baru...',
       'order' => 11,
       'is_active' => true
   ]);
   ```

3. **Run migration**:
   ```bash
   php artisan migrate
   ```

#### Modifying Quiz Logic

**QuizController.php**:
- `start()`: Load questions by type
- `submit()`: Process answers & integrate with Flask AI
- `result()`: Display results with AI prediction
- `history()`: Show user's quiz history

#### FlaskApiService

**Purpose**: Communicate with Flask API

```php
$flaskService = new FlaskApiService();
$quizData = $flaskService->transformSelfEfficacyData($answers, $questions);
$predictionResult = $flaskService->predictMentalHealth($quizData);
```

**Methods**:
- `predictMentalHealth()`: Send data to Flask API
- `transformSelfEfficacyData()`: Transform answers to Flask format
- `checkHealth()`: Check Flask API status

## Self Efficacy Scoring System

### Question Distribution

- **Self-Efficacy (SE)**: SE01–SE10 (10 questions, likert 4)
- **Well-Being (Q)**: Q1–Q18 (18 questions, likert 7)

### Well-Being Subscales

| Subscale | Items |
|----------|-------|
| Autonomy | Q1, Q2, Q3 |
| Environmental Mastery | Q4, Q5, Q6 |
| Personal Growth | Q7, Q8, Q9 |
| Positive Relations | Q10, Q11, Q12 |
| Purpose in Life | Q13, Q14, Q15 |
| Self Acceptance | Q16, Q17, Q18 |

### Prediction Categories

| Prediction | Label Tampil |
|------------|-------------|
| high_well_being | Kesehatan Mental Normal |
| low_well_being | Kesehatan Mental Terganggu |

## Troubleshooting

### Flask API Connection Error

**Symptoms**: Prediction fails with connection error

**Solutions**:
1. Ensure Flask API is running: `cd D:\Skripsi\project\backend && python app.py`
2. Check Flask API URL in `.env`: `FLASK_API_URL=http://127.0.0.1:5000`
3. Test Flask health: `curl http://127.0.0.1:5000/api/health`

### prediction_data is Null

**Symptoms**: Results don't show AI prediction

**Solutions**:
1. Check Laravel log: `tail -f storage/logs/laravel.log`
2. Verify QuizResult model has `prediction_data` in fillable
3. Check if Flask API returns valid response
4. Try submitting quiz again

### Tailwind Class Not Rendering (e.g. bg-green-800)

**Symptoms**: Color classes applied but not rendered

**Solutions**:
1. Use inline style instead: `style="background-color: #166534;"`
2. Rebuild assets: `npm run build`
3. Clear view cache: `php artisan view:clear`

### Database Migration Errors

**Symptoms**: Migration fails

**Solutions**:
1. Clear config cache: `php artisan config:clear`
2. Rollback migration: `php artisan migrate:rollback`
3. Check database connection in `.env`
4. Run migration again: `php artisan migrate`

### Asset Build Errors

**Symptoms**: CSS/JS not loading

**Solutions**:
1. Clear view cache: `php artisan view:clear`
2. Rebuild assets: `npm run build`
3. Check public folder permissions
4. Clear browser cache

## Development

### Code Style

- Follow PSR-12 coding standard
- Use Laravel conventions
- Add comments for complex logic

### Debugging

**Enable debug mode** in `.env`:
```env
APP_DEBUG=true
```

**Check logs**:
```bash
# Laravel log
tail -f storage/logs/laravel.log
```

**Database query log**:
```php
DB::enableQueryLog();
// ... run queries
dd(DB::getQueryLog());
```

## Deployment

### Production Setup

1. **Environment**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Assets**:
   ```bash
   npm run build
   ```

4. **Permissions**:
   ```bash
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   ```

## Security

### Best Practices

1. **Use HTTPS** in production
2. **Set strong password** for database
3. **Keep dependencies updated**
4. **Use CSRF protection** (enabled by default)
5. **Validate user input**
6. **Regular backups**

### Environment Variables

**Never commit** `.env` file to version control

**Required variables**:
```env
APP_KEY=                    # Generate with php artisan key:generate
DB_PASSWORD=                # Strong password
FLASK_API_URL=             # Flask API endpoint
```

## Maintenance

### Backup Strategy

1. **Database backup**:
   ```bash
   mysqldump -u root -p kuisioner_mentalhealth > backup.sql
   ```

2. **Files backup**:
   - `.env`
   - `storage/app`
   - `database/migrations`

## API Documentation

### Flask API Endpoints

**Base URL**: `http://127.0.0.1:5000`

**Endpoints**:
- `GET /` - API info
- `GET /api/health` - Health check
- `POST /api/predict` - Predict mental health (Self Efficacy & Well-Being)

## Credits

- **Developer**: [Your Name]
- **Project**: Skripsi Sistem Prediksi Kesehatan Mental
- **Institution**: [Your University]
- **Year**: 2026

## References

- [Laravel Documentation](https://laravel.com/docs)
- [Blade Templates](https://laravel.com/docs/blade)
- [TailwindCSS](https://tailwindcss.com/)
- [Ryff's Well-Being Scale](https://aging.wisc.edu/midus/findings/pdfs/830.pdf)
- [General Self-Efficacy Scale](https://www.ralfschwarzer.de/)

## Changelog

### Version 1.0.0 (2026)
- Initial release
- User authentication
- Self Efficacy quiz with AI prediction (SVM)
- Well-Being subscale analysis
- Quiz history
- Responsive design

## Roadmap

### Upcoming Features
- [ ] Admin dashboard
- [ ] Export results to PDF
- [ ] Email reports
- [ ] Dark mode
- [ ] Multi-language support
- [ ] Advanced analytics dashboard

## Notes

### Important Notes

1. **Flask Dependency**: Must run Flask API before using the quiz
2. **Database**: Run migrations after pulling updates
3. **Assets**: Rebuild after modifying frontend
4. **Tailwind Purge**: Use inline style for dynamic color classes

### Known Limitations

1. **ML Model**: Accuracy depends on training data size
2. **Language**: Currently Indonesian only
3. **Mobile**: Responsive but not native mobile app

---

**Last Updated**: April 2026