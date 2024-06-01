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
        // Fetch all leads with 'new' status
        $leads = leads::where('status', 'New')->get();

        foreach ($leads as $lead) {
            // Check if the lead status is still "new"
            if ($lead->status === 'New') {
                // Check if the lead was last updated more than 8 hours ago
                if ($lead->updated_at->addHours(8)->isPast()) {
                    // Check if the lead was not created by the current assigned user
                    if ($lead->user_id !== $lead->created_by) {
                        // Get a list of available users to reassign the lead with the least number of leads
                        $availableUsers = User::where('id', '!=', $lead->user_id)
                            ->where('role', 'user') // Add this line to filter by role
                            ->withCount('leads')
                            ->orderBy('leads_count')
                            ->get();

                        if ($availableUsers->isNotEmpty()) {
                            // Assign the lead to the user with the least number of leads
                            $newAssignedUser = $availableUsers->first();
                            $lead->user_id = $newAssignedUser->id;
                            $lead->save();
                        }
                    }
                }
            }
        }

        $this->info('Leads auto-assigned successfully.');
    }
    
}
