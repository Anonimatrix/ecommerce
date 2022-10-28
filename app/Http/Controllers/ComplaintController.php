<?php

namespace App\Http\Controllers;

use App\Repositories\Cache\ComplaintCacheRepository;
use App\Repositories\Cache\OrderCacheRepository;
use App\Repositories\Cache\PaymentCacheRepository;
use App\Statuses\ComplaintStatus;
use App\Filters\Filters;
use App\Models\Complaint;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Models\Payment;
use App\Statuses\OrderStatus;
use App\Statuses\PaymentStatus;
use App\Traits\Http\HasPhotos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ComplaintController extends Controller
{
    use HasPhotos;

    protected $repository;
    protected $paymentRepository;
    protected $orderRepository;
    protected $complaint;

    public function setComplaint(Request $request)
    {
        $complaint_id = $request->route('complaint_id');

        if ($complaint_id) {
            $this->complaint = $this->repository->getById($complaint_id);
        }
    }

    public function __construct(ComplaintCacheRepository $complaintCache, OrderCacheRepository $orderCache, PaymentCacheRepository $paymentCache, Request $request)
    {
        $this->repository = $complaintCache;
        $this->paymentRepository = $paymentCache;
        $this->orderRepository = $orderCache;
        $this->setComplaint($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagination = $this->repository->paginate(15, ['id', 'ASC'], [Filters::only_dont_taken()]);

        return Inertia::render('Complaints/Index', compact('pagination'));
    }

    public function take()
    {
        $this->repository->update(['status' => ComplaintStatus::TAKEN], $this->complaint);

        return response()->json(['status' => 'updated'], 201);
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
     * @param  \App\Http\Requests\StoreComplaintRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreComplaintRequest $request)
    {
        //TODO policy user is buyer and order is completed
        $data = [
            'order_id' => $request->input('order_id'),
            'reason' => $request->input('reason'),
            'status' => ComplaintStatus::STARTED
        ];

        $created = $this->repository->create($data);

        if ($created) {
            $this->uploadPhotos($created, $request->photos);
        }

        return redirect()->route('complaints.show', $created->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $complaint = $this->complaint;

        return Inertia::render('Complaints/Show', compact('complaint'));
    }

    public function cancel()
    {
        $complaint = $this->complaint;

        $this->repository->update(['status' => ComplaintStatus::CANCELED], $complaint);

        return response()->json(['status' => 'canceled']);
    }

    public function refund()
    {
        $complaint = $this->complaint;

        $this->paymentRepository->update(['status' => PaymentStatus::REFUNDED], $complaint->order->payment);

        $this->orderRepository->update(['status' => OrderStatus::CANCELED], $complaint->order);

        $this->repository->update(['status' => ComplaintStatus::SOLVED], $complaint);

        return response()->json(['status' => 'refunded']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function edit(Complaint $complaint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateComplaintRequest  $request
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateComplaintRequest $request, Complaint $complaint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complaint $complaint)
    {
        //
    }
}
