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
                                            <th>Topup Limit</th>
                                            <th>Withdraw Limit</th>
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
                                            <td>
                                                {{ account.topup_limit }}
                                                <a @click="changeAccountLimit(account, 'topup_limit')" class="label label-primary">
                                                    {{ account.topup_limit ? 'Change' : 'Not set' }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ account.withdraw_limit }}
                                                <a @click="changeAccountLimit(account, 'withdraw_limit')" class="label label-primary">
                                                    {{ account.withdraw_limit ? 'Change' : 'Not set' }}
                                                </a>
                                            </td>
                                            <td>

                                                <label v-if="account.default" class="label label-primary">Default Account</label>
                                                <a href="#" @click="makeDefaultAccount(account)"
                                                    class="label label-default" v-else-if="account.currency">Make default</a>
                                            </td>
                                            <td>
                                                {{ account.status ? 'Active' : 'Freezed' }} - 
                                                <a @click="makeFreezeAccount(account, 'freeze')"
                                                    v-if="account.status"
                                                    class="label label-default">Freeze</a>

                                                <a @click="makeFreezeAccount(account, 'unfreeze')"
                                                    v-else class="label label-danger">Unfreeze</a>
                                            </td>
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

                        <div v-if="accountId && seletectedAccount">
                            <div class="row" v-if="seletectedAccount.status">
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <h4>Top-up</h4>
                                            <div class="input-group">
                                                <input type="number"
                                                    v-model.number="amounts.topup.cash"
                                                    class="form-control" :placeholder="seletectedAccount.currency">
                                                <div class="input-group-btn">
                                                    <button @click="makeTransactionAccount(seletectedAccount, 'topup')"
                                                        class="btn btn-default" type="submit">
                                                        <i class="glyphicon glyphicon-plus"></i>
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
                                                <input type="number"
                                                    v-model.number="amounts.withdraw.cash"
                                                    class="form-control" :placeholder="seletectedAccount.currency">
                                                <div class="input-group-btn">
                                                    <button @click="makeTransactionAccount(seletectedAccount, 'withdraw')"
                                                        class="btn btn-default" type="submit">
                                                        <i class="glyphicon glyphicon-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="virtual-alert" v-else>
                                                It is not possible to do money withdrawal from virtual currency account
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="alert alert-danger text-center">
                                Accounts has been freezed
                            </div>
                            
                            <div>
                                <h4>Activities</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Account Number</th>
                                                <th>Type</th>
                                                <th>Cash</th>
                                                <th>Status</th>
                                                <th>Reason</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="transaction in account.transactions">
                                                <td>{{ account.account_number }}</td>
                                                <td>{{ transaction.type }}</td>
                                                <td>{{ transaction.cash }}</td>
                                                <td>
                                                    <label v-if="transaction.status == 'successful'" class="label label-primary">
                                                        {{ transaction.status }}
                                                    </label>

                                                    <label v-else class="label label-danger">
                                                        {{ transaction.status }}
                                                    </label>
                                                </td>
                                                <td>{{ transaction.reason_message }}</td>
                                                <td>{{ transaction.created_at }}</td>
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
                amounts: {
                    topup: {
                        cash: null
                    },
                    withdraw: {
                        cash: null
                    }
                },
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
                    $this.$http.post(`accounts/add`, {'currency': input, 'user_id' : $this.id}).then(() => $this.fetchUser())
                    .catch(({ response }) => {})
                }, function (dismiss) {})
            },
            selectAccount() {
                this.fetchAccount();
            },
            fetchAccount() {
                this.$http.get(`accounts/${this.accountId}`).then(({ data }) => {
                    this.account = data
                })
            },
            changeAccountLimit(account, type) {
                let $this = this
                $this.$swal({
                  html: `Change daily <b>${type}</b> of account <span class="text-primary">${account.account_number}</span>`,
                  input: 'number',
                  showCancelButton: true,
                  confirmButtonText: 'Change'
                }).then((result) => {
                    if (!result) { return }

                    $this.$http.put(`accounts/${account.id}/change-daily-limit`, { value: result, type: type }).then(() => $this.reloadData())
                    .catch(({ response }) => {})
                }, function (dismiss) {})
            },
            makeFreezeAccount(account, type) {
                let $this = this
                this.$swal({
                    html: `Are you sure you want <b>${type}</b> account <span class="text-primary">${account.account_number}<span>?`,
                    type: 'question',
                    showCancelButton: true
                }).then( function () {
                    $this.$http.put(`accounts/${account.id}/${type}`, { type: type }).then(() => $this.reloadData())
                    .catch(({ response }) => {})
                }, function (dismiss) {})
            },
            makeTransactionAccount(account, type) {
                var cash
                if (type == 'topup') {
                    cash = this.amounts.topup.cash
                } else if (type == 'withdraw') {
                    cash = this.amounts.withdraw.cash
                }

                if (!cash) { return }

                let $this = this
                this.$swal({
                    html: `Are you sure you want <b>${type}</b> account <span class="text-primary">${account.account_number}<span>?`,
                    type: 'question',
                    showCancelButton: true
                }).then( function () {
                    $this.$http.post(`accounts/${account.id}/new/transaction`, {
                        type: type,
                        cash: cash
                    }).then(({ data }) => {
                        $this.reloadData();
                        $this.amounts.topup.cash = null
                        $this.amounts.withdraw.cash = null
                    }).catch(({ response }) => {})
                }, function (dismiss) {})
            },
            reloadData() {
                this.fetchAccount()
                this.fetchUser()
            }
        }
    }
</script>

<style lang="scss" scoped>
    .virtual-alert {
        margin-bottom: 14px;
    }
</style>
