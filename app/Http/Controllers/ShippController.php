<?php

namespace App\Http\Controllers;

use App\Cache\AdressCacheRepository;
use App\Cache\ProductCache;
use App\Http\Requests\ShippQuoteRequest;
use App\Models\Shipp;
use App\Http\Requests\StoreShippRequest;
use App\Http\Requests\UpdateShippRequest;
use App\Shipping\Contracts\ShippGatewayInterface;
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
    protected $adress;
    protected $adressRepository;

    public function setProduct(Request $request)
    {
        $product_id = $request->route('product_id');

        if ($product_id) {
            $this->product = $this->repository->getById($product_id);
        }
    }

    public function setAdress(Request $request)
    {
        $adress_id = $request->route('adress_id');

        if ($adress_id) {
            $this->adress = $this->adressRepository->getById($adress_id);
        }
    }

    public function __construct(ProductCache $productCache, AdressCacheRepository $adressCache, Request $request)
    {
        $this->repository = $productCache;
        $this->adressRepository = $adressCache;
        $this->setProduct($request);
        $this->setAdress($request);
    }

    public function quote(ShippQuoteRequest $request, ShippGatewayInterface $shippGateway)
    {
        $prices = $shippGateway->quote($request->input('postal_code'), $this->product, $request->input('shipp_type'));

        return response()->json(['shipp_price' => $prices['tarifaConIva']['total']]);
    }

    public function listSucursales(ShippGatewayInterface $shippGateway)
    {
        $adress = $this->adress;
        $sucursales = $shippGateway->listSucursales($adress);

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
