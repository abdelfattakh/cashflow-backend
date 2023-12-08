<?php

namespace App\Http\Controllers;

use App\Enums\ItemStatusEnum;
use App\helpers\ApiResponse;
use App\Http\Requests\FilterItemRequest;
use App\Http\Requests\FilterLogRequest;
use App\Http\Requests\ItemRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Requests\SearchItemRequest;
use App\Http\Requests\SendEmailRequest;
use App\Http\Requests\StatisticsRequest;
use App\Http\Requests\TodayTaskRequest;
use App\Mail\SendItemsEmail;
use App\Models\Bank;
use App\Models\Cash;
use App\Models\Company;
use App\Models\Item;
use App\Models\Log;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Js;

class ItemController extends Controller
{
    use ApiResponse;

//totoal in home + +user crud + add cash to company

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approved_item($id): JsonResponse
    {

        $item = Item::find($id);
        if (is_null($item) || $item->date > date('Y-m-d')) {
            return response()->json(['message' => "can't approve a future item"]);

        }
        if (!filled($item->transactionable)) {
            return response()->json(['message' => 'there is no bank for item choose one']);

        }

        if ($item->status == ItemStatusEnum::paid()->value) {
            $item->update(['status' => ItemStatusEnum::pending()->value]);

            $item->transactionable->update(['current_balance' => $item->transactionable->current_balance - ($item->value)]);

            return response()->json(['transactionable' => $item->transactionable, 'message' => 'remove approved items']);
        }
        $item->update(['status' => ItemStatusEnum::paid()->value]);

        $item->transactionable->update(['current_balance' => $item->transactionable->current_balance + $item->value]);

        return response()->json(['message' => 'approved status updated successfully', 'transactionable' => $item->transactionable]);
    }

