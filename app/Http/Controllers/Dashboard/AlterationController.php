<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OtherUser;
use App\Models\Alteration;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AlterationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.coming-soon');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create alteration');
        try {
            $users = OtherUser::where('is_active', 'active')->get();
            $vehicles = Vehicle::where('is_active', 'active')->get();
            return view('dashboard.alterations.create', compact('users', 'vehicles'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Alteration Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create alteration');
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'alteration_date' => 'nullable|date',
            'alteration_details' => 'nullable|string',
            'notes' => 'nullable|string',
            // FROM USER
            'from_user_id' => 'required_without:from_name|nullable|exists:users,id',
            'from_name' => 'required_without:from_user_id|nullable|string|max:255',
            'from_father_name' => 'required_with:from_name|nullable|string|max:255',
            'from_email' => 'nullable|email',
            'from_phone' => 'nullable|string|max:20',
            'from_cnic' => 'nullable|string|max:20',
            'from_company' => 'nullable|string|max:255',
            'from_country' => 'nullable|string|max:255',
            'from_address' => 'nullable|string',

            // TO USER
            'to_user_id' => 'required_without:to_name|nullable|exists:users,id',
            'to_name' => 'required_without:to_user_id|nullable|string|max:255',
            'to_father_name' => 'required_with:to_name|nullable|string|max:255',
            'to_email' => 'nullable|email',
            'to_phone' => 'nullable|string|max:20',
            'to_cnic' => 'nullable|string|max:20',
            'to_company' => 'nullable|string|max:255',
            'to_country' => 'nullable|string|max:255',
            'to_address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            if (!empty($request->from_name)) {
                $fromUser = new OtherUser();
                $fromUser->name = $request->from_name;
                $fromUser->father_name = $request->from_father_name;
                $fromUser->email = $request->from_email;
                $fromUser->phone = $request->from_phone;
                $fromUser->cnic = $request->from_cnic;
                $fromUser->company = $request->from_company;
                $fromUser->country = $request->from_country;
                $fromUser->address = $request->from_address;
                $fromUser->save();
                $from_user_id = $fromUser->id;
            } else {
                $from_user_id = $request->from_user_id;
            }
            if (!empty($request->to_name)) {
                $toUser = new OtherUser();
                $toUser->name = $request->to_name;
                $toUser->father_name = $request->to_father_name;
                $toUser->email = $request->to_email;
                $toUser->phone = $request->to_phone;
                $toUser->cnic = $request->to_cnic;
                $toUser->company = $request->to_company;
                $toUser->country = $request->to_country;
                $toUser->address = $request->to_address;
                $toUser->save();
                $to_user_id = $toUser->id;
            } else {
                $to_user_id = $request->to_user_id;
            }
            $alteration = new Alteration();
            $alteration->vehicle_id = $request->vehicle_id;
            $alteration->from_user_id = $from_user_id;
            $alteration->to_user_id = $to_user_id;
            $alteration->type = $request->type;
            $alteration->alteration_date = $request->alteration_date ?? now();
            $alteration->alteration_details = $request->alteration_details;
            $alteration->notes = $request->notes;
            $alteration->save();

            DB::commit();
            return redirect()->route('dashboard.alterations.index')->with('success', 'Alteration Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Alteration Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view alteration');
        try {
            $alteration = Alteration::with('fromUser', 'toUser', 'vehicle')->findOrFail($id);
            return view('dashboard.alterations.show', compact('alteration'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Alteration Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update alteration');
        try {
            $alteration = Alteration::findOrFail($id);
            $users = OtherUser::where('is_active', 'active')->get();
            $vehicles = Vehicle::where('is_active', 'active')->get();
            return view('dashboard.alterations.edit', compact('alteration', 'users', 'vehicles'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Alteration Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update alteration');
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'alteration_date' => 'nullable|date',
            'alteration_details' => 'nullable|string',
            'notes' => 'nullable|string',
            // FROM USER
            'from_user_id' => 'required_without:from_name|nullable|exists:users,id',
            'from_name' => 'required_without:from_user_id|nullable|string|max:255',
            'from_father_name' => 'required_with:from_name|nullable|string|max:255',
            'from_email' => 'nullable|email',
            'from_phone' => 'nullable|string|max:20',
            'from_cnic' => 'nullable|string|max:20',
            'from_company' => 'nullable|string|max:255',
            'from_country' => 'nullable|string|max:255',
            'from_address' => 'nullable|string',

            // TO USER
            'to_user_id' => 'required_without:to_name|nullable|exists:users,id',
            'to_name' => 'required_without:to_user_id|nullable|string|max:255',
            'to_father_name' => 'required_with:to_name|nullable|string|max:255',
            'to_email' => 'nullable|email',
            'to_phone' => 'nullable|string|max:20',
            'to_cnic' => 'nullable|string|max:20',
            'to_company' => 'nullable|string|max:255',
            'to_country' => 'nullable|string|max:255',
            'to_address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            if (!empty($request->from_name)) {
                $fromUser = new OtherUser();
                $fromUser->name = $request->from_name;
                $fromUser->father_name = $request->from_father_name;
                $fromUser->email = $request->from_email;
                $fromUser->phone = $request->from_phone;
                $fromUser->cnic = $request->from_cnic;
                $fromUser->company = $request->from_company;
                $fromUser->country = $request->from_country;
                $fromUser->address = $request->from_address;
                $fromUser->save();
                $from_user_id = $fromUser->id;
            } else {
                $from_user_id = $request->from_user_id;
            }
            if (!empty($request->to_name)) {
                $toUser = new OtherUser();
                $toUser->name = $request->to_name;
                $toUser->father_name = $request->to_father_name;
                $toUser->email = $request->to_email;
                $toUser->phone = $request->to_phone;
                $toUser->cnic = $request->to_cnic;
                $toUser->company = $request->to_company;
                $toUser->country = $request->to_country;
                $toUser->address = $request->to_address;
                $toUser->save();
                $to_user_id = $toUser->id;
            } else {
                $to_user_id = $request->to_user_id;
            }
            $alteration = Alteration::findOrFail($id);
            $alteration->vehicle_id = $request->vehicle_id;
            $alteration->from_user_id = $from_user_id;
            $alteration->to_user_id = $to_user_id;
            $alteration->type = $request->type;
            $alteration->alteration_date = $request->alteration_date ?? now();
            $alteration->notes = $request->notes;
            $alteration->save();

            DB::commit();
            return redirect()->route('dashboard.alterations.index')->with('success', 'Alteration Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Alteration Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete alteration');
        try {
            $alteration = Alteration::findOrFail($id);
            $alteration->delete();
            return redirect()->route('dashboard.alterations.index')->with('success', 'Alteration Deleted Successfully');
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Alteration Delete Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }
}
