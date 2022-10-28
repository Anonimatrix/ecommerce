<?php

namespace App\Http\Controllers;

use App\Repositories\Cache\ViewCacheRepository;
use App\Models\View;
use App\Http\Requests\StoreViewRequest;
use App\Http\Requests\UpdateViewRequest;
use App\Repositories\Cache\UserCacheRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
{
    protected $repository;
    protected $view;

    public function setView(Request $request)
    {
        $view_id = $request->route('view_id');

        if ($view_id) {
            $this->view = $this->repository->getById($view_id);
        }
    }

    public function __construct(ViewCacheRepository $viewCache, Request $request)
    {
        $this->repository = $viewCache;
        $this->setView($request);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreViewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreViewRequest $request, UserCacheRepository $userRepository)
    {
        $user = $userRepository->authenticated()->id;

        $this->repository->create(
            [
                'user_id' => $user,
                'product_id' => $request->input('product_id')
            ]
        );

        return response()->json(['status' => 'success', 'message' => 'created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\View  $view
     * @return \Illuminate\Http\Response
     */
    public function show(View $view)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\View  $view
     * @return \Illuminate\Http\Response
     */
    public function edit(View $view)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateViewRequest  $request
     * @param  \App\Models\View  $view
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateViewRequest $request, View $view)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\View  $view
     * @return \Illuminate\Http\Response
     */
    public function destroy(View $view)
    {
        //
    }
}
