<?php

namespace App\Http\Controllers\Api;
use App\Models\leads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Projects;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPerformanceController extends Controller
{
    
    public function leadsGraph(Request $request)
{
    // Define the status array
    $statuses = [
        'New', 
        'Follow Ups', 
        'Converted', 
        'Rejected',
        'Not Interested', 
        'Invalid'
    ];

    // Fetch all leads
    $leads = leads::all();

    // Determine the date range
    $startDate = Carbon::parse($leads->min('date'))->startOfMonth();
    $endDate = Carbon::parse($leads->max('date'))->endOfMonth();

    // Initialize an array with all months in the range
    $allMonths = [];
    $currentDate = $startDate->copy();
    while ($currentDate->lte($endDate)) {
        $allMonths[$currentDate->format('Y-m')] = array_fill_keys($statuses, 0);
        $currentDate->addMonth();
    }

    // Group data by month
    $groupedData = $leads->groupBy(function($lead) {
        return Carbon::parse($lead->date)->format('Y-m'); // Group by month
    })->map(function ($monthGroup) use ($statuses) {
        // Initialize the status counts
        $statusCounts = array_fill_keys($statuses, 0);

        // Count each status
        foreach ($monthGroup as $lead) {
            if (in_array($lead->status, $statuses)) {
                    $statusCounts[$lead->status]++;
                
            }
        }

        // Calculate the total number of leads for this month
        $total = array_sum($statusCounts);

        // Calculate percentages
        return array_map(function ($count) use ($total) {
            return $total ? number_format(($count / $total) * 100, 2) : 0;
        }, $statusCounts);
    });

    // Merge grouped data with all months and filter out empty months
    foreach ($allMonths as $month => $counts) {
        if (isset($groupedData[$month])) {
            $allMonths[$month] = $groupedData[$month];
        }
    }

    // Filter out months where all statuses are zero
    $filteredData = array_filter($allMonths, function ($counts) {
        return array_sum($counts) > 0;
    });

    // Return the result as JSON
    return response()->json($filteredData);
}
public function getProjectStats()
{
    $projectStats = projects::select('project_type', \DB::raw('count(*) as count'))
        ->groupBy('project_type')
        ->get();

    return response()->json($projectStats);
}

public function getLeadStatistics()
{
    $statuses = leads::STATUS;
    $statusCounts = leads::select(
        'status',
        DB::raw('COUNT(*) as count')
    )
    ->groupBy('status')
    ->pluck('count', 'status');

    // Get the total number of leads
    $totalLeads = leads::count();

    // Calculate the total budget
    $totalBudget = leads::sum('revenue');

    // Prepare the response data
    $data = [
        'total_leads' => $totalLeads,
        'total_budget' => $totalBudget,
    ];

    foreach ($statuses as $status) {
        $data[$status] = $statusCounts->get($status, 0);
    }

    return response()->json($data);
}     
   


}

