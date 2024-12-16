<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EstimateItem;
use Illuminate\Http\Request;

// Models
use App\Models\Estimate;

// Constants
use App\Constants\Message;
use App\Constants\ResponseCode;

// Carbon
use Carbon\Carbon;

class DashboardController extends Controller
{
	public function index()
	{

		// Get the time range parameter, default to "all"
		$timeRange = request()->query('time_range', 'all');

		// Determine the date range based on the time range parameter
		$dateRange = null;
		if ($timeRange === 'week') {
			$dateRange = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
		} elseif ($timeRange === 'month') {
			$dateRange = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
		}

		// Apply date range conditionally
		$estimatesQuery = Estimate::query();
		if ($dateRange) {
			$estimatesQuery->whereBetween('created_at', $dateRange);
		}

		// Build the result based on the filtered query
		$result = [
			'total' => $estimatesQuery->count(),
			'in_progress' => $estimatesQuery->where('status', 'In Progress')->count(),
			'urgent' => $estimatesQuery->where('status', 'Urgent')->count(),
			'completed' => $estimatesQuery->where('status', 'Completed')->count(),
			'archived' => $estimatesQuery->where('status', 'Archived')->count(),
			'estimates' => Estimate::where('status', '!=', 'Completed')->limit(20)->get(),
			'urgencies' => Estimate::where('status', 'Urgent')->count(),
			'recordings' => EstimateItem::where('type', 'audio')->limit(3)->get()
		];

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);

	}
}
