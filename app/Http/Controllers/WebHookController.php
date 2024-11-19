<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebHookRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class WebHookController extends Controller
{
    public function index(WebHookRequest $request, PaymentService $paymentService)
    {
        $paymentService->update($request->data['id']);
    }
}
