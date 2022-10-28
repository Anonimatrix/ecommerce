<?php

namespace App\Http\Controllers;

use App\Repositories\Cache\ProductCache;
use App\Repositories\Cache\SearchCacheRepository;
use App\Repositories\Cache\TagCache;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Helpers\Slugify;
use App\Http\Requests\SearchRequest;
use App\Models\Search;
use App\Repositories\Cache\UserCacheRepository;
use App\Traits\Http\HasPhotos;
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
        $pagination = $this->repository->callFuncwithManagedSortAndFilter('paginate', null, [], 15);

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
        $product = $this->repository->create($request->validated() + ['user_id' => Auth::id()]);

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
    public function show($slug, UserCacheRepository $userRepository)
    {
        $product = $this->repository->getBySlug($slug, true);

        $similarProducts = $this->repository->getSimilarProducts($product->id, 7, ['tags_count', 'DESC'], []);

        $sellerProducts = $this->repository->getSellerProducts($product->id, 7, ['tags_count', 'DESC'], [], $userRepository);

        return Inertia::render('Products/Show', compact('product', 'similarProducts', 'sellerProducts'));
    }

    public function checkout()
    {
        $product = $this->product;

        return Inertia::render('Products/Checkout', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $product = $this->repository->getBySlug($slug, false);

        $this->authorize('update', $product);

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

    public function search(SearchRequest $request, SearchCacheRepository $searchRepository, UserCacheRepository $userRepository)
    {
        $search = strtolower(Slugify::slugifyReverse($request->input('q')));

        if ($userRepository->authenticated()) $searchRepository->updateOrCreate(['content' => $search, 'user_id' => Auth::id()]);

        $pagination = $this->repository->callFuncwithManagedSortAndFilter('search', ['tags_count', 'DESC'], [], $search, 15);

        return Inertia::render('Products/Search', compact('pagination'));
    }

    public function ownProducts()
    {
        $user_id = Auth::id();

        $pagination = $this->repository->callFuncwithManagedSortAndFilter('getOfUserPaginated', null, [], $user_id, 8);

        return Inertia::render('Profile/Products', compact('pagination'));
    }

    public function sellerProducts($user_id)
    {
        $pagination = $this->repository->callFuncwithManagedSortAndFilter('getOfUserPaginated', null, [], $user_id, 8);

        return Inertia::render('Users/Products', compact('pagination'));
    }
}
