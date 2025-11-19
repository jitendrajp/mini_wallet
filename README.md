# Mini Wallet

A lightweight wallet management system built with Laravel & Vue.js, supporting authentication, balance management, transactions, Redis queues, email notifications, JWT authentication and Pusher real-time integration.

This README provides full setup instructions, environment configuration details and steps to run the project locally.

---

## Features
- User Authentication (Login, User registration with email verification, Forgot Password, Logout)
- Balance Management (Add Balance)
- Send Money using Recipient User ID (id of the users table)
- Redis Queue Support
- JWT Authentication
- Pusher Integration

---

## Requirements
| Component | Version      |
|----------|--------------|
| PHP | 8.2+         |
| Composer | Latest       |
| MySQL | 5.7+ / MariaDB |
| Redis | Latest       |
| Node.js | 22 or higher |
| npm | 10 or higher |

---

## Installation Guide
First clone this repository and run the following commands in order:

```
# Copy environment file
cp .env.example .env

# Install PHP dependencies
composer install

# Generate app key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret

# Update .env (DB, Redis, Mail, Pusher)

# Run migrations
php artisan migrate

# Start queue worker (Redis)
php artisan queue:work redis --tries=3

# Start Laravel server
php artisan serve

# Install frontend dependencies
npm install

# Build frontend assets
npm run dev   # development
npm run build # production
```

## How to Test
- Create two users and verify email
- Login each user in a different browser
- Add Balance to each user
- Send Money each user using the recipient's user ID (primary key of users table)
- After the transaction, you can see the data update in real-time via Pusher

Cheers!
