<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Response\Response;
use App\Client;
use App\Midia;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Aws\S3\Exception\S3Exception;

class MidiaController extends Controller
{
    private $response;
    private $client;
    private $midia;

    public function __construct()
    {
        $this->response = new Response();
        $this->client = new Client();
        $this->midia = new Midia();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
    }

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
    {
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => array(
                'key' => env('AWS_KEY'),
                'secret'  => env('AWS_SECRET')
              )
        ]);

        $bucket = 'midia-site2go';
        $keyname = '1/robots.txt2';
        $filepath = getcwd().'/robots.txt';

        try {
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname,
                'SourceFile' => $filepath,
                'Body'   => '',
                'ACL'    => 'public-read'
            ));

            $returnMidia = $this->midia->create([
                'client_id' => 2,
                'url' => $result['ObjectURL'],
                'keyname' => $keyname
            ]);
    
            $this->response->setType("S");
            $this->response->setDataSet("midia", $returnMidia);
            $this->response->setMessages("Created midia successfully!");

        } catch (S3Exception $e) {
            $this->response->setType("N");
            $this->response->setMessages("Failed to create a midia!");
            return response()->json($this->response->toString(), 200);
        }

        return response()->json($this->response->toString(), 200);
    }

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
}
