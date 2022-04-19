<?php

namespace App\Http\Controllers;

use App\Cache\AdressCache;
use App\Models\Adress;
use App\Http\Requests\StoreAdressRequest;
use App\Http\Requests\UpdateAdressRequest;
use Illuminate\Http\Request;

class AdressController extends Controller
{
    protected $repository;
    protected $adress;

    public function setAdress(Request $request)
    {
        $adress_id = $request->route()->parameter('adress_id');

        if ($adress_id) {
            $this->adress = $this->repository->getById($adress_id);
        }
    }

    public function __construct(AdressCache $repository, Request $request)
    {
        $this->repository = $repository;
        $this->setAdress($request);
    }

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
     * @param  \App\Http\Requests\StoreAdressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdressRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adress  $adress
     * @return \Illuminate\Http\Response
     */
    public function show(Adress $adress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Adress  $adress
     * @return \Illuminate\Http\Response
     */
    public function edit(Adress $adress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdressRequest  $request
     * @param  \App\Models\Adress  $adress
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdressRequest $request, Adress $adress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adress  $adress
     * @return \Illuminate\Http\Response
     */
    public function destroy(Adress $adress)
    {
        //
    }
}
