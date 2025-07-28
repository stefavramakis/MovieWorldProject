
---

# Movie World Project - Setup Instructions

## 1. Clone the Repository

```bash
git clone https://github.com/stefavramakis/MovieWorldProject.git
cd MovieWorldProject
```

## 2. Install PHP Dependencies

```bash
composer install
```

## 3. Install JavaScript Dependencies

```bash
npm install
```

## 4. Environment Setup

- Copy the example environment file:

```bash
cp .env.example .env
```

- Edit `.env` and set your database credentials.

## 5. Generate Application Key

```bash
php artisan key:generate
```

## 6. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

## 7. Build Frontend Assets

```bash
npm run dev
```

## 8. Start the Development Server

```bash
php artisan serve
```

Visit [http://localhost:8000](http://localhost:8000) in your browser.

---

Place this content in a `README.md` file at the root of your project. This will guide anyone testing your project through setup and usage.