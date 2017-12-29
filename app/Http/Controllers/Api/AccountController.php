<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Contracts\CardGenerator;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        return $account->load('transactions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Make defalt account.
     *
     * @param  \App\Models\Account $account
     * @return \Illuminate\Http\Response
     */
    public function makeDefault($id)
    {
        $account = Account::findOrFail($id);

        if (! $account->isVirtual()) {
            Account::where('user_id', $account->user_id)->update(['default' => 0]);
            $account->update(['default' => 1]);
        }
    }

    /**
     * Add more account.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function addMoreAccount(Request $request, CardGenerator $generator)
    {
        $this->validate($request, [
            'currency' => 'nullable|in:USD,EUR',
            'user_id' => 'required',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->accounts()->save(new Account([
            'account_number' => $generator->generate(),
            'currency' => $request->currency ?: null,
        ]));
    }
}
