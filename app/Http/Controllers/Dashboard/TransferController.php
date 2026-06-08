<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CaseTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view transfer');
        try {
            $transfers = CaseTransfer::with('vehicleCase')->get();
            return view('dashboard.transfers.index', compact('transfers'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Transfer Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $this->authorize('create transfer');
    //     try {
    //         $users = OtherUser::where('is_active', 'active')->get();
    //         $vehicles = Vehicle::where('is_active', 'active')->get();
    //         return view('dashboard.transfers.create', compact('users', 'vehicles'));
    //     } catch (\Throwable $th) {
    //         // throw $th;
    //         Log::error("Transfer Create Failed:" . $th->getMessage());
    //         return redirect()->back()->with('error', "Something went wrong! Please try again later");
    //     }
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $this->authorize('create transfer');
    //     $validator = Validator::make($request->all(), [
    //         'type' => 'required|string|max:255',
    //         'vehicle_id' => 'required|exists:vehicles,id',
    //         'transfer_date' => 'nullable|date',
    //         'notes' => 'nullable|string',
    //         // FROM USER
    //         'from_user_id' => 'required_without:from_name|nullable|exists:users,id',
    //         'from_name' => 'required_without:from_user_id|nullable|string|max:255',
    //         'from_father_name' => 'required_with:from_name|nullable|string|max:255',
    //         'from_email' => 'nullable|email',
    //         'from_phone' => 'nullable|string|max:20',
    //         'from_cnic' => 'nullable|string|max:20',
    //         'from_company' => 'nullable|string|max:255',
    //         'from_country' => 'nullable|string|max:255',
    //         'from_address' => 'nullable|string',

    //         // TO USER
    //         'to_user_id' => 'required_without:to_name|nullable|exists:users,id',
    //         'to_name' => 'required_without:to_user_id|nullable|string|max:255',
    //         'to_father_name' => 'required_with:to_name|nullable|string|max:255',
    //         'to_email' => 'nullable|email',
    //         'to_phone' => 'nullable|string|max:20',
    //         'to_cnic' => 'nullable|string|max:20',
    //         'to_company' => 'nullable|string|max:255',
    //         'to_country' => 'nullable|string|max:255',
    //         'to_address' => 'nullable|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
    //     }

    //     try {
    //         DB::beginTransaction();
    //         if (!empty($request->from_name)) {
    //             $fromUser = new OtherUser();
    //             $fromUser->name = $request->from_name;
    //             $fromUser->father_name = $request->from_father_name;
    //             $fromUser->email = $request->from_email;
    //             $fromUser->phone = $request->from_phone;
    //             $fromUser->cnic = $request->from_cnic;
    //             $fromUser->company = $request->from_company;
    //             $fromUser->country = $request->from_country;
    //             $fromUser->address = $request->from_address;
    //             $fromUser->save();
    //             $from_user_id = $fromUser->id;
    //         } else {
    //             $from_user_id = $request->from_user_id;
    //         }
    //         if (!empty($request->to_name)) {
    //             $toUser = new OtherUser();
    //             $toUser->name = $request->to_name;
    //             $toUser->father_name = $request->to_father_name;
    //             $toUser->email = $request->to_email;
    //             $toUser->phone = $request->to_phone;
    //             $toUser->cnic = $request->to_cnic;
    //             $toUser->company = $request->to_company;
    //             $toUser->country = $request->to_country;
    //             $toUser->address = $request->to_address;
    //             $toUser->save();
    //             $to_user_id = $toUser->id;
    //         } else {
    //             $to_user_id = $request->to_user_id;
    //         }
    //         $transfer = new Transfer();
    //         $transfer->vehicle_id = $request->vehicle_id;
    //         $transfer->from_user_id = $from_user_id;
    //         $transfer->to_user_id = $to_user_id;
    //         $transfer->type = $request->type;
    //         $transfer->transfer_date = $request->transfer_date ?? now();
    //         $transfer->notes = $request->notes;
    //         $transfer->save();

    //         DB::commit();
    //         return redirect()->route('dashboard.transfers.index')->with('success', 'Transfer Created Successfully');
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         Log::error('Transfer Store Failed', ['error' => $th->getMessage()]);
    //         return redirect()->back()->with('error', "Something went wrong! Please try again later");
    //         throw $th;
    //     }
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view transfer');
        try {
            $transfer = CaseTransfer::with('vehicleCase')->findOrFail($id);
            return view('dashboard.transfers.show', compact('transfer'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Transfer Show Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update transfer');
        try {
            $transfer = CaseTransfer::with('vehicleCase')->findOrFail($id);
            return view('dashboard.transfers.edit', compact('transfer'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Transfer Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update transfer');
        $validator = Validator::make($request->all(), [
            'from_name' => 'required|string|max:255',
            'from_s_o' => 'required|string|max:255',
            'from_nic' => 'required|string|max:255',
            'from_biometric' => 'required|string|max:255',
            'to_name' => 'required|string|max:255',
            'to_s_o' => 'required|string|max:255',
            'to_nic' => 'required|string|max:255',
            'to_biometric' => 'required|string|max:255',
            'engine_no' => 'required|string|max:255',
            'chassis_no' => 'required|string|max:255',
            'wheels' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:255',
            'last_tax' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();

            $transfer = CaseTransfer::findOrFail($id);
            $transfer->from_name = $request->from_name;
            $transfer->from_s_o = $request->from_s_o;
            $transfer->from_nic = $request->from_nic;
            $transfer->from_biometric = $request->from_biometric;
            $transfer->to_name = $request->to_name;
            $transfer->to_s_o = $request->to_s_o;
            $transfer->to_nic = $request->to_nic;
            $transfer->to_biometric = $request->to_biometric;
            $transfer->engine_no = $request->engine_no;
            $transfer->chassis_no = $request->chassis_no;
            $transfer->wheels = $request->wheels;
            $transfer->weight = $request->weight;
            $transfer->last_tax = $request->last_tax;
            $transfer->save();

            DB::commit();
            return redirect()->route('dashboard.transfers.index')->with('success', 'Transfer Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Transfer Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete transfer');
        try {
            $transfer = CaseTransfer::findOrFail($id);
            $transfer->delete();
            return redirect()->route('dashboard.transfers.index')->with('success', 'Transfer Deleted Successfully');
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Transfer Delete Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }
}
