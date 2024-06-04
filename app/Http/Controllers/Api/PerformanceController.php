<?php

namespace App\Http\Controllers\Api;
use App\Models\leads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    // Laravel Controller to fetch user-specific data
public function countLeadsByStatus($userId)
{
    $leadCounts = leads::select(
        DB::raw('SUM(CASE WHEN status = "New" THEN 1 ELSE 0 END) AS New'),
        DB::raw('SUM(CASE WHEN status = "Converted" THEN 1 ELSE 0 END) AS Converted'),
        DB::raw('SUM(CASE WHEN status = "Follow_Ups" THEN 1 ELSE 0 END) AS Follow_Ups'),
        DB::raw('COUNT(*) AS Total_Leads')
    )
    ->where('user_id', $userId)
    ->groupBy('user_id')
    ->first();

// If no leads found for the user, set counts to 0
if (!$leadCounts) {
    $leadCounts = [
        'New' => 0,
        'Converted' => 0,
        'Follow_Ups' => 0,
        'Total_Leads' => 0,
    ];
}else{
    $leadCounts = $leadCounts->toArray();
}

return response()->json($leadCounts);
}
//Function to get leads for a specific user to evaluate his performance
public function fetchLeadsByAllStatuses($userId)
{
    $statuses = leads::STATUS;

    $labels = [];
    $totalLeads=[];
    foreach ($statuses as $status) {
        $labels[]=$status;
        $totalLeads[] = leads::where('user_id', $userId)->where('status', $status)->count();
    }

     return [
            'labels' => $labels,
            'totalLeads' => $totalLeads,
        ];
}
// Function to present performance of all users to admins
public function evaluateUserPerformance()
{
    $statuses = leads::STATUS;
    $users = User::with('leads')->get();

    $result = [];

    foreach ($users as $user) {
        // Initialize leadsByStatus array with all statuses set to 0
        $leadsByStatus = array_fill_keys($statuses, 0);

        // Count the number of leads for each status for the current user
        foreach ($user->leads as $lead) {
            if (array_key_exists($lead->status, $leadsByStatus)) {
                $leadsByStatus[$lead->status]++;
            }
        }

        $totalLeads = array_sum($leadsByStatus);
        $convertedLeads = $leadsByStatus['Converted'];
        $conversionRate = $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0;
        $conversionRateFormatted = number_format($conversionRate, 2) . '%';

        $userData = [
            'user_id' => $user->id,
            'firstName' => $user->firstName,
             'lastName'=>$user->lastName,
            'email' => $user->email,
            'Overall' => $totalLeads,
            'conversion_rate' => $conversionRateFormatted,
            'logged_in'=>$user->last_login_time
        ];

        foreach ($leadsByStatus as $status => $count) {
            $userData[$status] = $count;
        }

        $result[] = $userData;
    }

    return $result;
}
public function UserLeads($userId)
{
        $leads = leads::where('user_id', $userId)->get();
        return response()->json($leads, 200);
}

}
