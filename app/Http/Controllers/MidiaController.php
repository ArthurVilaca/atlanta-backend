<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Response\Response;
use App\Client;
use App\Midia;
use \App\Service\UserService;

use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Aws\S3\Exception\S3Exception;

class MidiaController extends Controller
{
    private $response;
    private $client;
    private $midia;
    private $userService;

    public function __construct()
    {
        $this->response = new Response();
        $this->client = new Client();
        $this->midia = new Midia();
        $this->userService = new UserService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('client_id')) {
            $midias = $this->midia->getByClientId($request->get('client_id'));
        } else {
            $user = $this->userService->getAuthUser($request);
            $client = $this->client->getClientByUser($user->id);
            $midias = $this->midia->getByClientId($client->id);
        }

        $this->response->setType("S");
        $this->response->setDataSet("midias", $midias);
        $this->response->setMessages("Midias search successfully!");
        return response()->json($this->response->toString(), 200);
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

        if ($request->get('client_id')) {
            $client = $this->client->find($request->get('client_id'));
        } else {
            $user = $this->userService->getAuthUser($request);
            $client = $this->client->getClientByUser($user->id);
        }


        if($request->hasFile('midia')) {
            $file = $request->midia;
        } else {
            $this->response->setType("N");
            $this->response->setMessages("Failed to create a midia!");
            return response()->json($this->response->toString(), 200);
        }

        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => array(
                'key' => env('AWS_KEY'),
                'secret'  => env('AWS_SECRET')
              )
        ]);

        $bucket = 'midia-site2go';
        $keyname = $client->id . '/' . $file->getClientOriginalName();
        $filepath = $file->getPathname();

        try {
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname,
                'SourceFile' => $filepath,
                'Body'   => '',
                'ACL'    => 'public-read'
            ));

            $returnMidia = $this->midia->create([
                'client_id' => $client->id,
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
