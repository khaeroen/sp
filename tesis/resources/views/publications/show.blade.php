@extends('layouts.app')

@section('style')
<style type="text/css">
    .show-thesis th {
        max-width: 100px;
    }
</style>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Publication: {{ $publication->title }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/publications') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        @if(isset($_GET['admin']))
                        <a href="{{ url('/publications/' . $publication->id . '/edit') }}" title="Edit Publication"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['publications', $publication->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Publication',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                        @endif
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless show-thesis">
                                <tbody>
                                    <tr><th> Title </th><td> {{ $publication->title }} </td></tr><tr><th> Author </th><td> {{ $publication->author }} </td></tr><tr><th> Supervisor </th><td> {{ $publication->supervisor }} </td></tr><tr><th> Author Email </th><td> {{ $publication->email }} </td></tr>
                                    <tr>
                                        <th>Abstract (English Version)</th>
                                        <td>{!! $publication->abstract_en !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Abstract (Bahasa Indonesia)</th>
                                        <td>{!! $publication->abstract_id !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Keywords</th>
                                        <td>{{ $publication->keyword }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cover</th>
                                        <td><a href="{{ url('/uploads/publications') . '/' . $publication->email . '_' . 'cover_publikasi.pdf' }}" target="_blank">{{ $publication->email }}_cover_publikasi.pdf</a></td>
                                    </tr>
                                    <tr>
                                        <th>Main File (Content)</th>
                                        <td><a href="{{ url('/uploads/publications') . '/' . $publication->email . '_' . 'file_publikasi.pdf' }}" target="_blank">{{ $publication->email }}_file_publikasi.pdf</a></td>
                                    </tr>
                                    <tr>
                                        <th>Lampiran</th>
                                        <td>
                                            <a href="{{ url('/uploads/publications') . '/' . $publication->email . '_' . 'lampiran_publikasi.pdf' }}" target="_blank">{{ $publication->email }}_lampiran_publikasi.pdf</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created/Submitted Time</th>
                                        <td>{{ $publication->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Update</th>
                                        <td>{{ $publication->updated_at }}</td>
                                    </tr>
                                    @if(isset($_GET['admin']))
                                    <tr>
                                        <th>Publish this thesis?</th>
                                        <td>
                                            @if($publication->publish != 1)
                                            <i class="fa fa-times-circle-o fa-2x" style="color: red;"></i>
                                            {!! Form::open([
                                                
                                                'url' => 'publish-publication',
                                                'method' => 'post'
                                            
                                            ]) !!}
                                            <input type="hidden" name="id" value="{{ $publication->id }}">
                                            <button class="btn btn-default btn-xs" type="submit">publish now!</button>
                                            {!! Form::close() !!}
                                            
                                            @else
                                            <i class="fa fa-check-circle-o fa-2x" style="color: green;"></i>
                                            {!! Form::open([
                                                
                                                'url' => 'publish-publication',
                                                'method' => 'post'
                                            
                                            ]) !!}
                                            <input type="hidden" name="id" value="{{ $publication->id }}">
                                            <button class="btn btn-default btn-xs" type="submit">unpublish</button>
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
