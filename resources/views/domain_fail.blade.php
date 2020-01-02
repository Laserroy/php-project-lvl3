@extends('layouts.app')

@section('navbar')
    @parent
@endsection

@section('primary_content')
    <p>An error occured while processing {{ $domain->name }}</p>
@endsection