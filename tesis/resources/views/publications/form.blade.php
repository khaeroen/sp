<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'Title', ['class' => 'col-md-12']) !!}
    <div class="col-md-12">
        {!! Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('author') ? 'has-error' : ''}}">
    {!! Form::label('author', 'Author', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::text('author', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('author', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('supervisor') ? 'has-error' : ''}}">
    {!! Form::label('supervisor', 'Supervisor', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::text('supervisor', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('supervisor', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    {!! Form::label('email', 'Email', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('abstract_en') ? 'has-error' : ''}}">
    {!! Form::label('abstract_en', 'abstract (English Language)', ['class' => 'col-md-12']) !!}
    <div class="col-md-12">
        {!! Form::textarea('abstract_en', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('abstract_en', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('abstract_id') ? 'has-error' : ''}}">
    {!! Form::label('abstract_id', 'abstract (Bahasa Indonesia)', ['class' => 'col-md-12']) !!}
    <div class="col-md-12">
        {!! Form::textarea('abstract_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('abstract_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('keyword') ? 'has-error' : ''}}">
    {!! Form::label('keyword', 'Keyword (lebih dari satu kata/frasa: pisahkan dengan tanda ",")', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::text('keyword', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('keyword', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('cover') ? 'has-error' : ''}}">
    {!! Form::label('cover', 'Cover [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        @if(isset($publication->cover))
            @if($publication->cover != null)
            {!! Form::file('cover', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads/publications') . '/' . $publication->email . '_' . 'cover_publikasi.pdf' }}" target="_blank">{{ $publication->email }}_cover_publikasi.pdf</a>
            @endif
        @else
            {!! Form::file('cover', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div><div class="form-group {{ $errors->has('bab_1') ? 'has-error' : ''}}">
    {!! Form::label('file', 'Main File [PDF]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        @if(isset($publication->file))
            @if($publication->file != null)
            {!! Form::file('file', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads/publications/') . '/' . $publication->email . '_' . 'file_publikasi.pdf' }}" target="_blank">{{ $publication->email }}_file_publikasi.pdf</a>
            @endif
        @else
            {!! Form::file('file', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div><div class="form-group {{ $errors->has('lampiran') ? 'has-error' : ''}}">
    {!! Form::label('lampiran', 'Lampiran [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        @if(isset($publication->lampiran))
            @if($publication->lampiran != null)
            {!! Form::file('lampiran', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('lampiran', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads/publications') . '/' . $publication->email . '_' . 'lampiran_publikasi.pdf' }}" target="_blank">{{ $publication->email }}_lampiran_publikasi.pdf</a>
            @endif
        @else
            {!! Form::file('lampiran', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('lampiran', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div>

<div class="form-group">
    <div class="col-md-12">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary form-control']) !!}
    </div>
</div>
