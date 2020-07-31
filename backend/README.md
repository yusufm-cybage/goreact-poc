 

## goreact-poc

## runing backend

open command prompt 

goto backend folder on root folder hit following command

```bash
$php artisan serve
```


## url end points

## Register User 

```python

POST method with headers require 

Content-Type:application/json
X-Requested-With:XMLHttpRequest

http://localhost:8000/api/register

name, email, password, password_confirmation need to be sent

```

## Login User 

```python

POST method with headers require 

Content-Type:application/json
X-Requested-With:XMLHttpRequest
 
http://localhost:8000/api/login

email, password

raw user data

username : johndoe@test.com
password : password

username : user@test.com
password : password

username : guest@test.com
password : password

```

## LogOut User 

```python

get method with headers require 

Content-Type:application/json
X-Requested-With:XMLHttpRequest
Bearer tokenvalue

http://localhost:8000/api/logout

 

```

## User detail

```python

get method with headers require 

Content-Type:application/json
X-Requested-With:XMLHttpRequest
Bearer tokenvalue

http://localhost:8000/api/user

 

```
