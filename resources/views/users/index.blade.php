@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">

            @if($users->isNotEmpty())
                @foreach($users as $user)
                    <div class="panel panel-default">
                        <div class="panel-heading"><b>{{ $user->full_name }}</b></div>

                        <div class="panel-body">
                            <h4>Customer infomations:</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Full Name: {{ $user->full_name }}</p>
                                    <p>Email: {{ $user->email }}</p>
                                    <p>Status: {{ $user->active ? 'Active' : 'In-Active' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p>Mobile: {{ $user->mobile }}</p>
                                    <p>Address: {{ $user->address }}</p>
                                    <p>Gender: {{ $user->gender }}</p>
                                </div>
                            </div>

                            <hr/>

                            <h4>List all user accounts:</h4>
                            @if($user->accounts->isNotEmpty())
                                <div class="table-responsive">
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
                                            @foreach($user->accounts as $account)
                                                <tr>
                                                    <td><b>{{ $account->account_number }}</b></td>
                                                    <td>{{ $account->amount }}</td>
                                                    <td>{{ $account->currency }}</td>
                                                    <td>{{ $account->daily_limit }}</td>
                                                    <td>{{ $account->default }}</td>
                                                    <td>{{ $account->status }}</td>
                                                    <td>{{ $account->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    Accounts empty
                                </div>
                            @endif

                            <center>
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-info">Manage</a>
                            </center>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info text-center">
                    Users empty
                </div>
            @endif
        </div>
    </div>
</div>
@endsection