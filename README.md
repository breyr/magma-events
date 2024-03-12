# LAMP Stack Docker

This project sets up a LAMP (Linux, Apache, MySQL, PHP) stack using Docker and Docker Compose.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

- Docker
- Docker Compose

### Setup

1. Clone the repository to your local machine.
2. Copy the `.env.example` file and rename the copy to `.env`.
3. Open the `.env` file and replace the placeholders with your own data. The `.env` file should contain the following variables:
    - `MYSQL_DATABASE`
    - `MYSQL_USER`
    - `MYSQL_PASSWORD`
    - `MYSQL_ALLOW_EMPTY_PASSWORD=1`
4. Open the `src/includes/connect.php` file and replace `<database service name>`, `<user>`, `<password>`, and `<database>` with your own data. The connection should look something like this:
   
    ```php
    $conn = new mysqli("db", "your_user", "your_password", "your_database");
    ```
5. Run the following command `docker-compose up` in the root directory of the project to start the services.

### Other Notes

There is bootstrap 5, bootstrap icons, jQuery, and custom CSS included in `index.php`. The `src` directory is mounted to the docker container running the apache service, so any changes made to the files in the `src` directory will be reflected in the Apache service.

## Usage

Once the services are up and running, you can access the PHP application by navigating to `http://localhost` in your web browser. You can access phpMyAdmin by navigating to `http://localhost:8080`.