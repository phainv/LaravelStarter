@extends('layouts.app')

@section('content')
    <user :id="{{ $user->id }}"></user>
@endsection