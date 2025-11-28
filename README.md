# CodeCraft Internship — Task 01  
## Secure User Authentication System (PHP + MySQL + PHPMailer)

This project was developed as part of **Task 01 – Secure User Authentication** under the **CodeCraft Full Stack Web Development Virtual Internship**.  
The objective was to design and implement a **complete user authentication system** using modern security practices such as OTP-based email verification, password hashing, session handling, and protected routing.

The project demonstrates real-world authentication concepts and follows a clean MVC-style architecture, making it a solid foundation for production-level PHP applications.

---

## 🌟 Why This Project Was Built?

The purpose of this assignment is to:

- Implement a **professional and secure authentication workflow**
- Practice building **real-world features** such as:
  - Registration  
  - Email-based OTP verification  
  - Login & logout  
  - Session-based access control  
- Demonstrate proficiency in:
  - PHP (Core + MVC structure)  
  - MySQL database design  
  - PHPMailer SMTP setup  
  - Protected routes & form validation  
- Showcase a polished, internship-level delivery using **Bootstrap + AdminLTE**
- Complete **Task 01** of the CodeCraft internship track

This project serves as both a **learning assignment** and a **portfolio-ready authentication module**.

---

## 🎯 Key Features

- **User Registration** with server-side validation  
- **Email OTP Verification** using PHPMailer + SMTP  
- **Login / Logout** with session-based authentication  
- **Protected Dashboard Area** (accessible only after OTP verification)  
- **Resend OTP** with 60-second cooldown  
- **Password Hashing** using `password_hash()` (bcrypt)  
- **Email Normalization** (trim + lowercase)  
- **CSRF Protection** for all POST requests  
- **Basic Rate-Limiting** to block brute-force attempts  
- **Flash Messaging System** (success/error alerts)  
- Clean **MVC-style project structure**  
- UI built with **Bootstrap 4.6 + AdminLTE**  

---

## 🛠 Tech Stack

### **Backend**
- PHP 8 (Core PHP, MVC Structure)
- MySQL + PDO (prepared statements)

### **Email / Notifications**
- PHPMailer (SMTP-based OTP emails)

### **Frontend**
- HTML, CSS
- Bootstrap 4.6
- AdminLTE 3 (layout & dashboard styling)

### **Server**
- Apache (XAMPP)
- `.htaccess` for clean routing

---

## 📌 Project Overview (Summary)

This Secure Auth System allows a user to:

1. Register an account  
2. Receive a **6-digit OTP** via email  
3. Verify the OTP (valid for **10 minutes**)  
4. Log in using email + password  
5. Access the **AdminLTE-styled Dashboard** with:  
   - A **Welcome Tab**  
   - A **Project Overview Tab** describing the assignment & tech stack  
6. Logout safely with session destruction  

Every action is validated, protected, and logged using best practices suitable for a beginner-to-intermediate full-stack developer.

---

```

## 📂 Folder Structure (Short Version)

CODECRAFT_FS_01/
│
├── public/ # index.php (entry point)
├── app/
│ ├── controllers/ # AuthController, DashboardController
│ ├── models/ # User model (DB queries)
│ └── views/ # Auth views, Dashboard
│
├── config/ # DB config
├── vendor/ # PHPMailer + Composer dependencies
└── README.md

```

---

## 🚀 Getting Started (Local Setup Guide)

Follow these steps to run the project locally:

### 1️⃣ Requirements
- PHP 7.4+ / 8.x  
- MySQL  
- Composer  
- XAMPP or WAMP  

### 2️⃣ Clone or Download Project

```
htdocs/
└── CODECRAFT_FS_01/
```

### 3️⃣ Database Setup
- Create a MySQL database  
- Import SQL file (or run migration queries)  
- Update your DB credentials in:



config/database.php


### 4️⃣ Install Dependencies (PHPMailer)
Run inside project folder:



composer install


### 5️⃣ Configure SMTP for OTP Emails  
Inside:



app/controllers/AuthController.php


update:



$mail->Host
$mail->Username
$mail->Password
$mail->Port


Gmail users MUST use:
- 2-step verification ON  
- 16-digit App Password  

### 6️⃣ Run the Application
Visit:



http://localhost/CODECRAFT_FS_01/public/


---

## 🔐 Security Notes

- Passwords securely stored using **bcrypt hashing**
- OTP expires in **10 minutes**
- CSRF protection implemented via session tokens
- All SQL queries use **PDO prepared statements**
- Emails normalized to avoid case-based duplicate accounts
- Rate-limiting implemented for:
  - Login attempts  
  - OTP verification  
- Sensitive SMTP credentials **must NOT be committed** to GitHub

---

## 📈 Future Enhancements

- Forgot Password (email reset link)
- Admin Panel & role-based authorization
- OTP hashing (instead of plain storage)
- Logging + audit trail system
- API version using JWT
- Migration to a front-end SPA (Vue/React)

---

## 👨‍💻 Developer Credit

This project is designed and developed by **Harsh Pandey**  
as part of **CodeCraft Full Stack Web Development Virtual Internship – Task 01**.

> Portfolio Link (optional):  
> Add your personal portfolio here — it strengthens your professional profile.

---

If you want, I can also generate:  
✔ A polished **GitHub repo description**  
✔ A perfect **LinkedIn post caption**  
✔ A **Task 01 submission description** for CodeCraft form  

Just tell me:  
**"LinkedIn caption do"** or  
**"GitHub repo description do"** or  
**"Task submission summary de do"**
