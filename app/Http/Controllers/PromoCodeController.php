<?php

namespace App\Http\Controllers;

use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the promo codes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $promoCodes = PromoCode::all();
        return response()->json(['promo_codes' => $promoCodes], 200);
    }

    /**
     * Store a newly created promo code in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:promo_codes',
            'percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $promoCode = PromoCode::create($validated);

        return response()->json(['message' => 'Promo code created successfully!', 'promo_code' => $promoCode], 201);
    }

    /**
     * Display the specified promo code.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(PromoCode $promoCode)
    {
        return response()->json(['promo_code' => $promoCode], 200);
    }

    /**
     * Update the specified promo code in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, PromoCode $promoCode)
    {
        $validated = $request->validate([
            'code' => 'required|unique:promo_codes,code,' . $promoCode->id,
            'percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $promoCode->update($validated);

        return response()->json(['message' => 'Promo code updated successfully!', 'promo_code' => $promoCode], 200);
    }

    /**
     * Remove the specified promo code from storage.
     *
     * @param  \App\Models\PromoCode  $promoCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();

        return response()->json(['message' => 'Promo code deleted successfully!'], 200);
    }
}
