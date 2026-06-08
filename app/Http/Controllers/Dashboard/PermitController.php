<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OtherUser;
use App\Models\Permit;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PermitController extends Controller
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
        $this->authorize('create permit');
        try {
            $users = OtherUser::where('is_active', 'active')->get();
            $vehicles = Vehicle::where('is_active', 'active')->get();
            return view('dashboard.permits.create', compact('users', 'vehicles'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Permit Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create permit');
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'permit_date' => 'nullable|date',
            'permit_details' => 'nullable|string',
            'notes' => 'nullable|string',
            // PERMIT HOLDER USER
            'permit_holder_id' => 'required_without:holder_name|nullable|exists:other_users,id',
            'holder_name' => 'required_without:permit_holder_id|nullable|string|max:255',
            'holder_father_name' => 'required_with:holder_name|nullable|string|max:255',
            'holder_email' => 'nullable|email',
            'holder_phone' => 'nullable|string|max:20',
            'holder_cnic' => 'nullable|string|max:20',
            'holder_company' => 'nullable|string|max:255',
            'holder_country' => 'nullable|string|max:255',
            'holder_address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            if (!empty($request->holder_name)) {
                $permitHolder = new OtherUser();
                $permitHolder->name = $request->holder_name;
                $permitHolder->father_name = $request->holder_father_name;
                $permitHolder->email = $request->holder_email;
                $permitHolder->phone = $request->holder_phone;
                $permitHolder->cnic = $request->holder_cnic;
                $permitHolder->company = $request->holder_company;
                $permitHolder->country = $request->holder_country;
                $permitHolder->address = $request->holder_address;
                $permitHolder->save();
                $permit_holder_id = $permitHolder->id;
            } else {
                $permit_holder_id = $request->permit_holder_id;
            }
            $permit = new Permit();
            $permit->vehicle_id = $request->vehicle_id;
            $permit->permit_holder_id = $permit_holder_id;
            $permit->type = $request->type;
            $permit->permit_date = $request->permit_date ?? now();
            $permit->permit_details = $request->permit_details;
            $permit->notes = $request->notes;
            $permit->save();

            DB::commit();
            return redirect()->route('dashboard.permits.index')->with('success', 'Permit Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Permit Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view permit');
        try {
            $permit = Permit::with('permitHolder', 'vehicle')->findOrFail($id);
            return view('dashboard.permits.show', compact('permit'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Permit Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update permit');
        try {
            $permit = Permit::findOrFail($id);
            $users = OtherUser::where('is_active', 'active')->get();
            $vehicles = Vehicle::where('is_active', 'active')->get();
            return view('dashboard.permits.edit', compact('permit', 'users', 'vehicles'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Permit Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update permit');
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'permit_date' => 'nullable|date',
            'permit_details' => 'nullable|string',
            'notes' => 'nullable|string',
            // PERMIT HOLDER USER
            'permit_holder_id' => 'required_without:holder_name|nullable|exists:other_users,id',
            'holder_name' => 'required_without:permit_holder_id|nullable|string|max:255',
            'holder_father_name' => 'required_with:holder_name|nullable|string|max:255',
            'holder_email' => 'nullable|email',
            'holder_phone' => 'nullable|string|max:20',
            'holder_cnic' => 'nullable|string|max:20',
            'holder_company' => 'nullable|string|max:255',
            'holder_country' => 'nullable|string|max:255',
            'holder_address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            if (!empty($request->holder_name)) {
                $permitHolder = new OtherUser();
                $permitHolder->name = $request->holder_name;
                $permitHolder->father_name = $request->holder_father_name;
                $permitHolder->email = $request->holder_email;
                $permitHolder->phone = $request->holder_phone;
                $permitHolder->cnic = $request->holder_cnic;
                $permitHolder->company = $request->holder_company;
                $permitHolder->country = $request->holder_country;
                $permitHolder->address = $request->holder_address;
                $permitHolder->save();
                $permit_holder_id = $permitHolder->id;
            } else {
                $permit_holder_id = $request->permit_holder_id;
            }
            $permit = Permit::findOrFail($id);
            $permit->vehicle_id = $request->vehicle_id;
            $permit->permit_holder_id = $permit_holder_id;
            $permit->type = $request->type;
            $permit->permit_date = $request->permit_date ?? now();
            $permit->permit_details = $request->permit_details;
            $permit->notes = $request->notes;
            $permit->save();

            DB::commit();
            return redirect()->route('dashboard.permits.index')->with('success', 'Permit Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Permit Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete permit');
        try {
            $permit = Permit::findOrFail($id);
            $permit->delete();
            return redirect()->route('dashboard.permits.index')->with('success', 'Permit Deleted Successfully');
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Permit Delete Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }
}
