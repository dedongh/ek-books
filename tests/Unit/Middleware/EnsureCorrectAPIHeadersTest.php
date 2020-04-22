<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\EnsureCorrectAPIHeaders;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class EnsureCorrectAPIHeadersTest extends TestCase
{
    /**
     * @test
     *
     */
    public function
    it_aborts_request_if_accept_header_does_not_adhere_to_json_api_spec()
    {
        $request = Request::create('/test', 'GET');
        $middleware = new EnsureCorrectAPIHeaders();

        $response = $middleware->handle($request, function ($request) {
            $this->fail('Did not abort request because of invalid Accept header');
        });
        $this->assertEquals(406, $response->status());
    }

    /**
     * @test
     *
     */
    public function
    it_accepts_request_if_accept_header_adheres_to_json_api_spec()
    {
        $request = Request::create('/test', 'GET');
        $request->headers->set('accept', 'application/json');
        $middleware = new EnsureCorrectAPIHeaders;
        /** @var Response $response */
        $response = $middleware->handle($request, function ($request) {
            return new Response();
        });
        $this->assertEquals(200, $response->status());
    }

    /**
     * @test
     *
     */
    public function
    it_aborts_post_request_if_content_type_header_does_not_adhere_to_json_api_sp
    ()
    {
        $request = Request::create('/test', 'POST');
        $request->headers->set('accept', 'application/json');
        $middleware = new EnsureCorrectAPIHeaders;
        /** @var Response $response */
        $response = $middleware->handle($request, function ($request) {
            $this->fail('Did not abort request because of invalid Content-Type header');
        });
        $this->assertEquals(415, $response->status());
    }

    /**
     * @test
     *
     */
    public function
    it_aborts_patch_request_if_content_type_header_does_not_adhere_to_json_api_s
    ()
    {
        $request = Request::create('/test', 'PATCH');
        $request->headers->set('accept', 'application/json');
        $middleware = new EnsureCorrectAPIHeaders;
        /** @var Response $response */
        $response = $middleware->handle($request, function ($request) {
            $this->fail('Did not abort request because of invalid Content-Type header');
        });
        $this->assertEquals(415, $response->status());
    }

    /**
     * @test
     */
    public function
    it_accepts_post_request_if_content_type_header_adheres_to_json_api_spec
    (){
        $request = Request::create('/test', 'POST');
        $request->headers->set('accept', 'application/json');
        $request->headers->set('content-type', 'application/json');
        $middleware = new EnsureCorrectAPIHeaders;
        /** @var Response $response */
        $response = $middleware->handle($request, function($request){
            return new Response();
        });
        $this->assertEquals(200, $response->status());
    }
    /**
     * @test
     *
     */
    public function
    it_accepts_patch_request_if_content_type_header_adheres_to_json_api_spec
    (){
        $request = Request::create('/test', 'PATCH');
        $request->headers->set('accept', 'application/json');
        $request->headers->set('content-type', 'application/json');
        $middleware = new EnsureCorrectAPIHeaders;
        /** @var Response $response */
        $response = $middleware->handle($request, function($request){
            return new Response();
        });
        $this->assertEquals(200, $response->status());
    }

    /**
     * @test
     */
    public function
    it_ensures_that_a_content_type_header_adhering_to_json_api_spec_is_on_resp
    ()
    {
        $request = Request::create('/test', 'GET');
        $request->headers->set('accept', 'application/json');
        $request->headers->set('content-type', 'application/json
');
        $middleware = new EnsureCorrectAPIHeaders;
        /** @var Response $response */
        $response = $middleware->handle($request, function($request){
            return new Response();
        });
        $this->assertEquals(200, $response->status());
        $this->assertEquals('application/json', $response->
        headers->get('content-type'));
    }
}
