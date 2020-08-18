# aifa
A simple RESTful API service powered by the [https://anapioficeandfire.com/Documentation#books](Ice &amp; Fire API)

## Project Requirements
* MySQL for database
* Redis for cache
* Apache web server
* PHP 7.2.5 or higher

## Project Setup
**Database Setup**
* Create a new MySQL database called `aifa`
* Create a new user MySQL user with username and password set as `aifa`
* Grant the new MySQL user full privileges to the aifa database

**Project Initialization &amp; Configuration**
* Create a new folder called "aifa"
* Enter the new folder and launch a new terminla window
* From the terminal, run the command `git clone https://github.com/kheme/aifa.git`
* Make a copy of the `.env.example` with the command `cp .env.example .env`
* Open the `.env` file in an editor of your choice, and set your redis password
