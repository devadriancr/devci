<?php

namespace App\Jobs;

use App\Models\HPO;
use App\Models\Input;
use App\Models\InputSupplier;
use App\Models\RYT1;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
            ->orderByRaw('R1DAT DESC, R1ORN DESC, R1SQN ASC')
            ->distinct('R1ORN')
            ->get();

        foreach ($orders as $key => $order) {
            $input = InputSupplier::where([['order_no', $order->R1ORN],['sequence', $order->R1SQN]])->first();

            if ($po === null) {
                $ord = floatval(substr($order->R1ORN, 0, 8));
                $line = floatval(substr($order->R1ORN, -4, 4));

                $hpo = HPO::where(
                    [
                        ['PORD', $ord],
                        ['PLINE', $line],
                        ['PQREC', '>', 0]
                    ]
                )->first();

                if ($hpo !== null) {
                    StoreSupplierOrderJob::dispatch($hpo, $order->R1ORN);
                }
            }
        }
    }
}
