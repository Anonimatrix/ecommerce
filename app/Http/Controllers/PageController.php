<?php

namespace App\Http\Controllers;

use App\Repositories\Cache\ProductCache;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    protected $productRepository;
    public function __construct(ProductCache $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function home()
    {
        //Get products of latest search of authenticated
        $products_latest_search = $this->productRepository->getProductsOfLatestSearch();

        //Get similar products of latest view of authenticated
        $similar_products_latest_view = $this->productRepository->getSimilarsOfLatestViewedProduct();

        //Get products most searched
        $products_of_most_searched = $this->productRepository->getMostSearched();

        //Get products most sold
        $products_most_sold = $this->productRepository->getMostSold();

        //Get products for categories with most solds
        $products_subcategorie_with_most_solds  = $this->productRepository->getProductsOfSubcategorieWithMostSolds();

        //Get products similar to authenticated
        $products_similar_to_user  = $this->productRepository->getProductsOfSimilarUsers();

        //Get products with most views
        $products_most_viewed = $this->productRepository->getMostViewed();

        return Inertia::render('Home', compact(
            'products_latest_search',
            'similar_products_latest_view',
            'products_of_most_searched',
            'products_most_sold',
            'products_subcategorie_with_most_solds',
            'products_similar_to_user',
            'products_most_viewed'
        ));
    }
}
