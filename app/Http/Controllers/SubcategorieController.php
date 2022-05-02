<?php

namespace App\Http\Controllers;

use App\Cache\SubcategorieCacheRepository;
use App\Models\Subcategorie;
use App\Http\Requests\StoreSubcategorieRequest;
use App\Http\Requests\UpdateSubcategorieRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubcategorieController extends Controller
{
    protected $repository;
    protected $subcategorie;

    public function setSubcategorie(Request $request)
    {
        $subcategorie_id = $request->route('subcategorie_id');

        if ($subcategorie_id) {
            $this->subcategorie = $this->repository?->getById($subcategorie_id);
        }
    }

    public function __construct(SubcategorieCacheRepository $subcategorieCache, Request $request)
    {
        $this->repository = $subcategorieCache;
        $this->setSubcategorie($request);
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
        return Inertia::render('Subcategories/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubcategorieRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubcategorieRequest $request)
    {
        $subcategorie = $this->repository->create($request->only(['title', 'categorie_id']));

        return redirect()->route('subcategories.show', $subcategorie->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subcategorie  $subcategorie
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $products = $this->repository->paginatedProductsOfSubcategorie(10, $this->subcategorie);

        return Inertia::render('Subcategories/Show', ['products' => $products, 'subcategorie' => $this->subcategorie]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subcategorie  $subcategorie
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $subcategorie = $this->subcategorie;

        return Inertia::render('Subcategories/Edit', compact('subcategorie'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubcategorieRequest  $request
     * @param  \App\Models\Subcategorie  $subcategorie
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubcategorieRequest $request)
    {
        $this->repository->update($request->only(['title', 'categorie_id']), $this->subcategorie);

        return redirect()->route('subcategories.show', $this->subcategorie->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subcategorie  $subcategorie
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->repository->delete($this->subcategorie);

        return redirect()->back();
    }
}
