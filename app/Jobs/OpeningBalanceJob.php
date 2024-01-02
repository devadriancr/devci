<?php

namespace App\Jobs;

use App\Models\ILI;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OpeningBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ili = ILI::select(
            [
                'LID', 'LPROD', 'LWHS', 'LLOC', 'LOPB'
            ]
        )
            ->where('LID', 'LI')
            ->orderBy('LOPB', 'DESC')
            ->get();

        foreach ($ili as $key => $iliValue) {
            StoreOpeningBalanceJob::dispatch(
                $iliValue->LPROD, // Item
                $iliValue->LLOC, // Locación
                $iliValue->LOPB // Opening Balance
            );
        }
    }
}
