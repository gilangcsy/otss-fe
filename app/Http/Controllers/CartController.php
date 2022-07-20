<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\UrlTrait;
use Illuminate\Support\Facades\Http;
use Validator;

class CartController extends Controller
{
    use UrlTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = $this->url_dynamic();
        $userId = $request->session()->get('userId');

        $cart = Http::get($this->url_dynamic() . 'cart/read-by-user/' . $userId);
        $cart = json_decode($cart->body());
        $totalPages = $cart->totalPages;
        $cart = $cart->data;

        return view('pages.cart', compact('cart', 'url'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = $request->session()->get('userId');

        $menuName = str_replace("-"," ", $request->menuName);
        $responseMenu = Http::get($this->url_dynamic() . 'menu?name=' . $menuName);
        $responseMenu = json_decode($responseMenu->body());
        $menuId = $responseMenu->data[0]->id;
        
        $cart = Http::post($this->url_dynamic() . 'cart', [
            'MenuId' => $menuId,
            'UserId' => $userId
        ]);
        $cart = json_decode($cart->body());

        if($cart->success) {
            return redirect()->back()->with('success', $cart->message);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkout(Request $request) {
        $result = json_decode($request->result_data, true);
        $userId = $request->session()->get('userId');

        $items = [];
        $countItems = count($request->quantity);
        for ($i=0; $i < $countItems; $i++) { 
            array_push($items, (object)[
                'MenuId' => $request->menuId[$i],
                'price' => $request->price[$i],
                'quantity' => $request->quantity[$i]
            ]);
        }

        $paymentType = $result['payment_type'];

        $response = Http::post($this->url_dynamic(). 'order/store', [
            'vaNumber' => $paymentType == 'qris' || $paymentType == 'gopay' ? '' : $result['va_numbers'][0]['va_number'],
            'paymentType' => $paymentType == 'qris' || $paymentType == 'gopay' ? $paymentType : $result['va_numbers'][0]['bank'],
            'status' => $result['transaction_status'],
            'transactionId' => $result['transaction_id'],
            'orderCode' => $result['order_id'],
            'amount' => $result['gross_amount'],
            'tax' => 10,
            'takeawayCharge' => 2,
            'serviceCharge' => 3,
            'UserId' => $userId,
            'items' => $items
        ]);

        $response = json_decode($response->body());

        if($response->success) {
            return redirect()->route('order.index');
        } else {
            dd($response->json());
        }
    }

    public function testing(Request $request) {
        dd($request->all());
    }
}
