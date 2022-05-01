<?php

namespace App\Http\Controllers;

use App\Cache\SearchCacheRepository;
use App\Formatter\AutosuggestFormatter;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    const LIMIT_SUGGESTS = 6;

    protected $searchInput;
    protected $repository;

    public function __construct(SearchCacheRepository $searchCache, SearchRequest $request)
    {
        $this->searchInput = $request->input('q');
        $this->repository = $searchCache;
    }

    public function autosuggest(AutosuggestFormatter $formatter)
    {
        /**
         * @var \App\Models\User $user;
         */
        $user = Auth::user();

        $historyUser =  $user ? $user->searchInSearches($this->repository, $this->searchInput, self::LIMIT_SUGGESTS) : new Collection();
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
    public function historySearch()
    {
        /**
         * @var \App\Models\User $user;
         */
        $user = Auth::user();
        $this->searchInputesUser = $user ? $user->searchInSearches($this->repository, $this->searchInput, self::LIMIT_SUGGESTS) : new Collection();

        return response()->json(['searches' => $this->searchInputesUser]);
    }

    public function mostSearched()
    {
        $mostSearched = $this->repository
            ->searchInMostSearchedWithLimit($this->searchInput, self::LIMIT_SUGGESTS);

        return response()->json(['searches' => $mostSearched]);
    }
}
