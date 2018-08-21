@extends('layouts.header')

@section('main-content')

    <div class="d-flex justify-content-center align-items-center container-fluid ">

        <div class="row" style="margin-top: 100px; width: 50%">
            @if(session('error'))
                <div class="col-md-12">
                    <div class="alert alert-warning">
                        <strong>Warning!</strong> {!! session('error')  !!}
                    </div>
                </div>
            @endif
            <div class="col-md-12">
                {!! Form::open(['route' => 'storeAsinCode', 'method' => 'post']) !!}
                <div class="form-group {!! $errors->has('code') ? 'has-error' : '' !!}">
                    {!! Form::label('code', 'ASIN Code') !!}
                    {!! Form::text('code', null , ['class'=>'form-control','required'=>'true']) !!}
                    {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
                </div>

                {!! Form::submit('SUBMIT', ['class'=>'btn btn-success btn-block']) !!}

                {!! Form::close() !!}
            </div>
            @if(session('message'))
                <div class="col-md-12"  style="margin-top: 20px">
                    <div class="alert alert-success">
                        <strong>Success!</strong> {!! session('message')  !!}
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
@section('additional-script')

@endsection
