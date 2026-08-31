# 🎓 IELTS Test Management System

### (Laravel + Bootstrap)

---

## 📌 Overview

The **IELTS Test Management System** is a web-based application designed to simulate real IELTS exams and manage the complete test lifecycle.

It enables:

* **Admins** to create, manage, and evaluate IELTS tests
* **Students** to attempt tests, track performance, and view results

The system supports all IELTS modules:

* Listening
* Reading
* Writing
* Speaking

---

## 🎯 Core Features

### 👨‍💼 Admin Panel

* Manage Modules:
  * Listening
  * Reading
  * Writing
  * Speaking
* Manage Test Types:
  * Academic
  * General Training
* Manage Levels:
  * Level 1–2 (Beginner)
  * Level 3 (Intermediate)
  * Exam Batch (Full Mock Test)
* Create & Manage Tests
* Add Sections (Part 1, Part 2, etc.)
* Add Questions:
  * MCQ
  * Fill in the blanks
  * True/False/Not Given
  * Essay
* Upload Audio (Listening tests)
* Manage Students
* Track Student Attempts
* View Results & Performance

---

### 👨‍🎓 Student Panel

* Register & Login
* Browse Tests by:
  * Module (Listening/Reading/Writing/Speaking)
  * Type (Academic/General)
  * Level
* Attempt Tests:
  * 🎧 Listening → Audio-based questions
  * 📖 Reading → Passage + questions layout
  * ✍️ Writing → Essay input with word count
  * 🎙️ Speaking → (Optional recording/upload)
* View Results
* Track Test History

---

## ⏱️ Test Timing System (Admin Controlled)

### ✅ Admin Controls

* Set **test duration (in minutes)**
* Enable/Disable timer per test
* Define test schedule (optional)

---

### ⏳ Student Experience

* Timer starts when test begins
* Countdown visible on screen
* Auto-submit when time ends
* Reload-safe timer (based on start time)

---

### 🔐 Anti-Cheat Logic

* Server-side time validation
* Prevent manual time bypass
* Auto-submit if time exceeded

---

## 🧩 System Architecture

```text
Module → Type → Level → Test → Section → Question → Answer
```

---

## 🗄️ Database Design

### Core Tables:

* users (admin / student)
* categories (Listening, Reading, Writing, Speaking)
* types (Academic / General)
* levels (Level 1–2, Level 3, Exam Batch)
* tests (includes duration & timer settings)
* sections
* questions
* answers
* student_tests (tracks attempts & timing)
* student_answers

---

## 🛠️ Technology Stack

| Layer    | Technology     |
| -------- | -------------- |
| Backend  | Laravel 10+    |
| Frontend | Bootstrap 5    |
| Database | MySQL          |
| Auth     | Laravel Auth   |
| Server   | Apache / Nginx |

---

## ⚙️ Installation Guide

### 1️⃣ Clone Repository

```bash
git clone https://github.com/your-username/ielts-system.git
cd ielts-system
```

---

### 2️⃣ Install Dependencies

```bash
composer install
npm install
```

---

### 3️⃣ Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

---

### 4️⃣ Configure Database

Edit `.env` file:

```env
DB_DATABASE=ielts_db
DB_USERNAME=root
DB_PASSWORD=
```

---

### 5️⃣ Run Migrations

```bash
php artisan migrate
```

---

### 6️⃣ Seed Data (Optional)

```bash
php artisan db:seed
```

---

### 7️⃣ Start Server

```bash
php artisan serve
```

👉 Open: http://127.0.0.1:8000

---

## 🔐 User Roles

| Role    | Permissions                     |
| ------- | ------------------------------- |
| Admin   | Full system access              |
| Student | Access only own tests & results |

---

## 🧠 Test Workflow

1. Admin creates test
2. Admin adds:
   * Sections
   * Questions
   * Answers
3. Admin sets **test duration**
4. Student starts test
5. Timer begins
6. Student submits OR auto-submit triggers
7. System evaluates answers
8. Student views result

---

## 📊 Evaluation System

| Question Type | Evaluation |
| ------------- | ---------- |
| MCQ           | Automatic  |
| Fill Blank    | Automatic  |
| True/False    | Automatic  |
| Writing       | Manual     |
| Speaking      | Manual     |

---

## 🎨 UI Structure

### 🔹 Header Navigation

* Listening
* Reading
* Writing
* Speaking

---

### 🔹 Layout Design

#### 📖 Reading

* Left → Passage
* Right → Questions

#### 🎧 Listening

* Audio Player (restricted controls)
* Questions below

#### ✍️ Writing

* Question
* Text Area
* Word Count

---

## 📁 Project Structure

```text
app/
 ├── Http/Controllers/
 ├── Models/

database/
 ├── migrations/
 ├── seeders/

resources/
 ├── views/
```

---

## 🔒 Middleware

* auth → authentication
* role:admin → admin access
* role:student → student access

---

## 🚀 Future Enhancements

* IELTS Band Score Calculation
* AI Writing Evaluation
* Speaking Audio Recorder
* Full Exam Mode (Listening + Reading + Writing combined)
* Analytics Dashboard
* Random Question Generator

---

## 📧 Contact

**Developer:** Dilpreet Singh
**Role:** Full Stack Web Developer
