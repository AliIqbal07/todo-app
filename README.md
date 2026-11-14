# Todo App

A to-do app built with Laravel for the DataMoon internship starter task. Users register and log in, and each user manages only their own tasks.

## Features
- Register, log in and log out (Laravel starter kit with Fortify)
- Create, list, edit and delete tasks (title, description, due date)
- Mark a task as complete or reopen it
- Filter tasks: all, active, completed
- Validation with Form Requests
- Each user sees only their own tasks (other users' tasks return 403)
- Factories and seeders for fake data, plus feature tests

## Tech stack
Laravel (Livewire starter kit), PHP 8.4, MySQL, Tailwind CSS, Pest, Laravel Pint, PHPStan, GitHub Actions CI.

## Requirements
PHP 8.4, Composer, Node.js with npm, MySQL, and optionally Laravel Herd.

## Setup
1. Clone the repo and enter it:
   ```
   git clone https://github.com/AliIqbal07/todo-app.git
   cd todo-app
   ```
2. Install dependencies:
   ```
   composer install
   npm install
   ```
3. Create your environment file and app key:
   ```
   cp .env.example .env
   php artisan key:generate
   ```
4. Create an empty MySQL database named `todo_app`, then set these in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=todo_app
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Create the tables, and optionally add demo data:
   ```
   php artisan migrate
   php artisan db:seed
   ```
   The seeder creates a demo user: `demo@example.com` / `password`.
6. Build the front-end assets:
   ```
   npm run build
   ```
7. Run the app. With Laravel Herd, keep the project in your Herd folder and open `http://todo-app.test`. Otherwise run `composer run dev` and open `http://localhost:8000`.

## Tests and code checks
```
composer ci:check
```
This runs Pint (code style), PHPStan (static analysis) and the Pest tests. Run `./vendor/bin/pint` to fix style issues automatically.

## Where things live
| Path | Purpose |
|---|---|
| `routes/web.php` | Routes, including the task resource routes |
| `app/Http/Controllers/TaskController.php` | Task logic |
| `app/Http/Requests/` | Form Request validation |
| `app/Models/` | `Task` and `User` models and their relationships |
| `database/migrations/` | Table definitions |
| `database/factories/`, `database/seeders/` | Fake data |
| `resources/views/tasks/` | Task pages |
| `tests/Feature/TaskTest.php` | Task feature tests |

## What I learned
- **Cursor:** [the four modes and when to use each; Privacy Mode; usage and pricing]
- **Request flow:** Model class interacts with the db, view handles the ui, controller runs the model and the view, routes decides which url to give to which controller. Artisan commands make the files and writes the boilerplate code
- **Migrations and models:** Migrations define the tables and what goes columns it has. The migrate command make the table and rollback undoes it. To update a table, we need to run rollback again
