<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    protected function getRoleValues()
    {
        return ['owner', 'cashier'];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'roles' => ['required', 'in:' . implode(',', $this->getRoleValues())],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->password = $request->password;
            $user->photo_path = $request->photo_path;
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->roles = $request->roles;
            $user->save();

            return response()->json([
                "message" => "User Added."
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!empty($user)) {
            return response()->json($user);
        } else {
            return response()->json([
                "message" => "User not found."
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'roles' => ['required', 'in:' . implode(',', $this->getRoleValues())],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->messages(),
            ], 422);
        } else {
            $user = User::find($id);

            if ($user) {
                $user->update([
                    'name' => $request->name,
                    'password' => $request->password,
                    'photo_path' => $request->photo_path,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'roles' => $request->roles
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => 'User updated.'
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "User with ID $id not found."
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
            $user->delete();

            return response()->json([
                "message" => "User deleted."
            ], 202);
        } else {
            return response()->json([
                "message" => "User not found."
            ], 404);
        }
    }
}
