`# Package Manager

## Overview

"Package Manager" is a monolithic web application developed using Laravel 10 for the backend, Vue 3 for the frontend, and Ant Design Vue for UI components. This guide will walk you through setting up the project in your local environment.

## Prerequisites

Ensure you have the following software installed:

- [PHP 8.0+](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download/)
- [Node.js 16.x+](https://nodejs.org/)
- [NPM](https://docs.npmjs.com/downloading-and-installing-node-js-and-npm) (NPM comes with Node.js)

## Setup Instructions

### 1. Clone the Repository

Start by cloning the repository to your local machine:

```
git clone https://github.com/yourusername/package-manager.git
cd package-manager `

### 2\. Install PHP Dependencies

Install the PHP dependencies using Composer:





`composer install`

### 3\. Configure Environment

Create a new environment configuration file from the example:





`cp .env.example .env`

Generate an application key:





`php artisan key:generate`

### 4\. Set Up the Database

Update the `.env` file with your database configuration:

env



`DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password`

Run the database migrations:





`php artisan migrate`

### 5\. Install Node.js Dependencies

Navigate to the frontend directory and install Node.js dependencies:





`cd frontend
npm install`

### 6\. Configure Vue 3 and Ant Design Vue


### 7\. Build Frontend Assets

Build the frontend assets for production:





`npm run build`

### 8\. Serve the Application

Go back to the root directory and start the Laravel development server:





`php artisan serve`

sql



 `Feel free to customize any sections or add more details as needed!`