    public function filter_items(FilterItemRequest $request)
    {
        $date_from = $request->date_from;
        $date_to = $request->date_to;


        $items = Item::query()
            ->when(filled($request->date_from) && filled($request->date_to), function ($q) use ($date_from, $date_to, $request) {
                return $q->whereBetween('created_at', [$date_from, $date_to]);
            })->when(filled($request->project_id), function ($q) use ($request) {
                return $q->where('project_id', $request->project_id);
            })->when(filled($request->company_id), function ($q) use ($request) {
                return $q->where('company_id', $request->company_id);
            })->when(filled($request->priority), function ($q) use ($request) {
                return $q->where('priority_level', $request->priority);
            })
            ->where('organization_id', auth()->user()->organization->id)
            ->with(['logs', 'transactionable', 'company', 'project'])
            ->orderBy('date')
            ->get();

        return response()->json(['message' => 'items retrieved successfully', 'items' => $items]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $currentDate = date('Y-m-d');
        $current_balance = Bank::query()->sum('current_balance');

        $items = Item::with(['logs', 'transactionable', 'company', 'project'])
            ->where('organization_id', auth()->user()->organization->id)
            ->orderBy('date')
            ->orderByRaw("CASE WHEN status = 'paid' THEN 0 ELSE 1 END")
            ->paginate(20);


        $items->each(function ($item) use (&$current_balance) {
            $current_balance += $item->value;
            $item['total'] = $current_balance;

        });
        $itemsPerPage = 20;
        $items_new = Item::with(['logs', 'transactionable', 'company', 'project'])
            ->orderBy('date')
            ->get()->toArray();

        $indexOfCurrentDateItem = [];
        $page = null;
        if (!empty($items_new)) {
            $closestDateDiff = PHP_INT_MAX; // Initialize with a large value
            foreach ($items_new as $key => $value) {
                $dateDiff = abs(strtotime($value['date']) - strtotime($currentDate));

                if ($value['date'] == $currentDate) {
                    $page = ceil(($key + 1) / $itemsPerPage);
                    break;

                } elseif ($dateDiff < $closestDateDiff) {
                    $closestDateDiff = $dateDiff;
                    $page = ceil(($key + 1) / $itemsPerPage);
                }
            }
        }


        return self::ResponseSuccess(data: ['items' => $items, 'current_day_page' => $page]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ItemRequest $request)
    {
        if (request('transactionable') == 'cash') {
            $cash = Cash::find($request->transactionable_id);
            $item = $cash->items()->create($request->all() + ['user_id' => Auth::id(), 'organization_id' => auth()->user()->organization->id]);
        }
        $bank = Bank::find($request->transactionable_id);
        $item = $bank->items()->create($request->all() + ['user_id' => Auth::id(), 'organization_id' => auth()->user()->organization->id]);


        return response()->json(['message' => 'Item added successfully', 'item' => $item], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $item = Item::find($id);
        $user = auth()->user();
        return response()->json(['item' => $item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ItemUpdateRequest $request, $id): JsonResponse
    {
        $item = Item::findOrFail($id);
        if (filled($request->value) && $item->status == ItemStatusEnum::paid()->value) {
            $item->transactionable->update(['current_balance' => $item->transactionable->current_balance - ($item->value)]);
//            todo: search for code  explanation
            $item->transactionable->update(['current_balance' => $item->transactionable->current_balance + ($request->value)]);

        }
        $item->update($request->all() + ['user_id' => Auth::id()]);


        return response()->json(['message' => 'Item updated successfully', 'item' => $item], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        Item::destroy($id);
        return response()->json(['message' => 'Item deleted successfully'], 200);
    }

    public function search_items(SearchItemRequest $request): JsonResponse
    {

        $items = Item::query()
            ->when(filled($request->name) && is_numeric($request->name), function ($q) use ($request) {
                return $q->where('id', (int)$request->name);

            })->when(filled($request->name) && !is_numeric($request->name), function ($q) use ($request) {
                return $q->where('name', 'like', '%' . $request->name . '%')
                    ->orWhere('description', 'like', '%' . $request->name . '%')
                    ->orWherehas('project', function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->name . '%');
                    });
            })
            ->where('organization_id', auth()->user()->organization->id)
            ->orderByRaw("CASE WHEN status = 'paid' THEN 0 ELSE 1 END")
            ->with(['logs', 'transactionable', 'company', 'project'])
            ->get();

        return response()->json(['message' => 'items retrieved successfully', 'items' => $items]);
    }

    /**
     * @param StatisticsRequest $request
     * @return JsonResponse
     */
    public function statistics(StatisticsRequest $request): JsonResponse
    {

        $projects = Project::query()->whereHas('company', function ($q) {
            $q->where('organization_id', auth()->user()->organization->id);
        })->paginate(6);

        $total_expense = Item::query()
            ->where('organization_id', auth()->user()->organization->id)
            ->where('value', '<', 0)
            ->whereMonth('created_at', $request->month)
            ->where('status', 'paid')
            ->sum('value');

        $total_income = Item::query()
            ->where('organization_id', auth()->user()->organization->id)
            ->where('value', '>', 0)
            ->whereMonth('created_at', $request->month)
            ->where('status', 'paid')
            ->sum('value');

        $total_bank_balance = Bank::query()
            ->where('organization_id', auth()->user()->organization->id)
            ->sum('current_balance');

        $total_cash_this_month = Cash::query()
            ->where('organization_id', auth()->user()->organization->id)
            ->sum('current_balance');


        return response()->json(['message' => 'items retrieved successfully',
            'projects' => $projects,
            'total_expense' => $total_expense,
            'total_income' => $total_income,
            'total_bank_balance' => $total_bank_balance,
            'total_cash_this_month' => $total_cash_this_month
        ]);
    }

    public function today_tasks(TodayTaskRequest $request)
    {

        $items = Item::query()
            ->where('organization_id', auth()->user()->organization->id)
            ->where('date', $request->date)
            ->orderByRaw("CASE WHEN status = 'paid' THEN 0 ELSE 1 END")
            ->with(['logs', 'transactionable', 'company', 'project'])
            ->paginate(20);

        return response()->json(['message' => 'items retrieved successfully', 'items' => $items], 200);

    }

    /**
     * Return data
     * @return JsonResponse
     */
    public function data(): JsonResponse
    {
        $projects = Project::query()->whereHas('company', function ($q) {
            $q->where('organization_id', auth()->user()->organization->id);
        })->with('company')->get();
        $companies = Company::query()
            ->where('organization_id', auth()->user()->organization->id)
            ->with(['bank', 'projects'])->get();
        $banks = Bank::query()->where('organization_id', auth()->user()->organization->id)->get();

        return response()->json(['message' => 'items retrieved successfully', 'banks' => $banks, 'companies' => $companies, 'projects' => $projects], 200);
    }

    /**
     * Show the application dashboard.
     *
     */
    public function sendMail(SendEmailRequest $request)
    {
        $email = $request->email;

        $data = Item::whereIn('id', $request->ids)->get();
        $maildata = $data->map(function ($item) {
            return [
                'name' => $item->name,
                'value' => $item->value,
                'project_name' => $item->project?->name,
                'bank_name' => $item->transactionable?->name,
                'company_name' => $item->company?->name,
            ];
        })->toArray();


        Mail::to($email)->send(new SendItemsEmail($maildata));

        return response()->json(['message' => 'email send successfully'], 200);
    }

    /**
     * @return JsonResponse
     */
    public function get_logs(): JsonResponse
    {
        $logs = Log::query()
            ->whereHas('item', function ($q) {
                $q->where('organization_id', auth()->user()->organization->id);
            })
            ->paginate(6);

        return response()->json(['message' => 'logs retrieved successfully', 'logs' => $logs], 200);

    }

    /**
     * @return JsonResponse
     */
    public function logs_filter(FilterLogRequest $request): JsonResponse
    {
        $date_from = $request->date_from;
        $date_to = $request->date_to;


        $logs = Log::query()
            ->when(filled($date_from) && filled($date_to), function ($q) use ($date_from, $date_to) {
                return $q->whereBetween('date', [$date_from, $date_to]);
            })
            ->when(filled($request->item_type), function ($q) use ($request) {
                return $q->where('type', $request->item_type);

            })->when(filled($request->changed_by) && ($request->changed_by == 'admin'), function ($q) use ($request) {
                return $q->whereNotNull('user_id');

            })->when(filled($request->changed_by) && ($request->changed_by == 'ai'), function ($q) use ($request) {
                return $q->whereNull('user_id');

            })
            ->when(filled($request->name) && is_numeric($request->name), function ($q) use ($request) {
                return $q->where('id', (int)$request->name);

            })->when(filled($request->name) && !is_numeric($request->name), function ($q) use ($request) {
                return $q->where('name', 'like', '%' . $request->name . '%');


            })
            ->whereHas('item', function ($q) {
                $q->where('organization_id', auth()->user()->organization->id);
            })
            ->get();

        return response()->json(['message' => 'logs retrieved successfully', 'logs' => $logs], 200);

    }

    public function get_log_by_id(Item $id)
    {
        $logs = $id->logs;
        return response()->json(['message' => 'logs retrieved successfully', 'logs' => $logs], 200);

    }
}
