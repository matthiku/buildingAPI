# buildingAPI

####RESTful API to provide access to the "Building Events and Control" database

Provides access for web frontend and backend control or monitoring programs

Using
* PHP
* mySQL database
* [Laravel/Lumen](http://lumen.laravel.com/) framework 
 
## Installation:
* Clone the repository
* Install the dependencies using *composer update* ([composer](http://getcomposer.org) needs to be installed beforehand)
* Create a new, empty database and a user with full access rights to this DB
* Copy the file  .env.example  as  .env  and modify it to reflect the database parameters in the previous step
* Create and seed the database with *php artisan migrate --seed*

## Background
This module is the interface part of a 3-tiered project called "Building Automation, Control and Monitoring":

>[buildingAutomationBackend](https://github.com/matthiku/buildingAutomationBackend)  < - > [buildingAPI](https://github.com/matthiku/buildingAPI)  < - > [buildingAutomationFrontend](https://github.com/matthiku/buildingAutomationFrontend)

(C) Matthias Kuhs, Ireland, 2015
