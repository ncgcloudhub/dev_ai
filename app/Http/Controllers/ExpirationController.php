<?php

namespace App\Http\Controllers;

use App\Models\Expiration;
use Illuminate\Http\Request;

class ExpirationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expirations = Expiration::orderBy('expires_on')->get();
        return view('admin.expirations.expiration_create', compact('expirations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expirations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'expires_on' => 'required|date',
        ]);

        Expiration::create($request->all());

        return redirect()->route('expirations.index')->with('success', 'Expiration entry added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expiration $expiration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expiration $expiration)
    {
        $expirations = Expiration::orderBy('expires_on')->get();
        return view('admin.expirations.expiration_edit', compact('expiration', 'expirations'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expiration $expiration)
    {
        $request->validate([
            'type' => 'required',
            'expires_on' => 'required|date',
        ]);
    
        $expiration->update($request->all());
    
        return redirect()->route('expirations.index')->with('success', 'Expiration updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expiration $expiration)
    {
        $expiration->delete();
        return redirect()->route('expirations.index')->with('success', 'Expiration deleted successfully.');
    }
    
}
