<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Response\Response;
use \App\Client;
use Aws\S3\S3Client;

class MidiaController extends Controller
{
    private $response;
    private $client;

    public function __construct()
    {
        $this->response = new Response();
        $this->client = new Client();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userLogged = $this->pageService->getAuthUser($request);
        if($userLogged->user_type != "U")
        {
            $client = $this->client->getClientByUser($userLogged->id);
            $pages = $this->page->getPagesByIdUser($client->id);      
            
            $this->response->setDataSet("Page", $pages);
            $this->response->setType("S");
            $this->response->setMessages("Sucess!");
        }
        
        else 
        {
            $this->response->setType("N");
            $this->response->setMessages("Error!");
        }
        
        return response()->json($this->response->toString());
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
        // if ($request->get('client_id'))
        // {
        //     $clientID = $request->get('client_id');
        // }
        // else
        // {
        //     $userLogged = $this->pageService->getAuthUser($request);
        //     $client = $this->client->getClientByUser($userLogged->id);
        //     $clientID = $client->id;
        // }

        // if($clientID != "" || $userLogged->user_type != "D")
        // {
        //     if($client)
        //     {

                $bucket = 'elasticbeanstalk-us-east-1-761736504383';
                $keyname = '*** Your Object Key ***';

                // $filepath should be absolute path to a file on disk						
                $filepath = getcwd().'/index.php';

                // Instantiate the client.
                $s3 = new S3Client([
                    'version' => 'latest',
                    'region'  => 'us-east-1'
                ]);

                $result = $s3->listBuckets();
                foreach ($result['Buckets'] as $bucket) {
                    echo $bucket['Name'] . "\n";
                }
                $array = $result->toArray();
                var_dump($array);die;


                // Upload a file.
                $result = $s3->putObject(array(
                    'Bucket' => $bucket,
                    'Key' => $keyname,
                    'SourceFile' => $filepath,
                    'ContentType' => 'text/plain',
                    'ACL' => 'public-read',
                    'StorageClass' => 'REDUCED_REDUNDANCY',
                    'Metadata' => array(    
                        'param1' => 'value 1',
                        'param2' => 'value 2'
                    )
                ));

                echo $result['ObjectURL'];



                $this->response->setDataSet("Page", $pageCreate);
                $this->response->setType("S");
        //         $this->response->setMessages("Page created!");
        //     }
        //     else 
        //     {
        //         $this->response->setType("N");
        //         $this->response->setMessages("You dont't have permission to create a page!");
        //     }
        // }
        // else 
        // {
        //     $this->response->setType("N");
        //     $this->response->setMessages("You dont't have permission to create a page!");
        // }

        return response()->json($this->response->toString());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $userLogged = $this->pageService->getAuthUser($request);
        if($userLogged->user_type != "U")
        {
            $pages = $this->page->find($id);

            if(!$pages)
            {
                $this->response->settype("N");
                $this->response->setMessages("Record not find!");
            }
            else
            {
                $this->response->settype("S");
                $this->response->setMessages("Record not find!");
                $this->response->setDataSet("page", $pages);

            }
        }
        else 
        {
            $this->response->settype("N");
            $this->response->setMessages("User don't have permission!");
        }
        
        return response()->json($this->response->toString());
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
