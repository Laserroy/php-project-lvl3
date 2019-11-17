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
    </tr>
  </thead>
  <tbody>
    <tr>
    @foreach ($data as $domain)
    <th scope="row">{{$domain->id}}</th>
    <td>{{$domain->name}}</td>
    @endforeach
    </tr>
  </tbody>
</table>
@endsection

