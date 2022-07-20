<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\UrlTrait;
use Illuminate\Support\Facades\Http;
use Validator;

class OrderController extends Controller
{
    use UrlTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->session()->get('userId');
        $order = [];

        $pending = Http::get($this->url_dynamic() . 'order/read-by-user/' . $userId . '?status=pending');
        $pending = json_decode($pending->body());
        $pending = $pending->data;

        $settlement = Http::get($this->url_dynamic() . 'order/read-by-user/' . $userId . '?status=settlement');
        $settlement = json_decode($settlement->body());
        $settlement = $settlement->data;

        $ready = Http::get($this->url_dynamic() . 'order/read-by-user/' . $userId . '?status=ready');
        $ready = json_decode($ready->body());
        $ready = $ready->data;
        
        $history = Http::get($this->url_dynamic() . 'order/read-by-user/' . $userId . '?status=finish');
        $history = json_decode($history->body());
        $history = $history->data;

        $order = [
            'pending' => $pending,
            'settlement' => $settlement,
            'history' => $history,
            'ready' => $ready
        ];
        
        return view('pages.order', compact('order'));
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
    public function complete(Request $request)
    {
        $completeOrder = Http::post($this->url_dynamic(). 'order/complete', [
            'transactionId' => $request->transactionId
        ]);

        $completeOrder = json_decode($completeOrder->body());

        if($completeOrder->success) {
            return redirect()->route('order.index')->with('success', $completeOrder->message);
        } else {
            dd($completeOrder->json());
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
}
