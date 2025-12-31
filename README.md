# PHP_Laravel12_Custome_Validation_Error_Using_Vue.JS

---

## Introduction

This project is a simple **user registration form** built with **Laravel 12** and **Vue.js (CDN version)**.  
It demonstrates how to handle **custom server-side validation errors** and display them dynamically in the frontend using Vue.js.  

---


## Key points of this project:  

* Laravel handles backend validation for `name`, `email`, and `password`.  
* Password validation includes **minimum 8 characters**, at least **1 uppercase**, **1 lowercase**, and **1 special character**.  
* Vue.js fetches validation errors via Axios and displays them next to the corresponding input fields.  
* Successfully validated data is stored in the database, and a success message is displayed above the form.  

---

##  Project Overview

This project demonstrates **Custom Validation Error Handling using Vue.js with Laravel 12**.

The main goal of this project is to:

* Submit a form using Vue.js (CDN)
* Validate input on the Laravel backend
* Return **custom validation error messages**
* Display errors dynamically in Vue.js
* Store valid data into the database

---

##  Technologies Used

* **Laravel 12** (Backend)
* **Vue.js (CDN)** (Frontend)
* **Axios** (AJAX requests)
* **MySQL** (Database)
* **Bootstrap 5** (UI)

---

## Step 1: Project Creation (Laravel 12 Command)

```bash
composer create-project laravel/laravel PHP_Laravel12_Custome_Validation_Error_Using_Vue.JS "12.*"
```

```bash
cd PHP_Laravel12_Custome_Validation_Error_Using_Vue.JS
```

```bash
php artisan serve
```

---

## Step 2: Environment Configuration (.env)

Create `.env` file (if not exists):

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Update database settings:

```env
DB_DATABASE=vue_custome_validation
DB_USERNAME=root
DB_PASSWORD=
```

Create database using this command:

```bash
php artisan migrate
```

---


## Step 3: Create Model & Migration

```bash
php artisan make:model UserForm -m
```

File: `database/migrations/create_user_forms_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Anonymous class for migration
return new class extends Migration {

    // Run the migration: create the table
    public function up(): void
    {
        Schema::create('user_forms', function (Blueprint $table) {
            $table->id();              // Primary key column: id
            $table->string('name');    // Column for user's name
            $table->string('email');   // Column for user's email
            $table->string('password');// Column for user's password
            $table->timestamps();      // Adds created_at and updated_at columns
        });
    }

    // Reverse the migration: drop the table
    public function down(): void
    {
        Schema::dropIfExists('user_forms');
    }
};
```

Run migration:

```bash
php artisan migrate
```


File: `app/Models/UserForm.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserForm extends Model
{
    protected $table = 'user_forms'; // Specify table name

    // Columns that can be mass-assigned
    protected $fillable = [
        'name',
        'email',
        'password'
    ];
}
```

---


## Step 4: Create Controller

```bash
php artisan make:controller UserController
```

File: `app/Http/Controllers/UserController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Used for validating form inputs
use App\Models\UserForm;                  // Model to interact with 'user_forms' table

class UserController extends Controller
{
    // Load the form view
    public function index()
    {
        // Return the Blade view 'user-form.blade.php' to the user
        return view('user-form');
    }

    // Handle form submission
    public function store(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',   // Name is required, minimum 3 characters
            'email' => 'required|email',  // Email is required and must be valid format
            'password' => [
                'required',               // Password is required
                'min:8',                  // Minimum 8 characters
                'regex:/[a-z]/',          // Must contain at least one lowercase letter
                'regex:/[A-Z]/',          // Must contain at least one uppercase letter
                'regex:/[@$!%*#?&]/',     // Must contain at least one special character
            ],
        ], [
            // Custom error messages for each validation rule
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 3 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Enter a valid email',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain at least 1 uppercase, 1 lowercase, and 1 special character',
        ]);

        // If validation fails, return JSON response with errors and status code 422
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors() // Contains field-specific error messages
            ], 422);
        }

        // Save validated data into the database
        UserForm::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Encrypt password before saving
        ]);

        // Return success response as JSON
        return response()->json([
            'status' => true,
            'message' => 'User saved successfully'
        ]);
    }
}
```

---


## Step 5: Routes Configuration

File: `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;



// Route::get('/', [UserController::class, 'index']);
// Load the user form
Route::get('/user-form', [UserController::class, 'index'])->name('user.form');
Route::post('/submit-form', [UserController::class, 'store']);

Route::get('/', function () {
    return view('welcome');
});
```

---

## Step 6: Vue.js Form View

File: `resources/views/user-form.blade.php`

