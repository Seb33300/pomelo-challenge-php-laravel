@extends('layout')

@section('content')
    <h1 class="flex justify-center pt-8 sm:justify-start sm:pt-0 text-gray-600 dark:text-gray-400">
        GitHub php repositories
    </h1>

    <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <table class="p-6 text-gray-600 dark:text-gray-400">
            <thead>
            <tr>
                <th>Full Name</th>
                <th>Description</th>
                <th>Owner</th>
                <th>License</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($paginator as $item)
            <tr>
                <td><a href="{{ $item['html_url'] }}" target="_blank">{{ $item['full_name'] }}</a></td>
                <td>{{ Str::limit($item['description'], 100, '...') }}</td>
                <td>
                    @if (isset($item['owner']))
                    <a href="{{ $item['owner']['html_url'] }}" target="_blank">{{ $item['owner']['login'] }}</a>
                    @endif
                </td>
                <td>{{ $item['license']['name'] ?? '' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">(Rendering of pagination is broken because tailwind is missing, but it's working...)</div>
    <div class="mt-4">{{ $paginator->links() }}</div>
@endsection
