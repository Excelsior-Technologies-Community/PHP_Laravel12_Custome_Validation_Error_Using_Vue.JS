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
