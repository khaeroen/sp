<!DOCTYPE html>
<html>
    <head>
        <title>Tesis</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
            <div class="col-md-12" style="padding-bottom: 30px;">
                {!! Form::open([
                    
                    'action' => 'TesisController@index',
                    'method' => 'get'
                
                ]) !!}

                <input type="text" name="search" placeholder="cari...">
                <select name="searchBy">
                    <option value="title">Thesis Title</option>
                    <option value="author">Author</option>
                </select>
                <input type="submit" class="btn btn-default">

                {!! Form::close() !!}
            </div>

            @if(isset($searchText))
            <div class="col-md-12" style="padding-bottom: 30px;">
                <p>Pencarian dengan kata kunci <strong>'{{ $searchText }}' </strong> berdasarkan <strong>{{ $searchCategory }}</strong></p>
            </div>
            @endif

            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th>No.</th>
                        <th>Thesis Title</th>
                        <th>Author</th>
                        <th>Email (contact)</th>
                        <th>Abstract Description</th>
                        <th>Link Thesis</th>
                        <th>Uploaded Thesis</th>
                    </tr>
                    @foreach($rows as $row)
                    <tr>
                        <td>{{ $n }}</td>
                        <td>{{ $row['title_thesis'] }}</td>
                        <td>{{ $row['full_name'] }}</td>
                        <td>{{ $row['email'] }}</td>
                        <td>{!! $row['abstract'] !!}</td>
                        <td>
                            @if(isset($row['link_journal']))
                                <a href="{{ $row['link_journal'] }}" target="_blank">{{ $row['link_journal'] }}</a>
                            @endif
                        </td>
                        <td>
                            @if(isset($row['upload_thesis']))
                                <a href="{{ $row['upload_thesis'] }}" class="btn btn-primary" target="_blank">Download Thesis</a>
                            @endif
                        </td>
                       
                    </tr>
                    
                    @endforeach
                </table>
        </div>
    </body>
</html>
