# Web-aplikacija: Social Network Web App

A social network web application built with PHP, JavaScript, HTML and CSS. Users can create posts, browse content by category and comment on posts.

## Features

- Create posts (`add-post.php`) with client-side logic in `add-post.js`
- Comment on posts (`add_comment.php`)
- Content categories: **Bitcoin**, **Esports**, **Technology**
- "About us" page
- User profiles and image uploads (upload files are not included in this repository)

> Note: please check this list against the real code and add or remove features (login/registration, likes, search, etc.) as needed.

## Tech stack

- **Backend:** PHP
- **Frontend:** HTML, CSS, JavaScript
- **Database:** MySQL (add the exact name if different)
- **Local server:** XAMPP / WAMP

## Project structure

```
Web-aplikacija/
├── Bitcoin/          # Bitcoin category pages
├── esports/          # Esports category pages
├── tehnology/        # Technology category pages
├── about-us.php      # About us page
├── add-post.php      # Create a new post
├── add_comment.php   # Add a comment
└── *.css / *.js      # Styles and scripts
```

## How to run locally

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Copy the project folder into `C:\xampp\htdocs\`.
3. Create a MySQL database and import the SQL file (if there is one) via phpMyAdmin.
4. Set your database connection details in the connection file (for example `db.php` / `config.php`).
5. Open `http://localhost/Web-aplikacija/` in your browser.
