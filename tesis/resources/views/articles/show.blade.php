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
                    <div class="panel-heading">Thesis: {{ $article->title }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/articles') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        @if(isset($_GET['admin']))
                        <a href="{{ url('/articles/' . $article->id . '/edit') }}" title="Edit Article"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        {!! Form::open([
                            'method'=>'DELETE',
                            'url' => ['articles', $article->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete Article',
                                    'onclick'=>'return confirm("Confirm delete?")'
                            ))!!}
                        {!! Form::close() !!}
                        
                        @endif

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table table-borderless show-thesis">
                                <tbody>
                                    <tr><th> Title </th><td> {{ $article->title }} </td></tr><tr><th> Author </th><td> {{ $article->author }} </td></tr><tr><th> Supervisor </th><td> {{ $article->supervisor }} </td></tr><tr><th> Author Email </th><td> {{ $article->email }} </td></tr>
                                    <tr>
                                        <th>Abstract (English Version)</th>
                                        <td>{!! $article->abstract_en !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Abstract (Bahasa Indonesia)</th>
                                        <td>{!! $article->abstract_id !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Keywords</th>
                                        <td>{{ $article->keyword }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cover</th>
                                        <td><a href="{{ url('/uploads') . '/' . $article->email . '_' . 'cover.pdf' }}" target="_blank">{{ $article->email }}_cover.pdf</a></td>
                                    </tr>
                                    <tr>
                                        <th>Bab 1</th>
                                        <td><a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_1.pdf' }}" target="_blank">{{ $article->email }}_bab_1.pdf</a></td>
                                    </tr>
                                    <tr>
                                        <th>Bab 2</th>
                                        <td>
                                            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_2.pdf' }}" target="_blank">{{ $article->email }}_bab_2.pdf</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bab 3</th>
                                        <td>
                                            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_3.pdf' }}" target="_blank">{{ $article->email }}_bab_3.pdf</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bab 4</th>
                                        <td>
                                            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_4.pdf' }}" target="_blank">{{ $article->email }}_bab_4.pdf</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bab 5</th>
                                        <td>
                                            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_5.pdf' }}" target="_blank">{{ $article->email }}_bab_5.pdf</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Bab 6</th>
                                        <td>
                                            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_6.pdf' }}" target="_blank">{{ $article->email }}_bab_6.pdf</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Lampiran</th>
                                        <td>
                                            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'lampiran.pdf' }}" target="_blank">{{ $article->email }}_lampiran.pdf</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created/Submitted Time</th>
                                        <td>{{ $article->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Update</th>
                                        <td>{{ $article->updated_at }}</td>
                                    </tr>
                                    @if(isset($_GET['admin']))
                                    <tr>
                                        <th>Publish this thesis?</th>
                                        <td>
                                            @if($article->publish != 1)
                                            <i class="fa fa-times-circle-o fa-2x" style="color: red;"></i>
                                            {!! Form::open([
                                                
                                                'url' => 'publish',
                                                'method' => 'post'
                                            
                                            ]) !!}
                                            <input type="hidden" name="id" value="{{ $article->id }}">
                                            <button class="btn btn-default btn-xs" type="submit">publish now!</button>
                                            {!! Form::close() !!}
                                            
                                            @else
                                            <i class="fa fa-check-circle-o fa-2x" style="color: green;"></i>
                                            {!! Form::open([
                                                
                                                'url' => 'publish',
                                                'method' => 'post'
                                            
                                            ]) !!}
                                            <input type="hidden" name="id" value="{{ $article->id }}">
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
