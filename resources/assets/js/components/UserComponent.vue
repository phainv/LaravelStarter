<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12" v-if="user">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>{{ user.full_name }}</b></div>

                    <div class="panel-body">
                        <h4>Customer infomations:</h4>
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    Full Name: <span>{{ user.full_name }}</span>
                                    <div class="row" v-if="editUser">
                                        <div class="col-md-6">
                                            <input type="text" placeholder="New First Name"
                                            v-model="form.user.first_name" class="form-control input-sm">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" placeholder="New Last Name"
                                            v-model="form.user.last_name" class="form-control input-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    Email: <span>{{ user.email }}</span>
                                    <input v-if="editUser" type="email" placeholder="New Email"
                                        v-model="form.user.email" class="form-control input-sm">
                                    <span v-else>{{ user.email }}</span>
                                </div>
                                
                                <div class="form-group">
                                    Status: {{ user.active ? 'Active' : 'In-Active' }}
                                    <select v-if="editUser" v-model="form.user.status" class="form-control">
                                        <option value="">-- Select Status --</option>
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    Address: <span>{{ user.address }}</span>
                                    <input v-if="editUser" type="text" placeholder="New Address"
                                        v-model="form.user.address" class="form-control input-sm">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button @click="editUser = !editUser" class="btn btn-info">Edit user</button>
                            </div>
                        </div>

                        <hr/>

                        <h4>List all user accounts:</h4>
                            <div class="table-responsive" v-if="user.accounts">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Account Number</th>
                                            <th>Amount</th>
                                            <th>Currency</th>
                                            <th>Daily Limit</th>
                                            <th>Default</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="account in user.accounts" :key="account.id">
                                            <td><b>{{ account.account_number }}</b></td>
                                            <td>{{ account.amount }}</td>
                                            <td>{{ account.currency }}</td>
                                            <td>{{ account.daily_limit }}</td>
                                            <td>{{ account.default }}</td>
                                            <td>{{ account.status }}</td>
                                            <td>{{ account.created_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-info text-center">
                                Accounts empty
                            </div>

                        <center><a href="" class="btn btn-info">Manage</a></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'user',
        data() {
            return {
                user: {},
                editUser: false,
                form: {
                    user: {}
                }
            }
        },
        props: {
            id: {
                type: Number,
                required: true
            }
        },
        mounted() {
            this.fetchUser()
        },
        methods: {
            fetchUser() {
                this.$http.get(`users/${this.id}`).then(({ data }) => {
                    this.user = data.data
                })
            }
        }
    }
</script>
