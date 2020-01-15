@extends('layouts.app')

@section('navbar')
    @parent
@endsection

@section('primary_content')
    <table class="table table-striped">
        <tbody>
            <tr>
                <th scope="row">Name</th>
                <td>{{$domain->name}}</td>
            </tr>
            <tr>
                <th scope="row">Status code</th>
                <td>{{$domain->status}}</td>
            </tr>
            <tr>
                <th scope="row">Content length</th>
                <td>{{$domain->content_length}}</td>
            </tr>
            <tr>
                <th scope="row">First header</th>
                <td>{{$domain->header1}}</td>
            </tr>
            <tr>
                <th scope="row">Description</th>
                <td>{{$domain->description}}</td>
            </tr>
            <tr>
                <th scope="row">Keywords</th>
                <td>{{$domain->keywords}}</td>
            </tr>
        </tbody>
    </table>
@endsection

