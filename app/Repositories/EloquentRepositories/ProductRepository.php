<?php

namespace App\Repositories\EloquentRepositories;

use App\Repositories\Cache\SearchCacheRepository;
use App\Repositories\Cache\SubcategorieCacheRepository;
use App\Repositories\Cache\UserCacheRepository;
use App\Repositories\Cache\ViewCacheRepository;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\View;
use App\Statuses\OrderStatus;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $userRepository;
    protected $searchRepository;
    protected $viewRepository;
    protected $subcategorieRepository;

    public function __construct(Product $product, UserCacheRepository $userRepository, SearchCacheRepository $searchRepository, ViewCacheRepository $viewRepository, SubcategorieCacheRepository $subcategorieRepository)
    {
        parent::__construct($product);
        $this->userRepository = $userRepository;
        $this->searchRepository = $searchRepository;
        $this->viewRepository = $viewRepository;
        $this->subcategorieRepository = $subcategorieRepository;
    }

    public function getBySlug(string $slug, bool $withTrashed = false)
    {
        $query = $this->model->where('slug', $slug);

        if ($withTrashed) $this->withTrashed($query);

        return $query->first();
    }

    public function search($search, $quantity, $sort_by, $filters)
    {
        $query = $this->model->where(DB::raw('LOWER(title)'), 'LIKE', "%$search%")->orWhereHas(
            'tags',
            fn ($query) =>
            $query->where('title', 'LIKE', "%$search%")
        )
            ->withCount('tags');

        $this->sortAndFilter($query, $sort_by, $filters);

        return $query->paginate($quantity);
    }

    public function getOfUserPaginated($user_id, $quantity, $sort_by, $filters)
    {
        $query = $this->model->where('user_id', $user_id);

        $this->sortAndFilter($query, $sort_by, $filters);

        return $query->paginate($quantity);
    }

    public function getSimilarProducts($product_id, $quantity, $sort_by, $filters)
    {
        // RAW SQL QUERY WITH INNER JOIN (SUBCATEGORIE)
        //select po.* from subcategories s inner join products p on p.id = 1 and s.id = p.subcategorie_id inner join products po on po.subcategorie_id = p.subcategorie_id;

        //RAW SQL QUERY WITH SUB-QUERY
        //select * from products where subcategorie_id in (select s.id from subcategories s inner join products p on p.subcategorie_id = s.id where p.id = 1 )

        //RAW SQL QUERY WITH INNER JOIN (TAGS)
        //select po.title from products p inner join product_tag pt on pt.product_id = 1 and pt.product_id = p.id inner join tags t on pt.tag_id = t.id inner join product_tag pt2 on t.id = pt2.tag_id inner join products po on pt2.product_id = po.id and po.id != 1 group by po.id;
        $query = DB::table('subcategories AS s')
            ->selectRaw('po.*, COUNT(po.id) AS tags_count')
            ->join(
                'products AS p',
                fn ($join) =>
                $join->on('p.subcategorie_id', '=', 's.id')->on('p.id', '=', $product_id)
            )
            ->join(
                'products AS po',
                fn ($join) =>
                $join->on('po.subcategorie_id', '=', 'p.subcategorie_id')->on('pt2.product_id', '=', 'po.id')
            )
            ->join(
                'product_tag AS pt',
                fn ($join) =>
                $join->on('pt.product_id', '=', $product_id)->on('p.id', '=', 'pt.product_id')
            )
            ->join('tags AS t', 'pt.tag_id', '=', 't.id')
            ->join('product_tag AS pt2', 'pt2.tag_id', '=', 't.id')
            ->whereRaw("po.id != $product_id")
            ->groupByRaw('po.id')
            ->limit($quantity);

        $this->sortAndFilter($query, $sort_by, $filters);

        return $query->get();
    }

    public function getSellerProducts($user_id, $quantity, $sort_by, $filters)
    {
        $query = $this->userRepository->getById($user_id)->products()->limit($quantity);

        $this->sortAndFilter($query, $sort_by, $filters);

        return $query->get();
    }

    public function getProductsOfLatestSearch()
    {
        $search = $this->searchRepository->latestOfAuthenticated($this->userRepository);

        if (!isset($search)) return [];

        return $this->search($search->content, 10, ['count_tags', 'DESC'], [])->items();
    }

    public function getSimilarsOfLatestViewedProduct()
    {
        $quantity = 10;
        $view = $this->viewRepository->latestOfAuthenticated($quantity, $this->userRepository);

        if (empty($view)) return [];

        $viewProduct = $view->product;

        $products = $this->getSimilarProducts($viewProduct->id, $quantity - 1, ['count_tags', 'DESC'], []);

        $products->push($viewProduct);

        return $products->all();
    }

    public function getMostSearched()
    {
        $search = $this->searchRepository->searchInMostSearchedWithLimit('', 1);

        if ($search->isEmpty()) return [];

        return $this->search($search[0]->content, 10, ['count_tags', 'DESC'], [])->items();
    }

    public function getMostSold()
    {
        // $most_sold = Order::select(DB::raw('COUNT(id) AS products_count'))
        //     ->where('status', OrderStatus::COMPLETED)
        //     ->orderBy('products_count', 'DESC')
        //     ->groupBy('product_id')
        //     ->limit(10)
        //     ->get();

        $most_sold =  $this->model
            ->withCount('orders')
            ->whereRelation(
                'orders',
                fn ($query) => $query->where('status', OrderStatus::COMPLETED)
            )
            ->orderBy('orders_count', 'DESC')
            ->limit(10)
            ->get();

        return $most_sold;
    }

    public function getProductsOfSubcategorieWithMostSolds()
    {
        $subcategories = $this->subcategorieRepository->withMostProductsSold();

        if ($subcategories->isEmpty()) return [];

        return $subcategories->map(function ($subcategorie) {
            return [
                'title' => $subcategorie->title,
                'products' => $subcategorie->products()->orderBy('orders_count')->limit(10)->get()
            ];
        });
    }

    public function getProductsOfSimilarUsers()
    {
        $user = $this->userRepository->authenticated();

        $views = $this->viewRepository->latestOfAuthenticated(5, $this->userRepository);

        if ($views->isEmpty()) return [];

        $views_id = $views->pluck('id');

        $products = $this->model
            ->leftJoin('views as v', function ($join) use ($views_id) {
                $join->on('v.product_id', '=', 'products.id')
                    ->whereIn('v.id', $views_id->toArray());
            })
            ->leftJoin('products as p', function ($join) {
                $join->on('v.product_id', '=', 'p.id');
            })
            ->leftJoin('views as vi', function ($join) {
                $join->on('p.id', '=', 'vi.product_id');
            })
            ->join('users as u', function ($join) use ($user) {
                $join->on('u.id', '=', 'vi.user_id')
                    ->where('u.id', '!=', $user->id);
            })
            ->join('views as vie', function ($join) {
                $join->on('vie.user_id', '=', 'u.id');
            })
            ->limit(10)
            ->get();

        return $products;
    }

    public function getMostViewed()
    {
        return $this->model
            ->withCount('views')
            ->where('views_count', '>', 0)
            ->orderBy('views_count')
            ->limit(10)
            ->get();
    }
}
