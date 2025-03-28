<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_info' => 'required|string|max:255',
            'vehicle_plate' => [
                'required', 
                'string', 
                'max:20', 
                Rule::unique('vehicles', 'vehicle_plate')
                    ->ignore(Auth::user()->vehicle->id ?? null, 'id')
            ],
            'vehicle_color' => 'nullable|string|max:50',
        ];
    }
}
