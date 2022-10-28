<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\Product;
use App\Models\Subcategorie;
use App\Statuses\OrderStatus;
use App\Repositories\Interfaces\SubcategorieRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Xkairo\CacheRepositoryLaravel\Repositories\EloquentRepositories\BaseRepository;

class SubcategorieRepository extends BaseRepository implements SubcategorieRepositoryInterface
{

    protected $relations = [
        'products'
    ];

    public function __construct(Subcategorie $subcategorie)
    {
        parent::__construct($subcategorie);
    }

    public function paginatedProductsOfSubcategorie(int $paginate, Subcategorie $subcategorie, $sort_by, $filters)
    {
        $query = $subcategorie->products();

        $this->sortAndFilter($query, $sort_by, $filters);

        return $query->paginate($paginate);
    }

    public function withMostProductsSold()
    {
        return $this->model
            ->selectRaw('subcategories.*, COUNT(o.id) as sold_count')
            ->with(['products' => function ($query) {
                $query
                    ->withCount('orders')
                    ->whereRelation(
                        'orders',
                        fn ($query) => $query->where('status', OrderStatus::COMPLETED)
                    );
            }])
            ->join(
                'products AS p',
                fn ($join) =>
                $join->on('p.subcategorie_id', '=', 'subcategories.id')
            )
            ->join(
                'orders AS o',
                fn ($join) =>
                $join->on('p.id', '=', 'o.product_id')
            )
            ->join(
                'products AS pr',
                fn ($join) =>
                $join->on('p.subcategorie_id', '=', 'subcategories.id')
                    ->on('pr.id', '=', 'o.product_id')
                    ->on('o.status', '=', OrderStatus::COMPLETED)
            )
            ->orderBy('sold_count')
            ->groupBy('pr.id')
            ->limit(10)->get();


        $subcategories = $this->model
            // ->select(DB::raw('COUNT(produ'))
            ->join('products', function ($join) {
                $join->on('products.subcategorie_id', '=', 'subcategories.id');
            })
            ->join('orders', function ($join) {
                $join->on('products.id', '=', 'orders.product_id');
            })
            ->join('products', function ($join) {
                $join->on('orders.product_id', '=', 'products.id');
                $join->on('orders.status', '=', OrderStatus::COMPLETED);
            })
            ->where('second_products.subcategorie_id', 'subcategories.id')
            // ->with(['products' => function ($query) {
            //     $query
            //         ->withCount('orders')
            //         ->whereRelation(
            //             'orders',
            //             fn ($query) => $query->where('status', OrderStatus::COMPLETED)
            //         );
            // }])
            // ->whereRelation('products', function ($query) {
            //     $query
            //         ->withCount('orders')
            //         ->whereRelation(
            //             'orders',
            //             fn ($query) => $query->where('status', OrderStatus::COMPLETED)
            //         )
            //         ->where('orders_count', '>', 0);
            // })
            ->limit(10)
            ->get();

        return $subcategories;
    }
}
