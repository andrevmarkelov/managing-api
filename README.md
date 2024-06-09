# REST API Implementation for Managing Departments and Employees

## Overview

A REST API has been implemented using the latest version of the Laravel framework to manage enterprise departments and employees. The API provides JSON responses and supports the following operations for the entities "Departments" and "Employees":

* Retrieve all records with pagination
* Create/Edit records
* Delete records

## Steps to Deploy the Project

1. **Clone the Repository**
    ```bash
    git clone https://github.com/andrevmarkelov/managing-api
    cd managing-api
    ```
   
2. **Install Dependencies**
    ```bash
    composer install
    ```

3. **Set Up Environment Variables**
    ```bash
    cp .env.example .env
    ```

4. **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

5. **Run Migrations and Seed Database**
    ```bash
    php artisan migrate --seed
    ```

6. **Serve the Application**
    ```bash
    php artisan serve
    ```
   Your application should now be running at `http://localhost:8000`.

## Entities

### 1. Department
**Fields:**
- `ID`
- `Name`

### 2. Employee
**Fields:**
- `ID`
- `First Name`
- `Last Name`
- `Middle Name`
- `Gender`
- `Salary`

**Relationships:**
- Many-to-Many relationship with Departments

## Validation Rules

### Creating/Editing an Employee:
**Fields:**
- First Name (required)
- Last Name (required)
- Middle Name (required)
- Gender (optional)
- Salary (required, integer)
- Array of Department IDs (required, at least one department)

### Creating/Editing a Department:
**Fields:**
- Name (required)

## API Endpoints

### Departments
- **GET** `/api/v1/departments/{limit?}`: Retrieve all departments with pagination.
    - **Response:**
        - `ID`
        - `Name`
        - `Employee Count`
        - `Maximum Salary among Employees`
- **POST** `/api/v1/department`: Create a new department.
- **PUT** `/api/v1/department/{id}`: Update an existing department.
- **DELETE** `/api/v1/department/{id}`: Delete a department. Departments with employees cannot be deleted.

### Employees
- **GET** `/api/v1/employees/{limit?}`: Retrieve all employees with pagination.
    - **Response:**
        - `ID`
        - `Full Name` (concatenation of First Name, Last Name, and Middle Name)
        - `Gender`
        - `Salary`
- **POST** `/api/v1/employee`: Create a new employee.
- **PUT** `/api/v1/employee/{id}`: Update an existing employee.
- **DELETE** `/api/v1/employee/{id}`: Delete an employee.

## Security

- **API Token Authentication**: Requests are authenticated using a token specified in the request headers. The token is configured in the `.env` file.

## Implementation Details

### 1. Models and Migrations
- **Department**: Defines the structure and relationships of the department entity.
- **Employee**: Defines the structure and relationships of the employee entity.
- Migrations to create the necessary database tables.

### 2. Factories and Seeders
- Factories to generate dummy data for departments and employees.
- Seeders to populate the database with initial data (10 departments and 20 employees).

### 3. Request Validation
- Custom request classes to handle validation for creating and updating departments and employees.

### 4. Controllers
- Controllers to handle CRUD operations for departments and employees.
- Custom middleware to verify the API token.

### 5. Middleware
- **VerifyApiToken**: Middleware to check the presence and validity of the API token in request headers.
- **ForceJsonResponse**: Ensures that all requests are handled with the `Accept: application/json` header, so responses are always in JSON format.

### 6. Routes
- Defined routes for handling API requests, grouped and protected by middleware for token authentication.

### 7. Error Handling
- JSON responses for validation errors, creation errors, update errors, and deletion errors.

### 8. Testing
- Feature tests are written to ensure that the API works correctly. The tests include creating, updating, and deleting departments and employees, as well as checking the responses.

![Test results](https://i.imgur.com/e7WcGrC.png)

## Example Requests

### Authorization Header

```
Authorization: Bearer Y7XDloVuwtg1MGCPWJMJ5soHVsscQD5vtu5h6kzR7ify0PyYzrLNSrB97mXsWuZ4
```

#### Creating a Department:

```http request
POST /api/v1/department
Content-Type: application/json

{
  "name": "IT Department"
}
```

#### Creating an Employee:

```http request
POST /api/v1/employee
Content-Type: application/json

{
  "first_name": "John",
  "last_name": "Doe",
  "middle_name": "Smith",
  "gender": "male",
  "salary": 50000,
  "departments": [1, 2]
}
```

#### Updating an Employee:

```http request
PUT /api/v1/employee/1
Content-Type: application/json

{
  "first_name": "Jane",
  "last_name": "Doe",
  "middle_name": "Smith",
  "gender": "female",
  "salary": 60000,
  "departments": [1, 3]
}
```

#### Deleting a Department:

```http request
DELETE /api/v1/department/1
```

#### Deleting an Employee:

```http request
DELETE /api/v1/employee/1
```
