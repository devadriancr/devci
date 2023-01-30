<?php

namespace App\Jobs;

use App\Models\HPO;
use App\Models\InputSupplier;
use App\Models\RYT1;
use Illuminate\Bus\Queueable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SupplierOrderMigrationJob implements ShouldQueue
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
        $orders = RYT1::select('R1ORN', 'R1SQN', 'R1SNP', 'R1DAT', 'R1TIM', 'R1PRO', 'R1USR')
            ->where('R1DAT', 'LIKE', '%2023')
            // ->orWhere('R1DAT', 'LIKE', '%2022')
            ->orderByRaw('R1DAT DESC, R1TIM DESC, R1ORN DESC, R1SQN ASC')
            ->get();

        foreach ($orders as $key => $order) {
            $input = InputSupplier::where([['order_no', $order->R1ORN], ['sequence', $order->R1SQN]])->first();

            if ($input === null) {
                $ord = intval(substr($order->R1ORN, 0, 8));
                $line = intval(substr($order->R1ORN, -4, 4));

                $hpo = HPO::select('PVEND')
                    ->where(
                        [
                            ['PORD', $ord],
                            ['PLINE', $line],
                        ]
                    )->first();

                if ($hpo !== null) {
                    // Log::info($hpo->PVEND . ' '.$order->R1ORN . ' '.$order->R1SQN . ' '.$order->R1PRO . ' '.$order->R1SNP . ' '.$order->R1DAT . ' '.$order->R1TIM);
                    StoreSupplierOrderJob::dispatch(
                        $hpo->PVEND,
                        $order->R1ORN,
                        $order->R1SQN,
                        $order->R1PRO,
                        $order->R1SNP,
                        $order->R1DAT,
                        $order->R1TIM
                    );
                }
            }
        }
    }
}
