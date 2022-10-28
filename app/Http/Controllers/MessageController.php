<?php

namespace App\Http\Controllers;

use App\Repositories\Cache\MessageCacheRepository;
use App\Filters\Filters;
use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{

    protected $repository;
    protected $message;

    public function setMessage(Request $request)
    {
        $message_id = $request->route('message_id');

        if ($message_id) {
            $this->message = $this->repository->getById($message_id);
        }
    }

    public function __construct(MessageCacheRepository $messageCache, Request $request)
    {
        $this->repository = $messageCache;
        $this->setMessage($request);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * @param  \App\Http\Requests\StoreMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request, $chat_id)
    {
        $created = $this->repository->create(['chat_id' => $chat_id, 'content' => $request->content]);

        return response()->json(compact('created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMessageRequest  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $deleted = $this->repository->delete($this->message);

        return response()->json(compact('deleted'));
    }
}
