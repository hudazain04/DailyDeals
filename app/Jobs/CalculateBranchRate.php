<?php

namespace App\Jobs;

use App\Models\Branch;
use App\Models\Rate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use function Symfony\Component\String\b;

class CalculateBranchRate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $branch_id;
    public function __construct($branch_id)
    {
        $this->branch_id=$branch_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $avgRate=Rate::where('branch_id',$this->branch_id)->avg('rate');
        $branch=Branch::find($this->branch_id);
        $branch->rate=$avgRate;
        $branch->save();

    }
}
