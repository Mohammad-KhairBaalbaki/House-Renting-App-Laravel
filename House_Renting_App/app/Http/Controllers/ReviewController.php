<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\House;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    //
    protected $reviewService;
    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }
    public function index()
    {
        $data = $this->reviewService->index();
        return $this->success($data, 'Reviews Retrieved Successfully', 200);
    }
    public function store(StoreReviewRequest $request)
    {
        $data = $this->reviewService->store($request->validated());
        if ($data === '1') {
            return $this->success(false, 'You Cannot Rate This House Because You have already rated It', 400);
        }
        if ($data === '2') {
            return $this->success(false, 'You Cannot Rate This House Because You Didnt Try It Yet', 400);
        }
        return $this->success($data, 'Review Created Successfully', 200);
    }

    public function checkIfCanRate(House $house)
    {
        $data1 = $this->reviewService->checkIfReserved($house, Auth::user());
        $data2 = $this->reviewService->checkIfReviewdOnce($house, Auth::user());
        if (!$data1) {
            return $this->success(false, 'You Cannot Rate This House Because You Didnt Reserve It Yet', 400);
        } elseif ($data2) {
            return $this->success(false, 'You Cannot Rate This House Because You have already rated It', 400);
        }
        return $this->success(true, 'you can rate this house ', 200);
    }


}
