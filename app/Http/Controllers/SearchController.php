<?php

namespace App\Http\Controllers;

use App\Repositories\Cache\SearchCacheRepository;
use App\Formatter\AutosuggestFormatter;
use App\Http\Requests\SearchRequest;
use App\Models\User;
use App\Repositories\Cache\UserCacheRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SearchController extends Controller
{
    const LIMIT_SUGGESTS = 6;
    const LIMIT_HISTORY = 10;

    protected $searchInput;
    protected $repository;
    protected $userRepository;

    public function __construct(SearchCacheRepository $searchCache, Request $request, UserCacheRepository $userRepository)
    {
        $this->searchInput = $request->input('q');
        $this->userRepository = $userRepository;
        $this->repository = $searchCache;
    }

    public function autosuggest(AutosuggestFormatter $formatter, SearchRequest $request)
    {
        /**
         * @var \App\Models\User $user;
         */
        $user = $this->userRepository->authenticated();

        $historyUser =  $user ? $user->searchInHistory($this->repository, $this->searchInput, self::LIMIT_SUGGESTS) : new Collection();
        $mostSearched = $this->repository
            ->searchInMostSearchedWithLimit($this->searchInput, self::LIMIT_SUGGESTS - $historyUser->count());

        $historyUserFormatted = $formatter->historyUserFormat($historyUser);
        $mostSearchedFormatted = $formatter->mostSearchedFormat($mostSearched);

        $suggestions = $historyUserFormatted->merge($mostSearchedFormatted);

        return response()->json(['suggestions' => $suggestions]);
    }

    /**
     *  
     *
     * @return \Illuminate\Http\Response
     */
    public function historySearch(SearchRequest $request)
    {
        /**
         * @var \App\Models\User $user;
         */
        $user = $this->userRepository->authenticated();
        $this->searchInputesUser = $user ? $user->searchInHistory($this->repository, $this->searchInput, self::LIMIT_SUGGESTS) : new Collection();

        return response()->json(['searches' => $this->searchInputesUser]);
    }

    public function mostSearched()
    {
        $mostSearched = $this->repository
            ->searchInMostSearchedWithLimit($this->searchInput, self::LIMIT_SUGGESTS);

        return response()->json(['searches' => $mostSearched]);
    }

    public function historyPage(SearchRequest $request)
    {
        /**
         * @var \App\Models\User $user;
         */

        $user = $this->userRepository->authenticated();
        $history = $user->searchInHistory($this->repository, $this->searchInput, self::LIMIT_HISTORY);

        return Inertia::render('Searches/History', ['products' => $history]);
    }
}
