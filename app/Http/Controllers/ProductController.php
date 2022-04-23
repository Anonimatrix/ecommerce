<?php

namespace App\Http\Controllers;

use App\Cache\ProductCache;
use App\Cache\TagCache;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Helpers\Slugify;
use App\Http\Requests\SearchRequest;
use App\Models\Search;
use App\Traits\Http\ProductController\HasPhotos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductController extends Controller
{
    use HasPhotos;

    protected $repository;
    protected $tagRepository;
    protected $product;

    public function setProduct(Request $request)
    {
        $product_id = $request->route('product_id');

        if ($product_id) {
            $this->product = $this->repository->getById($product_id);
        }
    }

    public function __construct(ProductCache $productCache, TagCache $tagCache, Request $request)
    {
        $this->repository = $productCache;
        $this->tagRepository = $tagCache;
        $this->setProduct($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagination = Product::paginate(15);

        return Inertia::render('Products/Index', compact('pagination'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->repository->create($request->only(['title', 'description', 'price', 'subcategorie_id', 'stock', 'paused_at']) + ['user_id' => Auth::id()]);

        $this->uploadPhotos($product, $request->photos);

        $tags_ids = $this->tagRepository->createMany($request->input('tags_titles'));

        $product->tags()->sync($tags_ids);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $product = $this->product;

        return Inertia::render('Products/Show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $this->authorize('update', $this->product);

        $product = $this->product;

        return Inertia::render('Products/Edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request)
    {
        $this->authorize('update', $this->product);

        if ($request->photos) {
            $this->product->photos()->forceDelete();
            $this->uploadPhotos($this->product, $request->photos);
        }

        $this->product->update($request->validated());

        return redirect()->back();
    }

    public function pause()
    {
        $this->authorize('pause', $this->product);

        $this->product->pause();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->authorize('delete', $this->product);

        $this->product->delete();

        return redirect()->back();
    }

    public function forceDestroy()
    {
        $this->authorize('forceDelete', $this->product);

        $this->product->forceDelete();

        return redirect()->back();
    }

    public function search(SearchRequest $request)
    {
        $search = strtolower(Slugify::slugifyReverse($request->input('q')));

        if (Auth::user()) Search::updateOrCreate(['content' => $search, 'user_id' => Auth::id()]);

        $products = $this->repository->search($search);

        return Inertia::render('Products/Search', compact('products'));
    }
}