```html
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laravel 12 Vue Validation</title>

    <!-- CSRF token for security in AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vue 3 and Axios CDN for reactive form handling and HTTP requests -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        body {
            background: #f5f7fa; /* Light background color for the page */
        }

        .form-card {
            max-width: 500px; /* Card width */
            margin: 50px auto; /* Center the card vertically and horizontally */
            padding: 30px; /* Inner spacing */
            background: #fff; /* White background for the card */
            border-radius: 12px; /* Rounded corners */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); /* Subtle shadow for 3D effect */
        }

        .alert {
            border-radius: 8px; /* Rounded corners for alerts */
        }
    </style>
</head>

<body>
    <div id="app">
        <!-- Main Vue app container -->

        <div class="form-card">
            <!-- Form card container -->
            <h3 class="mb-4 text-center">Register User</h3>

            <!-- Success message displayed above the form -->
            <div class="alert alert-success text-center" v-if="successMessage">
                @{{ successMessage }} <!-- Dynamic success message from Vue -->
            </div>

            <form @submit.prevent="submitForm">
                <!-- Form submission handled by Vue's submitForm method -->

                <!-- Name input -->
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" v-model="form.name" class="form-control" placeholder="Enter your name">
                    <!-- Display validation error if name has error -->
                    <small class="text-danger" v-if="errors.name">@{{ errors.name[0] }}</small>
                </div>

                <!-- Email input -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" v-model="form.email" class="form-control" placeholder="Enter your email">
                    <!-- Display validation error if email has error -->
                    <small class="text-danger" v-if="errors.email">@{{ errors.email[0] }}</small>
                </div>

                <!-- Password input -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" v-model="form.password" class="form-control" placeholder="Enter password">

                    <!-- Instruction text for password rules -->
                    <small class="text-muted d-block mb-1">
                        Must be at least 8 characters, include 1 uppercase, 1 lowercase, and 1 special character
                    </small>

                    <!-- Display validation error if password has error -->
                    <small class="text-danger d-block">
                        <span v-if="errors.password">@{{ errors.password[0] }}</span>
                    </small>
                </div>

                <!-- Submit button -->
                <div class="d-grid">
                    <button class="btn btn-primary btn-lg">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Link to external Vue.js app script -->
    <script src="/js/app.js"></script>
</body>

</html>
```

---


## Step 7: app.js 

File: `public/js/app.js`

```
// Import Vue's createApp function
const { createApp } = Vue;

// Create a new Vue application
createApp({
    // Reactive data for the app
    data() {
        return {
            form: { name: '', email: '', password: '' }, // Form input values
            errors: {}, // Object to store validation errors returned from server
            successMessage: '' // Success message to show when form submission is successful
        }
    },
    // Methods for handling actions
    methods: {
        // Method to handle form submission
        submitForm() {
            this.errors = {}; // Clear previous errors
            this.successMessage = ''; // Clear previous success message

            // Send POST request to server with form data
            axios.post('/submit-form', this.form)
                .then(response => {
                    // If server returns success
                    if (response.data.status) {
                        this.successMessage = response.data.message; // Set success message
                        this.form = { name: '', email: '', password: '' }; // Clear form inputs
                    }
                })
                .catch(error => {
                    // If validation errors returned from server
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors; // Populate errors object
                    }
                });
        }
    }
// Mount the Vue app to the HTML element with id="app"
}).mount('#app');
```

---


##  Project Structure

```
PHP_Laravel12_Custome_Validation_Error_Using_Vue.JS
│
├── app/
│   └── Http/
│       └── Controllers/
│           └── UserController.php
│
├── app/Models/
│   └── UserForm.php
│
├── database/
│   └── migrations/
│       └── create_user_forms_table.php
│
├── public/
│   └── js/
│       └── app.js
│
├── resources/views/
│   └── user-form.blade.php
│
├── routes/
│   └── web.php
│
├── .env
└── README.md
```

---


## Output

**Validation Error**

<img width="1919" height="1030" alt="Screenshot 2025-12-31 110712" src="https://github.com/user-attachments/assets/c5ac9ffd-fea9-49bc-af5b-7612f0fd4bf1" />

<img width="1919" height="1028" alt="Screenshot 2025-12-31 110848" src="https://github.com/user-attachments/assets/d7be3786-baec-4152-bf7b-2cec900a03a6" />


**User Registration**

<img width="1915" height="1029" alt="Screenshot 2025-12-31 110934" src="https://github.com/user-attachments/assets/41b8532c-336e-47fa-86be-53027b72e4aa" />


---


##  How This Project Works

1. User fills the form
2. Vue sends data via Axios
3. Laravel validates input
4. Custom errors returned (JSON)
5. Vue displays errors instantly
6. Valid data stored in database


---


Your PHP_Laravel12_Custome_Validation_Error_Using_Vue.JS Project is Now Ready!



