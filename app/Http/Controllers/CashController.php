<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankRequest;
use App\Http\Requests\BankUpdateRequest;
use App\Http\Requests\CashRequest;
use App\Http\Requests\CashUpdateCheckBalance;
use App\Http\Requests\UpdateCashRequest;
use App\Http\Requests\UpdateCheckBalanceRequest;
use App\Models\Bank;
use App\Models\Cash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cash = Cash::query()->where('organization_id', auth()->user()->organization->id)->get();
        return response()->json($cash);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CashRequest $request)
    {
        $cash = Cash::create($request->all() + ['current_balance' => $request->actual_balance,'organization_id' => auth()->user()->organization->id]);

        return response()->json(['message' => 'Cash added successfully', 'cash' => $cash], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $cash = Cash::find($id);
        return response()->json($cash);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCashRequest $request, $id)
    {
        $cash = Cash::findOrFail($id);
        Cash::where('id', $id)->update($request->all());
        return response()->json(['message' => 'cash updated successfully', 'cash' => $cash], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Cash $cash)
    {
        $cash->delete();
        return response()->json(['message' => 'cash deleted successfully'], 200);
    }
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_balance($id)
    {
        $cash = Cash::findOrFail($id);
        if (!$cash) {
            return response()->json(data: ['message' => 'Cash not found'], status: 200);

        }
        $difference = $cash->actual_balance - $cash->current_balance;


        return response()->json(data: ['bank' => $cash, 'difference' => $difference, 'message' => 'Check balance got successfully'], status: 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_check_balance($id, CashUpdateCheckBalance $request)
    {
        $cash = Cash::findOrFail($id);
        if (!$cash) {
            return response()->json(data: ['message' => 'Cash not found'], status: 200);

        }
        $cash->update([
            'actual_balance' => $request->actual_balance,


        ]);
        $difference = $cash->actual_balance - $cash->current_balance;

        return response()->json(data: ['bank' => $cash, 'difference' => $difference, 'message' => 'update Check balance got successfully'], status: 200);
    }

}
