<?php

namespace App\Http\Controllers;

use App\Repositories\Cache\CategorieCache;
use App\Filters\Filters;
use App\Http\Requests\SearchCategorieRequest;
use App\Models\Categorie;
use App\Http\Requests\StoreCategorieRequest;
use App\Http\Requests\UpdateCategorieRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategorieController extends Controller
{

    protected $repository;
    protected $categorie;

    public function setCategorie(Request $request)
    {
        $categorie_id = $request->route('categorie_id');

        if ($categorie_id) {
            $this->categorie = $this->repository->getById($categorie_id);
        }
    }

    public function __construct(CategorieCache $categorieCache, Request $request)
    {
        $this->repository = $categorieCache;
        $this->setCategorie($request);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SearchCategorieRequest $request)
    {
        $searchString = $request->input('q') ?? '';

        $categories = $this->repository->callFuncwithManagedSortAndFilter('all', ['title', 'ASC'], [Filters::search_by_title($searchString)]);

        return Inertia::render('Categories/Index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategorieRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategorieRequest $request)
    {
        $this->repository->create($request->validated());

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function show(Categorie $categorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $categorie = $this->categorie;

        return Inertia::render('Categories/Edit', compact('categorie'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategorieRequest  $request
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategorieRequest $request)
    {
        $this->repository->update($request->validated(), $this->categorie);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        if (count($this->categorie->subcategories) == 0) {
            $deleted = $this->repository->delete($this->categorie);
            return response()->json(compact('deleted'));
        }

        return response()->json(['status' => 'failed', 'message' => 'for destroy categorie this was to be void of subcategories'], 500);
    }

    public function moveSubcategoriesToOtherCategorie(Request $request)
    {
        $request->validate([
            'to_categorie_id' => 'required|exists:categories,id|integer'
        ]);

        $to_categorie_id = $request->to_categorie_id;

        $this->repository->moveSubcategoriesToOtherCategorie($this->categorie->id, $to_categorie_id);

        return redirect()->back();
    }

    public function removeAllForCategorie()
    {
        $this->repository->removeSubcategoriesOfCategorie($this->categorie);

        return redirect()->back();
    }
}
