 
# goreact-poc

## Folder structure 
	There are two sperate folders in this repo
	1. frontend
	2. backend
	
	frontend - This folder contains the UI. The frontend code is developed using Angular 9
	backend - This folder contails the API. The backend code is developed using Laravel 7 framework using PHP 7.2
## Demo
 - [Walkthrough Demo](https://github.com/yusufm-cybage/goreact-poc/raw/master/demo_goreact.mp4)

## Functionality implemented
 - Login/Logout functionality
	- There are 2 types of users : 
		1. Admin user
		2. Normal user
		
		- Credentials for Admin user are :
			username:```admin@test.com```
			password:```password```
	
		- Credentials for Normal user are :
			username:```johndoe@test.com```
			password:```password```
		
 - Upload files form
	- Supported files that can be uploaded are of MIME type jpg, pdf and mp4

 - Listing of files uploaded by the user 
 - Search for uploaded files based in name, tag or description of the file.
 - For admin user only :- 
	- List of all the files that have been uploaded by all the users.
 
## URL Parameters
	
## Functionality excluded 
 - Few functionality have not been implemented for this PoC
	1. Access logs are not implemented.
	2. Event logs are not implemented.
	3. Tests for default framework files of Laravel are not written.

## Prerequisites
 - System requirements
 - A server application such as Apache or Nginx to run Laravel api server.
 - PHP 7.2 and MySQL 5.7
 - Composer for dependency manager
 - XDebug must be enabled with PHP 7.2 to generate the code coverage.
 - Laravel 7 Angular 9 version 

## Installation

```bash
$git clone https://github.com/yusufm-cybage/goreact-poc.git
$cd root folder i.e goreact-poc

```
## Backend setup and Features

run the following commands

```bash
$cd backend

$composer install

setup database name in env file

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=goreact
DB_USERNAME=root
DB_PASSWORD=

$php artisan migrate:fresh --seed
$php artisan passport:install
$php artisan serve

```

 - Query Optimization: using laravel eloquent query mechanism for retriving details.
 - Backend Security aspect
   - While viewing user wise media file id encrypted in url and entire application.
   - Authentication required while fetching all url except login.

## Frontend setup
 - Details and commands to setup and run
   - Change your directory to Frontend folder
   - Install dependencies using `npm install` command
   - Set API Base URL and File Base URL in environment file. Currently the default URL is set 'http://localhost:8000/'
   - Start your development environment using `npm start`
   - To generate build use `npm build`. Use the `--prod` flag for a production build.

## PHPunit Setup and generating Code Coverage
 - PHPUnit 8 is used to implement Unit testing in API.
 - command to run PHPUnit Testcases : 
	```vendor\bin\phpunit```
 - command to generate code coverage : 
	```vendor\bin\phpunit --coverage-html codecoverage```
	 - This command will create the coverage report in folder codecoverage

## CI/CD using PHPUnit
 - We can use build.xml to configure PHPUnit with CI/CD tool such as Jenkins that would run phpunit while determining the pipeline when code is pushed to repo.
 - Also we can configure PHP_CodeSniffer, phpDox, PHPMD to configure the coding standards that would be implemented while a build is executed in the deployment pipeline.

## Security 
 - We have implmented Laravel passport to achieve OAuth authentication. Due to which we are able to achieve maximum encryption of user data.
 - To access the user details we are not using user id but instead we are using UUID for secure profile access.
 - File names are also encrypted and accessed so that they cannot be directly accessed.
