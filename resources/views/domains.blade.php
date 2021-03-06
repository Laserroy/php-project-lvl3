@extends('layouts.app')

@section('navbar')
    @parent
@endsection

@section('primary_content')
<table class="table">
    <thead>
        <tr>
            <th scope="col">Name</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($domains as $domain)
        <tr>
            <td><a href="{{ $link = route('domains.show', ['id' => $domain->id]) }}">{{$domain->name}}</a> </td>
        </tr>
    @endforeach
    </tbody>
</table>

<nav aria-label="Page navigation">
    <ul class="pagination">
        <li class="page-item"><a class="page-link" href="{{$domains->previousPageUrl()}}">Previous</a></li>
        <li class="page-item"><a class="page-link">{{$domains->currentPage()}}</a></li>
        <li class="page-item"><a class="page-link" href="{{$domains->nextPageUrl()}}">Next</a></li>
    </ul>
</nav>
@endsection
