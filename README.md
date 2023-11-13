# php_ecommerce_with_realtime_chat

# Project Setup Instructions

## Overview

This guide provides step-by-step instructions to set up and run the project on your local machine. Before proceeding, ensure that PHP and MySQL are installed.

## Installation Steps

1. **Install PHP and MySQL:**
   Make sure you have PHP and MySQL installed on your machine. If not, you can download and install them from their official websites.

2. **Run the Project:**
   Clone the project repository and navigate to the project directory. Run the project using your preferred web server.

3. **Configure Database Properties:**
   Open the `database/dbconfig.php` file and update the database properties according to your local MySQL configuration.

   ```php
   // database/dbconfig.php

   $host = 'your_mysql_host';
   $username = 'your_mysql_user';
   $password = 'your_mysql_password';
   $database = 'your_database_name';
   $port = 'your_mysql_port';

4. **Setup database & its tables:**
   Run the following URL in your browser to execute the database setup script:

   ```php
   http://localhost:{port}/database/setup-database.php

   Replace {port} with the port number where your local server is running.

5. **Project Setup Complete:**
   Once you have completed the above steps, the project is now set up on your local machine and ready for use.

## Running application in docker

1. **Install Docker:**
   Make sure you have docker installed with version `24.0.7 ` or higher. Please verify the docker installation by
   running the version command.

   ```php
   docker --version && docker-compose --version
   
2. **Spinning the containers**
   Navigate to the source directory. and run the following command
   
   ```php
   docker-compose up -d
   ```
   It will take some time on first installation to fetch/create the images.


3. **Testing**
   Please enter `http:localhost:8081` in browser to run the application. Please follow above guidelines to set up
   database and tables.
