<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuthException;
use JWTAuth;
use \App\Response\Response;

use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Aws\S3\Exception\S3Exception;
use Aws\Route53\Route53Client;

class PublishController extends Controller
{
    private $response;
    
    public function __construct()
    {
        $this->response = new Response();
    }
    /**
     * Display a listing of the resource.
     * @param $request se neceesário o token 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => array(
                'key' => env('AWS_KEY'),
                'secret'  => env('AWS_SECRET')
              )
        ]);
        $bucket = 'teste.gordinhosexy.com.br';

        $result = $s3->getObject(array(
            'Bucket' => $bucket,
            'Key'    => 'index.html'
        ));
        var_dump($result);die;

        // $container = $this->containerService->get();

        // $this->response->setType("S");
        // $this->response->setDataSet("containers", $container);
        // $this->response->setMessages("Dealers search successfully!");
        
        // return response()->json($this->response->toString(), 200);
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
        $bucket = 'teste.gordinhosexy.com.br';

        if($request->hasFile('index')) {
            $file = $request->index;
        }

        // creating a bucket
        $result = $s3->createBucket([
            'Bucket' => $bucket,
        ]);


        // setting bucket permissions
        $params = [
            'ACL' => 'public-read',
            'Bucket' => $bucket,
        ];
        $resp = $s3->putBucketAcl($params);


        // putting file in bucket
        $keyname = $file->getClientOriginalName();
        $filepath = $file->getPathname();
        $result = $s3->putObject(array(
            'Bucket' => $bucket,
            'Key'    => $keyname,
            'SourceFile' => $filepath,
            'Body'   => '',
            'ACL'    => 'public-read'
        ));


        // setting bucket permissions
        $params = [
            'ACL' => 'public-read',
            'Bucket' => $bucket,
        ];
        $resp = $s3->putBucketAcl($params);


        // setting bucket as website
        $params = [
            'Bucket' => $bucket,
            'WebsiteConfiguration' => [
                'ErrorDocument' => [
                    'Key' => 'index.html',
                ],
                'IndexDocument' => [
                    'Suffix' => 'index.html',
                ],
            ]
        ];
        $resp = $s3->putBucketWebsite($params);

        $this->response->setType("S");
        $this->response->setMessages("Site publish successfully!");
        
        return response()->json($this->response->toString(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {    }

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
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => array(
                'key' => env('AWS_KEY'),
                'secret'  => env('AWS_SECRET')
              )
        ]);
        $bucket = 'teste.gordinhosexy.com.br';

        if($request->hasFile('index')) {
            $file = $request->index;
        }

        // putting file in bucket
        $keyname = $file->getClientOriginalName();
        $filepath = $file->getPathname();
        $result = $s3->putObject(array(
            'Bucket' => $bucket,
            'Key'    => $keyname,
            'SourceFile' => $filepath,
            'Body'   => '',
            'ACL'    => 'public-read'
        ));

        $this->response->setType("S");
        $this->response->setMessages("Sucess, updated successfully!");

        return response()->json($this->response->toString(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }   
}
