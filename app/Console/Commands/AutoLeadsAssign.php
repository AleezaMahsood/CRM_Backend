<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\leads;
use App\Models\User;

class AutoLeadsAssign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-leads-assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically assign leads to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $leads = leads::where('status', 'new')->get();

        foreach ($leads as $lead) {
            // Check if lead status is still "new"
            if ($lead->status === 'new') {
                // Check if lead was last updated more than 8 hours ago
                if ($lead->updated_at->addHours(8)->isPast()) {
                    // Get list of available users to reassign lead with least number of leads
                    $availableUsers = User::where('id', '!=', $lead->user_id)
                        ->withCount('leads')
                        ->orderBy('leads_count')
                        ->get();

                    if ($availableUsers->isNotEmpty()) {
                        // Assign lead to the user with the least number of leads
                        $newAssignedUser = $availableUsers->first();
                        $lead->user_id = $newAssignedUser->id;
                        $lead->save();
                    }
                }
            }
        }

        $this->info('Leads auto-assigned successfully.');
    }
    
}
