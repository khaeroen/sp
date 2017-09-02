@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Publikasi Ilmiah: <strong>"{{ $publication->title }}"</strong></div>
                    <div class="panel-body">
                        <a href="{{ url('/publications') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {!! Form::model($publication, [
                            'method' => 'PATCH',
                            'url' => ['/publications', $publication->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                        @include ('publications.form', ['submitButtonText' => 'Update'])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
