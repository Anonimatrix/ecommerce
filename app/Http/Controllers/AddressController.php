<?php

namespace App\Http\Controllers;

use App\Repositories\Cache\AddressCacheRepository;
use App\Models\Address;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Repositories\Cache\UserCacheRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AddressController extends Controller
{
    protected $repository;
    protected $address;

    public function setAddress(Request $request)
    {
        $address_id = $request->address_id;

        if ($address_id) {
            $this->address = $this->repository->getById($address_id);
        }
    }

    public function __construct(AddressCacheRepository $repository, Request $request)
    {
        $this->repository = $repository;
        $this->setAddress($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserCacheRepository $userRepository)
    {
        $addresses = $this->repository->paginatedUserAddresses(10, $userRepository->authenticated());

        return Inertia::render('Address/Index', compact('addresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Address/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAddressRequest $request)
    {
        $request->merge(['user_id' => Auth::id()]);

        $this->repository->create($request->only(['address', 'city', 'country', 'user_id', 'postal_code']));

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $this->authorize('owner', $this->address);

        $address = $this->address;

        return Inertia::render('Address/Edit', compact('address'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAddressRequest  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAddressRequest $request)
    {
        $this->authorize('owner', $this->address);

        $this->repository->update($request->only(['postal_code', 'country', 'city', 'address']), $this->address);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->authorize('owner', $this->address);

        $this->address->delete();

        return redirect()->back();
    }
}
