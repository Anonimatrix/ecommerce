<?php

namespace App\Http\Controllers;

use App\Models\Shipp;
use App\Http\Requests\StoreShippRequest;
use App\Http\Requests\UpdateShippRequest;

class ShippController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreShippRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShippRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shipp  $shipp
     * @return \Illuminate\Http\Response
     */
    public function show(Shipp $shipp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shipp  $shipp
     * @return \Illuminate\Http\Response
     */
    public function edit(Shipp $shipp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShippRequest  $request
     * @param  \App\Models\Shipp  $shipp
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShippRequest $request, Shipp $shipp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipp  $shipp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shipp $shipp)
    {
        //
    }
}
