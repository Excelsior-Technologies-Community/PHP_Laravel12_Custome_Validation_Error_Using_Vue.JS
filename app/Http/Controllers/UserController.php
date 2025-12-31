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
