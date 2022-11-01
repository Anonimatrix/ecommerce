<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Chat $chat)
    {
        $chateable = $chat->chateable;
        if ($chateable instanceof Complaint) {
            return in_array(
                $user->id,
                [
                    $chateable->intermediary->id,
                    $chateable->order->buyer->id,
                    $chateable->order->product->user->id
                ]
            ) || $user->can('view foreign chat');
        }

        if ($chateable instanceof Order) {
            return in_array(
                $user->id,
                [
                    $chateable->complaint->intermediary->id,
                    $chateable->buyer->id,
                    $chateable->product->user->id
                ]
            ) || $user->can('view foreign chat');
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Chat $chat)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Chat $chat)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Chat $chat)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Chat $chat)
    {
        //
    }
}
