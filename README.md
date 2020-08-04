 

## goreact-poc

## Folder structure 
	There are two sperate folders in this repo
	1. frontend
	2. backend
	
	frontend - This folder contains the UI. The frontend code is developed using Angular 9
	backend - This folder contails the API. The backend code is developed using Laravel 7 framework using PHP 7.2

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
 - PHP 7.2 and Laravel 7
 - XDebug must be enabled with PHP 7.2 to generate the code coverage.
 - Angular 9 version 

## Backend setup and Features
 - Details and commands to run
 - Query Optimization: using laravel eloquent query mechanism for retriving details.
 - Backend Security aspect
   - While viewing user wise media file id encrypted in url and entire application.
   - Authentication required while fetching all url except login.

## Frontend setup
 - Details and commands to setup and run
   - Change your directory to Frontend folder
   - Install dependencies using `npm install` command
   - Update your API Base URL in environment file
   - Start your development environment using `ng serve`
   - To generate buid use `ng build`. Use the `--prod` flag for a production build.

## PHPunit Setup and generating Code Coverage
 - PHPUnit 8 is used to implement Unit testing in API.
 - command to run PHPUnit Testcases : 
	```vendor\bin\phpunit```
 - command to generate code coverage : 
	```vendor\bin\phpunit --coverage-html codecoverage```
	 - This command will create the coverage report in folder codecoverage
