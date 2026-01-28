# Mini Wallet
---
Mini Wallet is a lightweight wallet management system built with **Laravel** and **Vue.js**.  
It is designed as a **technical demonstration** to showcase system-level engineering decisions such as safe balance handling, concurrency control, atomic transactions, background job processing, and real-time updates using modern web technologies.

This project includes full setup instructions, environment configuration, and steps to run the application locally.


## 🏗 Key Engineering Decisions

### Scalable Balance Management
User balances are stored directly on the **user record** instead of being calculated from the transaction history.  
This design keeps balance lookups fast and predictable, even as the number of transactions grows significantly.

### Concurrency Safety
Database transactions combined with **row-level locking** are used to prevent race conditions and double-spend scenarios when multiple transfers are processed at the same time.

### Atomic Transfers
Each money transfer — debiting the sender, crediting the receiver, and recording the transaction — is executed as a **single atomic operation**, ensuring data consistency at all times.

---
## ✨ Features

- **User Authentication**  
  Login, Registration with Email Verification, Forgot Password, Logout

- **Wallet Balance Management**  
  Add balance to wallet

- **Send Money to Other Users**  
  Searchable by name or email address

- **Real-Time Updates**  
  Powered by Pusher

- **Background Job Processing**  
  Redis queues for async tasks

- **JWT Authentication**

---
## 🚀 Live Demo

#### URL: `http://13.61.248.128`

### 🔐 Demo Credentials

#### User Alpha
- **Email:** user.alpha@demo.app  
- **Password:** mini@Wallet025  

#### User Beta
- **Email:** user.beta@demo.app  
- **Password:** mini@Wallet025  

#### User Gamma
- **Email:** user.gamma@demo.app  
- **Password:** mini@Wallet025

---
## 🧪 How to Test (Quick & Easy Guide)

Follow the steps below to test the Mini Wallet application.

### 1️⃣ Log in with two users

- Open **two different browsers**  
  _(Example: Chrome & Edge, or one normal window and one Incognito window)_

- Log in as:
  - **User Alpha** in the first browser
  - **User Beta** in the second browser

- If the wallet balance is low, use **Add Balance** to add funds.


### 2️⃣ Send money from User Alpha to User Beta

- From **User Alpha’s account**, send some money to **User Beta**
- After sending:
  - The transaction should appear immediately
  - User Alpha’s wallet balance should be reduced


### 3️⃣ Verify User Beta’s balance (Real-Time)

- Check **User Beta’s account**
- You should see:
  - The incoming transaction
  - The updated wallet balance
- ⚡ No page refresh is needed — updates happen in real time


### 4️⃣ Send money back to User Alpha

- From **User Beta’s account**, send money back to **User Alpha**
- This confirms **two-way (peer-to-peer) money transfers** work correctly


### 5️⃣ (Optional) Test with a third user

- Log in as **User Gamma** to test with an additional account


---
## 🛠 Installation Guide


⚙️ Requirements

| Component   | Version |
|------------|---------|
| PHP        | 8.2+    |
| Composer   | Latest  |
| PostgreSQL | 18+     |
| Redis      | Latest  |
| Node.js    | 22+     |
| npm        | 10+     |


Clone the repository and run the following commands **in order**:

```bash
# Copy environment file
cp .env.example .env

# Install PHP dependencies
composer install

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret

# Update .env with DB, Redis, Mail, and Pusher credentials

# Run database migrations
php artisan migrate

# Start Redis queue worker
php artisan queue:work redis --tries=3

# Start Laravel development server
php artisan serve

# Install frontend dependencies
npm install

# Build frontend assets
npm run dev     # Development
npm run build   # Production
