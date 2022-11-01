<?php

namespace App\Http\Controllers;

use App\Repositories\Cache\ChatCacheRepository;
use App\Repositories\Cache\MessageCacheRepository;
use App\Models\Chat;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Models\Message;
use App\Repositories\Cache\UserCacheRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
    protected $repository;
    protected $chat;

    public function setChat(Request $request)
    {
        $chat_id = $request->route('chat_id');

        if ($chat_id) {
            $this->chat = $this->repository->getById($chat_id);
        }
    }

    public function __construct(ChatCacheRepository $chatCache, MessageCacheRepository $messageCache, Request $request)
    {
        $this->repository = $chatCache;
        $this->messageRepository = $messageCache;
        $this->setChat($request);
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
     * @param  \App\Http\Requests\StoreChatRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChatRequest $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(UserCacheRepository $userRepository)
    {
        $chat = $this->chat;

        $this->authorize('view', $chat);

        $user = $userRepository->authenticated();

        if ($user && $user->can('view trashed messages')) {
            $chat->load(['messages' => fn ($q) => $q->withTrashed()]);
        }

        return Inertia::render('Chats/Show', compact('chat'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChatRequest  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChatRequest $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
