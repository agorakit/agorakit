@extends('errors::minimal')

@section('title', trans('Too Many Requests'))
@section('code', '429')
@section('message', trans('Too Many Requests'))
