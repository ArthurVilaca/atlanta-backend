<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuthException;
use JWTAuth;
use App\Billsreceive;
use App\Client;
use \App\Response\Response;

class BillsreceiveController extends Controller
{
    private $client;
    private $response;

    public function __construct()
    {
        $this->billsreceive = new Billsreceive();
        $this->client = new Client();
        $this->response = new Response();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $billsreceives = $this->billsreceive->get();
        foreach ($billsreceives as $key => $value) {
            $value->client = $this->client->find($value->id);
        }

        $this->response->setType("S");
        $this->response->setDataSet("billsreceives", $billsreceives);
        $this->response->setMessages("Billsreceive search successfully!");
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
     * @param  \App\Billsreceive  $billsreceive
     * @return \Illuminate\Http\Response
     */
    public function show(Billsreceive $billsreceive)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Billsreceive  $billsreceive
     * @return \Illuminate\Http\Response
     */
    public function edit(Billsreceive $billsreceive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Billsreceive  $billsreceive
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Billsreceive $billsreceive)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Billsreceive  $billsreceive
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billsreceive $billsreceive)
    {
        //
    }
}
