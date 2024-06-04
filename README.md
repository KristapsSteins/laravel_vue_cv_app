# Project Name

## Description

This is a Vue.js and Laravel 11.9 project for managing cv.

## Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js and npm
-   PostgreSQL (Make sure to set the database connection in .env to PostgreSQL)

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/KristapsSteins/laravel_vue_cv_app.git
    ```
2. Navigate to the project directory:
    ```sh
    cd <project-directory>
    ```
3. Install PHP dependencies:
    ```sh
    composer install
    ```
4. Install JavaScript dependencies:
    ```sh
    npm install
    ```
5. Copy the example environment file and modify according to your environment (make sure to set the DB_CONNECTION to pgsql and provide your PostgreSQL database details):
    ```sh
    cp .env.example .env
    ```
6. Generate a new application key:
    ```sh
    php artisan key:generate
    ```
7. Run the database migrations (**Set the database connection in .env before migrating**):
    ```sh
    php artisan migrate
    ```
## Usage

1. Start the local development server:
    ```sh
    php artisan serve
    ```
2. Start the Vue.js server:
    ```sh
    npm run dev
    ```
    
You can now access the server at http://localhost:8000