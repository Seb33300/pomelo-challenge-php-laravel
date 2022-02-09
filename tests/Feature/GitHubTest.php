<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GitHubTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        Http::fake(function (Request $request) {
            $data = $request->data();
            $page = $data['page'] ?? 1;

            if ($page > 16) {
                return Http::response('Only the first 1000 search results are available', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return Http::response(file_get_contents(__DIR__.'/fixtures/github_repositories.json'), Response::HTTP_OK);
        });
    }

    /**
     * Test github repositories pagination
     *
     * @return void
     */
    public function test_github_repositories()
    {
        // Call without page number
        $response = $this->get('github/repositories');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertDontSee('github/repositories?page=0"', false);
        $response->assertSee('github/repositories?page=2"', false);

        // Call with a page number
        $response = $this->get('github/repositories?page=2');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('github/repositories?page=1"', false);
        $response->assertDontSee('github/repositories?page=2"', false);
        $response->assertSee('github/repositories?page=3"', false);

        // Call with invalid page number
        $response = $this->get('github/repositories?page=999');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
