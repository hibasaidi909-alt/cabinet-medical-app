doctor@example.com
PASSWORD= password
🛠️ Tech Stack & Requirements

*   **Backend:** PHP 8.4.18+ & Laravel 13.11.2+
*   **Frontend:** Blade / TailwindCSS (integrated with Axios for API requests)
*   **Database:** MySQL / PostgreSQL

---

## ⚙️ Installation & Setup

Follow these steps to get your development environment running:

### 1. Clone the Repository
```bash
git clone [https://github.com/hibasaidi909-alt/cabinet-medical-app.git](https://github.com/hibasaidi909-alt/cabinet-medical-app.git)
cd cabinet-medical-app
-Install DependenciesBashcomposer install
npm install && npm run dev
- Environment ConfigurationCopy the environment file and configure your database settings:Bashcp .env.example .env
php artisan key:generate
Open .env and update your database credentials:Code snippetDB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=MediConnect
DB_USERNAME=root
DB_PASSWORD=your_password
- Database Migrations & SeedingRun the migrations to create the tables for patients, doctors, and appointments:Bashphp artisan migrate --seed
- Start the ApplicationBashphp artisan serve
The app will be accessible at http://127.0.0.1:8000. Core API Endpoints & RoutesAppointments (AppointmentController)GET /appointments - Index view/response with eager-loaded (patients, doctors, services) relationships. Supports search queries.POST /appointments - Stores validated appointment data. Architecture HighlightsEager Loading: Optimized database queries using with(['patient', 'doctor', 'service']) to prevent $N+1$ performance issues.JSON API Responses: Clean backend endpoints configured to seamlessly deliver data to Axios on the frontend.Separation of Concerns: Strict adherence to the MVC pattern with dedicated Request Validation layers for appointment scheduling.