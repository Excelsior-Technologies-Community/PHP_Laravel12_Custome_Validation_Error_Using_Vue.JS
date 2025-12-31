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
