<?php

namespace App\Jobs;

use App\Models\HPO;
use App\Models\InputSupplier;
use App\Models\RYT1;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
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
        $orders = RYT1::select('R1ORN', 'R1SQN', 'R1SNP', 'R1DAT', 'R1TIM', 'R1PRO', 'R1USR', 'R1FLG')
            ->where(
                [
                    ['R1DAT', 'LIKE', '%2023'],
                    ['R1FLG', '=', ' ']
                ]
            )
            ->orderByRaw('R1DAT DESC, R1TIM DESC, R1ORN DESC, R1SQN ASC')
            ->get();

        foreach ($orders as $key => $order) {
            $date = Carbon::parse(str_replace('/', '-', $order->R1DAT))->format('Y-m-d');
            $time = Carbon::parse(trim($order->R1TIM))->format('H:i:s.v');
            // Log::info($key . '.    No. Order: ' . $order->R1ORN . '    Seqence: ' . $order->R1SQN . '    Date: ' . $order->R1DAT . '    Flag: ' . $order->R1FLG);
            $input = InputSupplier::where(
                [
                    ['order_no', $order->R1ORN],
                    ['sequence', $order->R1SQN],
                    ['received_date', $date],
                    ['received_time', $time]
                ]
            )->first();

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
                    DB::connection('odbc-lx834fu01')
                        ->table('LX834FU01.RYT1')
                        ->whereRaw("R1ORN = '" . strval($order->R1ORN) . "' AND R1SQN = '" . strval($order->R1SQN) . "' AND R1SNP = '" . strval($order->R1SNP) . "' AND R1PRO = '" . strval($order->R1PRO) . "'")
                        ->update(['R1FLG' => "1"]);

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
