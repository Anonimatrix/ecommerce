<?php

namespace App\Http\Controllers;

use App\Cache\AdressCacheRepository;
use App\Models\Adress;
use App\Http\Requests\StoreAdressRequest;
use App\Http\Requests\UpdateAdressRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

    public function __construct(AdressCacheRepository $repository, Request $request)
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
        $adresses = $this->repository->paginatedUserAdresses(10, Auth::user());

        return Inertia::render('Adress/Index', compact('adresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Adress/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAdressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdressRequest $request)
    {
        $request->merge(['user_id' => Auth::id()]);

        $this->repository->create($request->only(['adress', 'city', 'country', 'user_id', 'postal_code']));

        return redirect()->back();
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
    public function edit()
    {
        $this->authorize('owner', $this->adress);

        $adress = $this->adress;

        return Inertia::render('Adress/Edit', compact('adress'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdressRequest  $request
     * @param  \App\Models\Adress  $adress
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdressRequest $request)
    {
        $this->authorize('owner', $this->adress);

        $this->repository->update($request->only(['postal_code', 'country', 'city', 'adress']), $this->adress);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Adress  $adress
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->authorize('owner', $this->adress);

        $this->adress->delete();

        return redirect()->back();
    }
}
