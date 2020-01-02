@extends('layouts.app')

@section('navbar')
    @parent
@endsection

@section('primary_content')
    <table class="table">
        <thead>
            <tr>
                <th scope="col">id</th>
                <th scope="col">name</th>
                <th scope="col">status</th>
                <th scope="col">content length</th>
                <th scope="col">header</th>
                <th scope="col">description</th>
                <th scope="col">keywords</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">{{$domain->id}}</th>
                <td>{{$domain->name}}</td>
                <td>{{$domain->status}}</td>
                <td>{{$domain->content_length}}</td>
                <td>{{$domain->header1}}</td>
                <td>{{$domain->description}}</td>
                <td>{{$domain->keywords}}</td>
            </tr>
        </tbody>
    </table>
@endsection

