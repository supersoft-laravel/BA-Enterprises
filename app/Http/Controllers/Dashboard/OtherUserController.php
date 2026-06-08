<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OtherUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OtherUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view user');
        try {
            $users = OtherUser::all();
            return view('dashboard.other-users.index', compact('users'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Other User Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create user');
        try {
            return view('dashboard.other-users.create');
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Other User Create Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create user');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:other_users,email',
            'phone' => 'nullable|string|max:20|unique:other_users,phone',
            'cnic' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $user = new OtherUser();
            $user->name = $request->name;
            $user->father_name = $request->father_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->cnic = $request->cnic;
            $user->company = $request->company;
            $user->country = $request->country;
            $user->address = $request->address;
            $user->save();

            DB::commit();
            return redirect()->route('dashboard.other-users.index')->with('success', 'Other User Created Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Other User Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('update user');
        try {
            $user = OtherUser::findOrFail($id);
            return view('dashboard.other-users.edit', compact('user'));
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Other User Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update user');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:other_users,email,' . $id,
            'phone' => 'nullable|string|max:20|unique:other_users,phone,' . $id,
            'cnic' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $user = OtherUser::findOrFail($id);
            $user->name = $request->name;
            $user->father_name = $request->father_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->cnic = $request->cnic;
            $user->company = $request->company;
            $user->country = $request->country;
            $user->address = $request->address;
            $user->save();

            DB::commit();
            return redirect()->route('dashboard.other-users.index')->with('success', 'Other User Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Other User Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete user');
        try {
            $user = OtherUser::findOrFail($id);
            $user->delete();
            return redirect()->route('dashboard.other-users.index')->with('success', 'Other User Deleted Successfully');
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("Other User Delete Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function updateStatus(string $id)
    {
        $this->authorize('update user');
        try {
            $user = OtherUser::findOrFail($id);
            $message = $user->is_active == 'active' ? 'User Deactivated Successfully' : 'User Activated Successfully';
            if ($user->is_active == 'active') {
                $user->is_active = 'inactive';
                $user->save();
            } else {
                $user->is_active = 'active';
                $user->save();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('User Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
