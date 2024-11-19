<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Database\Factories\PaymentFactory;
use Illuminate\Foundation\Http\FormRequest;

class WebHookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $payment = Payment::where('external_id', $this->data['id'])->exists();
        
        return $payment && $this->user_id == config('payment.mercadopago.user_id');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'action' => 'required|string|in:payment.updated',
            'id'=> 'required',
            'user_id'=> 'required',
            'data.id'=> 'required',
            'type'=> 'required|in:payment',
        ];
    }
}
