<?php

namespace App\Http\Controllers;

use App\Models\Subcategorie;
use App\Http\Requests\StoreSubcategorieRequest;
use App\Http\Requests\UpdateSubcategorieRequest;

class SubcategorieController extends Controller
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
     * @param  \App\Http\Requests\StoreSubcategorieRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubcategorieRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subcategorie  $subcategorie
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategorie $subcategorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subcategorie  $subcategorie
     * @return \Illuminate\Http\Response
     */
    public function edit(Subcategorie $subcategorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubcategorieRequest  $request
     * @param  \App\Models\Subcategorie  $subcategorie
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubcategorieRequest $request, Subcategorie $subcategorie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subcategorie  $subcategorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategorie $subcategorie)
    {
        //
    }
}
