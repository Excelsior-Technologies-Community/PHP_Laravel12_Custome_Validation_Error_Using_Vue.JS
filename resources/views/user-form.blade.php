<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <style>
        body {
            background: #f4f6f9;
        }

        /* Navbar */
        .navbar {
            background: #343a40;
        }

        .navbar a {
            color: #fff !important;
        }

        /* Card */
        .card-box {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Table */
        .table th {
            background: #343a40;
            color: #fff;
        }

        .btn {
            border-radius: 8px;
        }

        /* Success message */
        .alert {
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar p-3">
        <div class="container-fluid">
            <h4 class="text-white">Dashboard</h4>
            <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>

    <div id="app" class="container mt-4">

        <div class="card-box">

            <h4 class="mb-3">User Management</h4>

            <!-- SUCCESS MESSAGE -->
            <div class="alert alert-success" v-if="successMessage">
                @{{ successMessage }}
            </div>

            <!-- SEARCH -->
            <input type="text" v-model="search" @keyup="fetchUsers" placeholder="Search user..."
                class="form-control mb-3">

            <!-- TABLE -->
            <table class="table table-bordered text-center">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <tr v-for="user in users" :key="user.id">
                    <td>@{{ user.name }}</td>
                    <td>@{{ user.email }}</td>

                    <td>
                        <span class="badge bg-success" v-if="user.status">Active</span>
                        <span class="badge bg-secondary" v-else>Inactive</span>
                    </td>

                    <td>
                        <button @click="toggleStatus(user.id)" class="btn btn-info btn-sm">Toggle</button>
                        <button @click="deleteUser(user.id)" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
            </table>

        </div>
    </div>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    users: [],
                    search: '',
                    successMessage: '' // <-- Success message
                }
            },

            mounted() {
                this.fetchUsers();
            },

            methods: {

                fetchUsers() {
                    axios.get('/get-users?search=' + this.search)
                        .then(res => {
                            this.users = res.data;
                        });
                },

                deleteUser(id) {
                    if (confirm("Delete user?")) {
                        axios.get('/delete/' + id)
                            .then(res => {
                                this.successMessage = res.data.message; // show success
                                this.fetchUsers();
                                setTimeout(() => this.successMessage = '', 3000); // hide after 3s
                            })
                            .catch(err => console.error(err));
                    }
                },

                toggleStatus(id) {
                    axios.get('/status/' + id)
                        .then(res => {
                            this.successMessage = res.data.message; // show success
                            this.fetchUsers();
                            setTimeout(() => this.successMessage = '', 3000); // hide after 3s
                        })
                        .catch(err => console.error(err));
                }
            }

        }).mount('#app');
    </script>

</body>

</html>