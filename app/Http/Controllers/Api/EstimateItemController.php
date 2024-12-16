<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\UploadFiles;
// Models
use App\Models\EstimateItem;

// Constants
use App\Constants\Message;
use App\Constants\ResponseCode;

class EstimateItemController extends Controller
{
    public function list()
	{
		$result = EstimateItem::all();

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);
	}

    public function show($id)
	{
		$result = EstimateItem::findOrFail($id);

		return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $result);
	}

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'estimate_id' => 'required|exists:estimates,id',
            'type'        => 'required|in:text,audio',
            'content'     => 'required',
        ]);
        if($request->type="audio")
        {
            if ($request->hasFile('content')) {
                $validatedData['content'] = UploadFiles::uploadFile($request->content);
            }
        }

        $estimateItem = EstimateItem::create($validatedData);

        return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimateItem);
    }


    public function update(Request $request, $id)
    {
        $estimateItem = EstimateItem::findOrFail($id);

        $validatedData = $request->validate([
            'type'        => 'required|in:text,audio',
            'content'     => 'required',
        ]);

        if($request->type="audio")
        {
            if ($request->hasFile('content')) {
                $validatedData['content'] = UploadFiles::uploadFile($request->content);
            }
        }

        $estimateItem->update($validatedData);

        return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, $estimateItem);
    }

    public function destroy($id)
    {
        $estimateItem = EstimateItem::findOrFail($id);

        $estimateItem->delete();

        return makeResponse(ResponseCode::SUCCESS, Message::REQUEST_SUCCESSFUL, null);
    }
}
