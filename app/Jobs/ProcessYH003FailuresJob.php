<?php

namespace App\Jobs;

use App\Models\YH003;
use App\Models\YH003Failure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessYH003FailuresJob implements ShouldQueue
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
        $failures = YH003Failure::where('status', true)->get();

        foreach ($failures as $failure) {
            try {
                YH003::insert([
                    'H3CONO' => $failure->H3CONO ?? '',
                    'H3DDTE' => $failure->H3DDTE ?? '',
                    'H3DTIM' => $failure->H3DTIM ?? '',
                    'H3PROD' => $failure->H3PROD ?? '',
                    'H3SUCD' => $failure->H3SUCD ?? '',
                    'H3SENO' => $failure->H3SENO ?? '',
                    'H3RQTY' => $failure->H3RQTY ?? '',
                    'H3CUSR' => $failure->H3CUSR ?? '',
                    'H3RDTE' => $failure->H3RDTE ?? '',
                    'H3RTIM' => $failure->H3RTIM ?? '',
                ]);

                $failure->status = false;
                $failure->save();

            } catch (\Exception $e) {
                Log::error('Error al procesar el registro fallido', ['failure' => $failure, 'error' => $e->getMessage()]);
            }
        }
    }
}
