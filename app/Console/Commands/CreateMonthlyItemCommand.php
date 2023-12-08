<?php

namespace App\Console\Commands;

use App\Enums\DateTypeEnum;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateMonthlyItemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:item';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Items Where have every month status';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Item::query()->where('type', 'monthly')->where('checked', false)->get();
        foreach ($items as $item) {

            $now = Carbon::now();
            $oneMonthAfterCreatedAt =Carbon::parse($item->date)->addMonth();
            if ($now >= $oneMonthAfterCreatedAt) {
                $item->checked = true;
                $item->save();
                Item::query()->create([
                    'name' => $item->name,
                    'value' => $item->value,
                    'type' => $item->type,
                    'date' => Carbon::parse($item->date)->addMonth(),
                    'priority_level' => $item->priority_level,
                    'max_period' => $item->max_period,
                    'description' => $item->description,
                    'comment' => $item->comment,
                    'user_id' => $item->user_id,
                    'bank_id' => $item->bank_id,
                    'company_id' => $item->company_id,
                    'project_id' => $item->project_id,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'status' => $item->status,
//
                ]);
            }
        }
    }

}
