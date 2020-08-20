# aifa
A simple RESTful API service inspired by the [https://anapioficeandfire.com/Documentation#books](Ice &amp; Fire API), written by [https://github.com/kheme](Okiemute Omuta)

## Project Requirements
* MySQL database
* Redis server
* Apache web server
* PHP 7.2.5 or higher

## Project Setup
#### Database Setup
* Create a new MySQL database called "aifa"
* Create a new user MySQL user with username = "aifa" and password = "aifa"
* Grant the new MySQL user ("aifa") full privileges to the "aifa" database

#### Project Initialization &amp; Configuration
* Open a new terminal window in a folder of your choice
* From the terminal, run the command `git clone https://github.com/kheme/aifa.git`
* Enter the the "aifa" folder and make a copy of the `.env.example` with the command `cp .env.example .env`
* Open the `.env` file in an editor of your choice, and set your `REDIS_PASSWORD`

#### Migration
Return to the terminal window and run the command `php artisan migrate`

## Testing
To test, open a new terminal window from within the aifa folder and run `./vendor/bin/pupunit --testdox`