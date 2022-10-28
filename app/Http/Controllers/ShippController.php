<?php

namespace App\Http\Controllers;

use App\Repositories\Cache\AddressCacheRepository;
use App\Repositories\Cache\ProductCache;
use App\Http\Requests\ShippQuoteRequest;
use App\Models\Shipp;
use App\Http\Requests\StoreShippRequest;
use App\Http\Requests\UpdateShippRequest;
use App\Services\Shipping\Contracts\ShippGatewayInterface;
use Illuminate\Http\Request;

class ShippController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $repository;
    protected $product;
    protected $address;
    protected $addressRepository;

    public function setProduct(Request $request)
    {
        $product_id = $request->route('product_id');

        if ($product_id) {
            $this->product = $this->repository->getById($product_id);
        }
    }

    public function setAddress(Request $request)
    {
        $address_id = $request->route('address_id');

        if ($address_id) {
            $this->address = $this->addressRepository->getById($address_id);
        }
    }

    public function __construct(ProductCache $productCache, AddressCacheRepository $addressCache, Request $request)
    {
        $this->repository = $productCache;
        $this->addressRepository = $addressCache;
        $this->setProduct($request);
        $this->setAddress($request);
    }

    public function quote(ShippQuoteRequest $request, ShippGatewayInterface $shippGateway)
    {
        $res = $shippGateway->quote($request->input('postal_code'), $this->product, $request->input('shipp_type'));

        if ($res['status_code'] >= 300) {
            return response()->json(['status' => 'failed', 'message' => 'error getting info with shipments provider']);
        }

        return response()->json(['shipp_price' => $res['price']]);
    }

    public function listSucursales(ShippGatewayInterface $shippGateway)
    {
        $address = $this->address;
        $sucursales = $shippGateway->listSucursales($address);

        return response()->json(['sucursales' => $sucursales]);
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
