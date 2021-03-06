## API Spec

### Register API

-   Endpoint : `/auth/users`
-   Http Method : POST
-   Header :
    -   Content-Type: `application/json`
    -   Accept: `application/json`
-   Body Request :

```json
{
    "username": "optional",
    "email": "optional(if phone exists)",
    "phone": "optional(if email exists)",
    "password": "required"
}
```

-   Body Response Success :

```json
{
    "code": 200,
    "status": "OK",
    "message": "Success, register user",
    "data": {
        "id": "random",
        "username": "username",
        "phone": "phone",
        "email": "email",
        "created_at": "date, created_at"
    }
}
```

-   Body Response Failed :

```json
{
    "code": 400,
    "status": "BAD_REQUEST",
    "message": "password: required, xxx: required"
}
```

## Scenario

You have been tasked to build a food ordering system.
The system will allow users to select a restaurant and it will shows up a menu of the dishes
that the restaurant has to offer.
You users will then have to select the dishes they want with the amount of quantity and an
ability to have a special request field for them to type a note regarding that dish e.g
additional chilli sauce.
Your project manager has carried out the requirement gathering and has provided you with
the following requirements:

1. Provide CRUD functionality for Restaurant aka Vendor
2. Provide endpoint to retrieve dishes for the specific restaurant
3. Provide endpoint to make order and list the orders request
4. Validation rules
   a. Restaurant name should be less than 128 characters
   b. Order special request field could be of any length
5. Extends Restaurant aka Vendor to allow simple query filtering using Tags e.g
   `api/v1/vendors?tags[]=promo&tags[]=featured`
   Your project manager has provided you with some initial code. Please have a look at figure
   out what are existingly there. You are free to add any necessary code to the project and
   decide the schema of the response of the API endpoints.
   You are not required to provide any UI for this project.
   To get started on this project please load the provided code files and run the following
   command:
   php artisan migrate:refresh --seed

## Point to Note

1. Code should follow PHP best practices and OOP Best Practices
2. Utilize functionality provided by framework Laravel where relevant
3. All APIs should conform to RESTful Standards
4. No APIs authentication is required
5. Validation should be included where necessary
6. Write necessary database migrations using Laravel Migrations
7. All responses from API should be in JSON Format
8. Where possible you should document your code
   #   y u m f o o d 
    
    
