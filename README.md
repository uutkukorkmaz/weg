# WEG Coding Case Study

## Installation

-----
### macOS

#### Pre-requisites
 
- [Homebrew](https://brew.sh/)
- [make](https://www.gnu.org/software/make/)

#### Install

There is only one step required to install the project on macOS. Make script will do the boring parts of the installation such as setting up correct environment variables for docker containers and connect project with the corresponding containers.

```bash
make build
make migrate
```
-----
### Linux / Windows

#### Pre-requisites

- [docker](https://docs.docker.com/engine/install/)
- [docker-compose](https://docs.docker.com/compose/install/)
- [php  ^8.1](https://www.php.net/downloads.php)
- [composer](https://getcomposer.org/download/)

#### Install

- Create .env files for both docker and laravel to operate
    ```bash
    cp .env.example .env
    cp ./src/.env.example ./src/.env
    ```
- Edit .env files to match your environment 
  - While building the containers, Docker read the .env file which locates in the root directory of the project.
- Build docker containers
    ```bash
    docker-compose up --build -d
    ```
- Install composer dependencies
    ```bash
    cd src && composer install && php artisan key:generate
    ```
- Run migrations
    ```bash
    php artisan migrate --seed
    ```

## Notes

Task will be populated when the seeder runs. Alternatively you can call the following command to populate the database with the all providers' data.

```bash
php artisan app:populate-tasks
```