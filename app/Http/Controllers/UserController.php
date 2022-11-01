<?php

namespace App\Http\Controllers;

use App\Services\Billing\Contracts\PaymentGatewayInterface;
use App\Repositories\Cache\OrderCacheRepository;
use App\Repositories\Cache\RoleCacheRepository;
use App\Repositories\Cache\UserCacheRepository;
use App\Filters\Filters;
use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\OptionalSearchRequest;
use App\Statuses\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $repository;
    protected $user;
    protected $orderCache;
    protected $authenticated;

    public function setAuthenticated()
    {
        $this->authenticated = $this->repository->authenticated();
    }

    public function setUser(Request $request)
    {
        $user_id = $request->route('user_id');

        if ($user_id) $this->user = $this->repository->getById($user_id);
    }

    public function __construct(UserCacheRepository $userCache, OrderCacheRepository $orderCache, RoleCacheRepository $roleCache, Request $request)
    {
        $this->repository = $userCache;
        $this->roleRepository = $roleCache;
        $this->orderCache = $orderCache;
        $this->setAuthenticated();
        $this->setUser($request);
    }

    public function getMoney()
    {
        $money = $this->authenticated->money;
        $pending_money = $this->authenticated->pending_money;

        return response()->json(compact('money', 'pending_money'));
    }

    public function withdraw(PaymentGatewayInterface $paymentGateway)
    {
        dd($paymentGateway->withdraw());
        return redirect($paymentGateway->withdraw());
    }

    public function index(OptionalSearchRequest $request)
    {
        $searchValue = $request->input('q') ?? '';

        $pagination = $this->repository->paginate(20, ['id', 'DESC'], [Filters::search_by_name_email_last_name_or_dni($searchValue)]);

        return Inertia::render('Users/Index', compact('pagination'));
    }

    public function assignRoles(AssignRoleRequest $request)
    {
        $roles = $request->roles;

        foreach ($roles as $role) {
            if ($role['assigned']) $this->user->assignRole($role['name']);
            else $this->user->removeRole($role['name']);
        }

        return response()->json([], 200);
    }

    public function assignRolesPage()
    {
        $roles = $this->roleRepository->all();
        $user = $this->user;

        return Inertia::render('Users/AssignRoles', compact('roles', 'user'));
    }
}
