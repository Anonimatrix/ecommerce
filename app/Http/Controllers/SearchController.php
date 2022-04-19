<?php

namespace App\Http\Controllers;

use App\Formatter\AutosuggestFormatter;
use App\Http\Requests\SearchRequest;
use App\Models\Search;
use App\Http\Requests\StoreSearchRequest;
use App\Http\Requests\UpdateSearchRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    const LIMIT_SEARCHES = 6;

    protected $searchInput;

    public function __construct(SearchRequest $request)
    {
        $this->searchInput = $request->input('q');
    }

    public function autosuggest(AutosuggestFormatter $formatter)
    {
        $historyUser = Auth::user() ? Auth::user()->searchInSearches($this->searchInput, self::LIMIT_SEARCHES) : new Collection();
        $mostSearched = Search::where('content', 'LIKE', "%$this->searchInput%")->whereNotIn('content', $historyUser->pluck('content'))->orderBy(DB::raw('COUNT(*)'), 'desc')->groupBy('content')->distinct()->limit(self::LIMIT_SEARCHES - $historyUser->count())->get();

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
        $this->searchInputesUser = Auth::user() ? Auth::user()->searchInSearches($this->searchInput, self::LIMIT_SEARCHES) : new Collection();

        return response()->json(['searches' => $this->searchInputesUser]);
    }

    public function mostSearched()
    {
        $mostSearched = Search::where('content', 'LIKE', "%$this->searchInput%")->orderBy(DB::raw('COUNT(*)'), 'desc')->groupBy('content')->distinct()->limit(self::LIMIT_SEARCHES)->get();

        return response()->json(['searches' => $mostSearched]);
    }
}
