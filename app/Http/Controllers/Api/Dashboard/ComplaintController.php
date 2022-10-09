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
        $complaints = Complaint::with(['user','responser'])->when($request->search, function ($q) use ($request) {
            return $q->where('content', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

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
                'kind'    => 'nullable',
                'type'    => 'nullable',
                'content' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only('kind','type','content','user_id');

            if ($request->type) {
                $data['kind'] = 'Complaint';
            } else {
                $data['kind'] = 'Suggestion';
            }
            $data['user_id'] = Auth::user()->id;
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
            $sender    = User::find($complaint->user_id);
            $responser = User::find($complaint->responser_id);
            return $this->sendResponse(['complaint' => $complaint, 'sender' => $sender, 'responser' => $responser], 'Data exited successfully');
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
                'kind'    => 'nullable',
                'type'    => 'nullable',
                'content' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only('kind', 'type', 'content', 'user_id');

            if ($request->type) {
                $data['kind'] = 'Complaint';
            } else {
                $data['kind'] = 'Suggestion';
            }
            $data['user_id'] = Auth::user()->id;

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


    public function reply(Request $request,$id)
    {
        DB::beginTransaction();
        // try {
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
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return $this->sendError('An error occurred in the system');
        // }
    }
}
