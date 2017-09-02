@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Upload Publikasi Ilmiah</div>
                    <div class="panel-body">
                        <!-- <a href="{{ url('/publications/create') }}" class="btn btn-success btn-sm" title="Add New Publication">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a> -->

                        {!! Form::open(['method' => 'GET', 'url' => '/publications', 'class' => '', 'role' => 'search'])  !!}
                        <div class="row">
                            <div class="col-md-4 col-sm-5 col-xs-6">
                                <input type="text" name="search" placeholder="Search..." class="form-control" value="{{ isset($keyword) ? $keyword : '' }}">    
                            </div>
                            <div class="col-md-3 col-sm-4 col-xs-4">
                                <select name="searchBy" class="form-control">
                                    <option value="title" {{ $searchBy == 'title' ? 'selected' : '' }}>Title</option>
                                    <option value="author" {{ $searchBy == 'author' ? 'selected' : '' }}>Author</option>
                                    <option value="supervisor" {{ $searchBy == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                    <option value="keyword" {{ $searchBy == 'keyword' ? 'selected' : '' }}>Keyword</option>
                                    <option value="email" {{ $searchBy == 'email' ? 'selected' : '' }}>Keyword</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>   
                        </div>
                        {!! Form::close() !!}

                        <br/>
                        @if(isset($keyword))
                        <p>Hasil pencarian dengan kata kunci <strong>'{{ $keyword }}'</strong> pada kolom <strong>'{{ $searchBy }}'</strong></p>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>No</th><th>Title</th><th>Author</th><th>Supervisor</th><th>Keywords</th><th class="action">Published <br>(checked by admin)</th><th class="action"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($publications as $item)
                                    <tr>
                                        <td>{{ $n }}</td>
                                        <td>{{ $item->title }}</td><td>{{ $item->author }}</td><td>{{ $item->supervisor }}</td><td>{{ $item->keyword }}</td>
                                        <td class="action">
                                            @if($item->publish != 1)
                                            
                                            {!! Form::open([
                                                
                                                'url' => 'publish-publication',
                                                'method' => 'post'
                                            
                                            ]) !!}
                                            <i class="fa fa-times-circle-o fa-2x" style="color: red;"></i>
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <button class="btn btn-default btn-xs" type="submit">publish now!</button>
                                            {!! Form::close() !!}
                                            
                                            @else
                                           
                                            {!! Form::open([
                                                
                                                'url' => 'publish-publication',
                                                'method' => 'post'
                                            
                                            ]) !!}
                                            <i class="fa fa-check-circle-o fa-2x" style="color: green;"></i>
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <button class="btn btn-default btn-xs" type="submit">unpublish</button>
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                        <td class="action">
                                            <a href="{{ url('/publications/' . $item->id) }}" title="View Publication"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            <a href="{{ url('/publications/' . $item->id . '/edit') }}" title="Edit Publication"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/publications', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-xs',
                                                        'title' => 'Delete Publication',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    <?php $n++; ?>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $publications->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
