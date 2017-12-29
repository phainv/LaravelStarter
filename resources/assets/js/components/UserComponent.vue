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
                                </div>
                                
                                <div class="form-group">
                                    Status: {{ user.active ? 'Active' : 'In-Active' }}
                                    <select v-if="editUser" v-model="form.user.active" class="form-control input-sm">
                                        <option v-for="option in data.status" v-bind:value="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    Address: <span>{{ user.address }}</span>
                                    <input v-if="editUser" type="text" placeholder="New Address"
                                        v-model="form.user.address" class="form-control input-sm">
                                </div>

                                <div class="form-group">
                                    Mobile: <span>{{ user.mobile }}</span>
                                    <input v-if="editUser" type="text" placeholder="New Mobile Number"
                                        v-model="form.user.mobile" class="form-control input-sm">
                                </div>

                                <div class="form-group">
                                    Status: {{ user.gender }}
                                    <select v-if="editUser" v-model="form.user.gender" class="form-control input-sm">
                                        <option v-for="option in data.gender" v-bind:value="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button @click="editUser = !editUser"
                                class="btn btn-info">{{ editUser ? 'Cancel' : 'Edit' }}</button>

                                <button @click="updateUser" v-if="editUser" class="btn btn-primary">Update</button>
                            </div>
                        </div>

                        <hr/>

                        <h4>List all user accounts:</h4>
                            <a @click="addMoreAccount"class="btn btn-primary btn-sm btn-outline m-b">
                                Add more account
                            </a>

                            <div class="clearfix"></div>
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
                                            <td>
                                                <span v-if="account.currency">{{ account.currency }}</span>
                                                <label v-else class="label label-info">Vitural Account</label>
                                            </td>
                                            <td>{{ account.daily_limit }}</td>
                                            <td>

                                                <label v-if="account.default" class="label label-primary">Default Account</label>
                                                <a href="#" @click="makeDefaultAccount(account)"
                                                    class="label label-default" v-else-if="account.currency">Make default</a>
                                            </td>
                                            <td>{{ account.status }}</td>
                                            <td>{{ account.created_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else class="alert alert-info text-center">
                                Accounts empty
                            </div>

                        <hr/>

                        <h4>Manage by Account:</h4>
                        <div class="form-group">
                            <select class="form-control" v-model="accountId" @change="selectAccount">
                                <option value="">-- Select Account --</option>
                                <option v-for="account in user.accounts" v-bind:value="account.id">
                                    <!-- {{ account.account_number }} - ( Currency: {{ account.currency ? account.currency : 'Virtual' }} ) -->
                                    {{ account.account_number }} - {{ account.currency ? `Currency: ${account.currency}` : 'Virtual Currency' }}
                                </option>
                            </select>
                        </div>

                        <div class="clearfix"></div>

                        <div class="row" v-if="accountId && seletectedAccount">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <h4>Top-up</h4>
                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="USD">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default" type="submit">
                                                    <i class="glyphicon glyphicon-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <h4>Withdraw money</h4>
                                        <div class="input-group" v-if="!account.is_virtual">
                                            <input type="number" class="form-control" placeholder="USD">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default" type="submit">
                                                    <i class="glyphicon glyphicon-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="virtual-alert" v-else>
                                            It is not possible to do money withdrawal from virtual currency account
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <h4>Activities</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Account Number</th>
                                                <th>Type</th>
                                                <th>Cash</th>
                                                <th>Status</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="transaction in account.transactions">
                                                <td>{{ account.account_number }}</td>
                                                <td>{{ transaction.type }}</td>
                                                <td>{{ transaction.cash }}</td>
                                                <td>{{ transaction.status }}</td>
                                                <td>{{ transaction.note }}</td>
                                            </tr>
                                        </tbody>
                                      </table>
                                      <!-- <p v-else>
                                          <p class="text-center">No activities</p>
                                      </p> -->
                                </div>
                            </div>
                        </div>

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
                account: {},
                editUser: false,
                accountId: '',
                data: {
                    status: [
                        { text: 'Active', value: 1 },
                        { text: 'In-Active', value: 0 }
                    ],
                    gender: [
                        { text: 'Other', value: 'other' },
                        { text: 'Male', value: 'male' },
                        { text: 'Female', value: 'female' }
                    ]
                },
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
        computed: {
            seletectedAccount () {
                return this.user.accounts.find(m => m.id == this.accountId) || {}
            }
        },
        methods: {
            fetchUser() {
                this.$http.get(`users/${this.id}`).then(({ data }) => {
                    this.user = data.data
                })
            },
            updateUser() {
                let $this = this
                this.$swal({
                    text: 'Are you sure you want update info user?',
                    type: 'question',
                    showCancelButton: true
                }).then( function () {
                    $this.$http.put(`users/${$this.id}`, $this.form.user).then(({ data }) => {
                        $this.editUser = false
                        $this.user = data.data
                        $this.form.user = {}
                    }).catch(({ response }) => {})
                }, function (dismiss) {})
                
            },
            makeDefaultAccount(account) {
                let $this = this
                this.$swal({
                    html: `Make <span class="text-primary">${account.account_number}</span> is default account?`,
                    type: 'question',
                    showCancelButton: true
                }).then( function () {
                    $this.$http.put(`accounts/${account.id}/default`).then(({ data }) => {
                        $this.fetchUser()
                    }).catch(({ response }) => {})
                }, function (dismiss) {})
            },
            addMoreAccount() {
                let $this = this
                this.$swal({
                    html: `Choose currency and create new account`,
                    type: 'info',
                    input: 'select',
                    inputClass: 'form-control',
                    confirmButtonText: 'Create new account',
                    inputOptions: {
                        '': 'Vitural Account',
                        'USD': 'Dollars',
                        'EUR': 'Euro'
                    },
                    showCancelButton: true
                }).then( function (input) {
                    console.log(input)
                    $this.$http.post(`accounts/add`, {'currency': input, 'user_id' : $this.id}).then(({ data }) => {
                        $this.fetchUser()
                    }).catch(({ response }) => {})
                }, function (dismiss) {})
            },
            selectAccount() {
                this.fetchAccount();
            },
            fetchAccount() {
                this.$http.get(`accounts/${this.accountId}`).then(({ data }) => {
                    this.account = data
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .virtual-alert {
        margin-bottom: 14px;
    }
</style>
