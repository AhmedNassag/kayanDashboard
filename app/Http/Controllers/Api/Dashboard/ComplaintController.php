<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $complaints = Complaint::with(['responser'])
        ->where(function ($q) use($request){
            $q->when($request->search, function ($q) use ($request) {
                return $q->where('content', 'like', '%' . $request->search . '%')
                    ->orWhere('kind', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search . '%')
                    ->orWhere('reply', 'like', '%' . $request->search . '%');
            });
        })->where(function ($q) use ($request) {
            $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereDate("created_at",'>=', $request->from_date)
                ->whereDate("created_at",'<=', $request->to_date);
            });
        })
        ->where('platform','like',"%$request->platform%")
            ->latest()
            ->paginate(10);

        return $this->sendResponse(['complaints' => $complaints], 'Data exited successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'phone'    => 'nullable|numeric',
                'name'    => 'nullable',
                'kind'    => 'nullable',
                'type'    => 'nullable|exists:type_of_complaints,id',
                'platform'    => 'nullable',
                'content' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only('kind', 'type', 'content','phone','name','platform');

            if (!$request->type || $request->type = '') {
                $data['kind'] = 'Suggestion';
            } else {
                $data['kind'] = 'Complaint';
            }
            if(!$request->platform)
                $data['platform'] = 'mobile app';

            $complaint = Complaint::create($data);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $complaint = Complaint::find($id);
            $responser = User::find($complaint->responser_id);
            return $this->sendResponse(['complaint' => $complaint, 'responser' => $responser], 'Data exited successfully');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $complaint = Complaint::find($id);
            return $this->sendResponse(['complaint' => $complaint], 'Data exited successfully');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $complaint = Complaint::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name'    => 'nullable',
                'phone'    => 'nullable|numeric',
                'kind'    => 'nullable',
                'type'    => 'nullable|exists:type_of_complaints,id',
                'content' => 'required',
                'platform'    => 'nullable',

            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only('kind', 'type', 'content','phone','name','platform');

            if ($request->type) {
                $data['kind'] = 'Complaint';
            } else {
                $data['kind'] = 'Suggestion';
            }
            if(!$request->platform)
                $data['platform'] = 'mobile app';

            $complaint->update($data);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $complaint = Complaint::find($id);
            if ($complaint) {
                $complaint->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }


    public function reply(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $complaint = Complaint::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'reply' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only('reply', 'responser_id');
            $data['responser_id'] = Auth::user()->id;

            $complaint->update($data);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }
}
