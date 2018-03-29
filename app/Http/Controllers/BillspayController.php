<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuthException;
use JWTAuth;
use App\Billspay;
use App\Dealer;
use \App\Response\Response;

class BillspayController extends Controller
{
    private $dealer;
    private $response;

    public function __construct()
    {
        $this->billspay = new Billspay();
        $this->dealer = new Dealer();
        $this->response = new Response();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $billspays = $this->billspay->get();
        foreach ($billspays as $key => $value) {
            $value->dealer = $this->dealer->find($value->id);
        }

        $this->response->setType("S");
        $this->response->setDataSet("billspays", $billspays);
        $this->response->setMessages("Billspay search successfully!");
        return response()->json($this->response->toString(), 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Billspay  $billspay
     * @return \Illuminate\Http\Response
     */
    public function show(Billspay $billspay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Billspay  $billspay
     * @return \Illuminate\Http\Response
     */
    public function edit(Billspay $billspay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Billspay  $billspay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Billspay $billspay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Billspay  $billspay
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billspay $billspay)
    {
        //
    }
}
