Login System with Profile Management

This project is a PHP-based login system with profile management functionalities, designed for basic user authentication and profile updates. It utilizes MySQL for data storage and includes features such as:

User Registration and Login: Users can register with their email and password, and then securely log in using their credentials.

Profile Management: Logged-in users can update their profile information including first name, last name, email, address, pin code, country, and profile picture. Profile picture upload supports file upload functionality.

Password Hashing: Passwords are securely hashed using PHP's password_hash() function for storage in the database.

Session Management: PHP sessions are used to track logged-in users across different pages of the application.

File Handling: Profile pictures uploaded by users are stored in a designated uploads/ directory, with functionalities to delete existing profile pictures.

Security Features: Includes basic input validation and error handling to ensure data integrity and user experience.

This project serves as a foundational PHP application for managing user authentication and basic profile operations. It is ideal for learning PHP fundamentals, MySQL integration, file handling, and session management in web applications.