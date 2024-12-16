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

class EstimateController extends Controller
{
	public function list(Request $request)
	{

		$query = Estimate::query();

		if ($request->status == 'Urgent') {
			$query->where('is_urgent', 1);
		} else if ($request->status == 'Archived') {
			$query->where('is_archived', 1);
		} else if ($request->status == 'Completed') {
			$query->where('status', operator: 'Completed');
		} else if ($request->status == 'In Progress') {
			$query->where('status', operator: 'In Progress');
		} else if ($request->status == 'Draft') {
			$query->where('status', operator: 'Draft');
		}

		$allowedSortByFields = ['name', 'cost', 'status', 'created_at', 'updated_at'];
		if (in_array($request->sortBy, $allowedSortByFields)) {
			$query->orderBy($request->sortBy, $request->orderBy);
		}

		$allowedOrderByDirections = ['asc', 'desc'];
		if (!in_array($request->orderBy, $allowedOrderByDirections)) {
			$request->orderBy = 'asc';
		}

		$estimates = $query->get();

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimates);
	}

	public function search($query)
	{

		$estimates = Estimate::where('name', 'Like', '%' . $query . '%')->get();
		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimates);
	}

	public function show($id)
	{
		$result = Estimate::findOrFail($id);
		$result->last_reviewed = now();
		$result->save();

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);
	}

	public function recordings($id)
	{
		$result = EstimateItem::where('estimate_id', $id)->where('type', 'audio')->get();

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);
	}
	public function store(Request $request)
	{
		$validatedData = $request->validate([
			'name' => 'required|string',
			'address' => 'required|string',
			'phone' => 'required|string',
			'email' => 'required|email',
			'type' => 'required',
			'cost' => 'required|numeric|min:0',
			'status' => 'required',
			'last_reviewed' => 'nullable|date'
		]);

		$validatedData['items'] = $request->items;

		$estimate = Estimate::create($validatedData);

		storeNotification('Created', $estimate);


		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimate);
	}


	public function update(Request $request, $id)
	{
		$estimate = Estimate::findOrFail($id);

		$validatedData = [
			'name' => $request->name,
			'address' => $request->address,
			'phone' => $request->phone,
			'email' => $request->email,
			'type' => $request->type,
			'cost' => $request->cost,
		];

		$validatedData['items'] = $request->items;


		$estimate->update($validatedData);

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimate);
	}

	public function markArchived($id)
	{
		$estimate = Estimate::findOrFail($id);
		$estimate->is_archived = 1;
		$estimate->save();

		storeNotification('Archived', $estimate);


		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimate);
	}

	public function markUnarchived($id)
	{
		$estimate = Estimate::findOrFail($id);
		$estimate->is_archived = 0;
		$estimate->save();

		storeNotification('Unarchived', $estimate);


		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimate);
	}

	public function markUrgent($id)
	{
		$estimate = Estimate::findOrFail($id);
		$estimate->is_urgent = 1;
		$estimate->save();

		storeNotification('Urgent', $estimate);


		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimate);
	}


	public function markComplete($id)
	{
		$estimate = Estimate::findOrFail($id);
		$estimate->status = 'Completed';
		$estimate->save();

		storeNotification('Completed', $estimate);


		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimate);
	}

	public function markInComplete($id)
	{

		$estimate = Estimate::findOrFail($id);
		$estimate->status = 'In Progress';
		$estimate->save();

		storeNotification('In Complete', $estimate);

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimate);
	}

	public function markNotUrgent($id)
	{
		$estimate = Estimate::findOrFail($id);
		$estimate->is_urgent = 0;
		$estimate->save();

		storeNotification('Not Urgent', $estimate);

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimate);
	}

	public function destroy($id)
	{
		$estimate = Estimate::findOrFail($id);

		storeNotification('Deleted', $estimate);

		$estimate->delete();

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, null);
	}

}
