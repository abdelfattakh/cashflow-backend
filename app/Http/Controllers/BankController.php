<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Http\Requests\BankUpdateRequest;
use App\Http\Requests\UpdateCheckBalanceRequest;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $banks = Bank::query()->where('organization_id', auth()->user()->organization->id)->get();
        return response()->json($banks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BankRequest $request)
    {
        $bank = Bank::create($request->all() + ['current_balance' => $request->actual_balance, 'organization_id' => auth()->user()->organization->id]);

        return response()->json(['message' => 'Bank added successfully', 'Bank' => $bank], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $bank = Bank::find($id);
        return response()->json($bank);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BankUpdateRequest $request, $id)
    {
        $bank = Bank::findOrFail($id);
        Bank::where('id', $id)->update($request->all());
        return response()->json(['message' => 'Bank updated successfully', 'bank' => $bank], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();
        return response()->json(['message' => 'Bank deleted successfully'], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_balance($id)
    {
        $bank = Bank::findOrFail($id);
        if (!$bank) {
            return response()->json(data: ['message' => 'Bank not found'], status: 200);

        }
        $difference = $bank->actual_balance - $bank->current_balance;


        return response()->json(data: ['bank' => $bank, 'difference' => $difference, 'message' => 'Check balance got successfully'], status: 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_check_balance($id, UpdateCheckBalanceRequest $request)
    {
        $bank = Bank::findOrFail($id);
        if (!$bank) {
            return response()->json(data: ['message' => 'Bank not found'], status: 200);

        }
        $bank->update([
            'actual_balance' => $request->actual_balance,


        ]);
        $difference = $bank->actual_balance - $bank->current_balance;

        return response()->json(data: ['bank' => $bank, 'difference' => $difference, 'message' => 'update Check balance got successfully'], status: 200);
    }
}
