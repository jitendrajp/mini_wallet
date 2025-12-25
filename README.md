# Mini Wallet

A lightweight wallet management system built with Laravel & Vue.js, supporting authentication, balance management, transactions, Redis queues, email notifications, JWT authentication and Pusher real-time integration.

This README provides full setup instructions, environment configuration details and steps to run the project locally.

---

## Features
- User Authentication (Login, User registration with email verification, Forgot Password, Logout)
- Balance Management (Add Balance)
- Send Money selecting Recipient User (Searable with Name or Email address)
- Redis Queue Support
- JWT Authentication
- Pusher Integration

---

# Demo

**URL:**  
http://13.61.248.128

## Credentials

### User Alpha
- **Email:** user.alpha@demo.app  
- **Password:** mini@Wallet025

### User Beta
- **Email:** user.beta@demo.app  
- **Password:** mini@Wallet025

### User Gamma
- **Email:** user.gamma@demo.app  
- **Password:** mini@Wallet025

## How to Test (Quick Guide)

1. **Login as User Alpha**  
   → Verify dashboard loads and wallet balance is visible.

2. **Send funds to User Beta**  
   → Confirm transaction is created and balance is reduced.

3. **Logout and login as User Beta**  
   → Verify incoming transaction and updated balance.

4. **Send funds back to User Alpha**  
   → Confirms bi-directional (peer-to-peer) transfers.

5. **Login as User Gamma**  
   → Use this account to test:
   - Multiple incoming transactions
   - Transaction history listing
   - Edge cases (low balance, validation errors)

---

## Requirements
| Component | Version      |
|----------|--------------|
| PHP | 8.2+         |
| Composer | Latest       |
| PostgreSQL | 18+ |
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


Cheers!
