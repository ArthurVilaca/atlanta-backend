<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Response\Response;
use \App\Service\UserService;

use SendGrid;

class EmailsController extends Controller
{
    private $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {    }

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

    /**
     * Send a email to a client
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function send($content, $to, $subject)
    {
        $from_email = new SendGrid\Email("Httplay", "vilaca.arthur.f@gmail.com");
        $to_email = new SendGrid\Email("Cliente", $to);
        $content_email = new SendGrid\Content("text/html", $content);

        $mail = new SendGrid\Mail($from_email, $subject, $to_email, $content_email);
        $apiKey = env('SENDGRID_APIKEY');
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);
    }
}
