# Contact Form 



## How to install 

### Prerequies 

- PHP 7.2 or upper 
- [composer](https://getcomposer.org/) 

### Installation

1. into the project folder execute in the terminal the following command : `composer install` to install the dependencies

2. edit the file **.env**

3. create the database by typing : `php bin/console do:da:create` 

4. execute migration script  by typing : `php bin/console do:mi:mi`

5. inject the fake data by typing : `php bin/console do:fi:lo`


## API 

POST /api/contact 

example data to send : 
```json
{
    "department": "1",
    "firstname": "Foo",
    "lastname": "Bar",
    "mail": "foo@example.com",
    "message": "lorem ipsum"
}
```

GET /api/departments 

GET /api/department/{id}

example data get : 
```json
{
    "id": "1", 
    "name": "Dev"
}
```
