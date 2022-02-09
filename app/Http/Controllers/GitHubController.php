<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;

class GitHubController extends Controller
{
    /**
     * Show results of a GitHub repository search.
     *
     * @return \Illuminate\View\View
     */
    public function repositories()
    {
        $perPage = 30;
        $page = Paginator::resolveCurrentPage();

        $response = Http::accept('application/vnd.github.v3+json')->get('https://api.github.com/search/repositories', [
            'q' => 'php',
            'per_page' => $perPage,
            'page' => $page,
        ]);

        abort_if(!$response->ok(), $response->status(), $response->json('message'));

        $data = $response->json();

        $paginator = new LengthAwarePaginator($data['items'], $data['total_count'], $perPage, $page, ['path' => url()->current()]);

        return view('github.repositories', compact('paginator'));
    }
}
