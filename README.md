# Quran Website

An elegant, aesthetic Arabic Quran website using JavaScript for frontend and PHP for backend, following MVC architecture.

## Features

- Modern, responsive design optimized for Arabic script
- Interactive Quran reader with customizable settings
- Audio player for Quran recitations
- Mood-based Surah recommendation chat interface
- Prayer times based on user location
- Dark mode and light mode
- User accounts with preferences storage

## Technical Stack

- Frontend: HTML, CSS, JavaScript (React)
- Backend: PHP with MVC architecture
- APIs: Quran.com API, AlQuran Cloud, Prayer Times API

## Installation

1. Clone the repository
2. Configure your web server to point to the project directory
3. Create the database using the provided SQL script
4. Configure the database connection in `config/database.php`
5. Access the website through your web server

## Directory Structure

```
/
├── app/           # Application code (MVC)
│   ├── controllers/
│   ├── models/
│   ├── views/
│   └── helpers/
├── public/        # Publicly accessible files
│   ├── css/
│   ├── js/
│   ├── img/
│   └── audio/
├── config/        # Configuration files
├── api/           # API endpoints
└── database/      # Database migrations and seeds
``` 