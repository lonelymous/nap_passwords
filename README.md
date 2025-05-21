# 🔐 PHP Login System with Encrypted Password Validation

A PHP-based web authentication project that verifies usernames and passwords by decoding an encrypted `password.txt` file and checking user data against a MySQL database.

## 📖 About

This project demonstrates secure user login by:

- Reading user credentials from a form (via POST)
- Decoding a custom-encrypted password file (`password.txt`)
- Validating credentials against the decoded data
- Looking up users in a MySQL database (`adatok`, table `tabla`)
- Displaying a colored response based on the user's favorite color
- Redirecting to `https://police.hu` after incorrect login attempts

## 👨‍💻 Project Requirements

- PHP 7.4+ with CLI and web server interface
- MySQL/MariaDB
- phpMyAdmin (for manual DB setup)
- Docker (for containerized deployment)

## 🛠️ Tech Stack

- **PHP** — core application logic
- **MySQL** — stores usernames and favorite colors
- **phpMyAdmin** — for database management
- **Nginx** — web server
- **Docker** — container orchestration

### Example Data:

| Username                | Titkos  |
|-------------------------|---------|
| katika@gmail.com        | piros   |
| arpi40@freemail.hu      | zold    |
| zsanettka@hotmail.com   | sarga   |
| hatizsak@protonmail.com | kek     |
| terpeszterez@citromail.hu | fekete |
| nagysanyi@gmail.hu      | feher   |

## 🔐 Password File (`password.txt`)

Passwords are stored in a file encrypted with a simple offset-based cipher using the repeating key.

## 📦 Setup & Deployment

### 1. Clone the repository

```bash
git clone https://github.com/lonelymous/nap_passwords.git
```

### 2. Run the repository project in Docker

#### a. Build the Docker image

From the project root directory:

```bash
cd nap_passwords
docker build -t nap-passwords .
```

#### b. Run the Docker image

```bash
docker run -d -p 8080:80 --name passwords-app nap-passwords
```
