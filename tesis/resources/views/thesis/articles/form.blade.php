<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'Title', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('author') ? 'has-error' : ''}}">
    {!! Form::label('author', 'Author', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('author', null, ['class' => 'form-control']) !!}
        {!! $errors->first('author', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('supervisor') ? 'has-error' : ''}}">
    {!! Form::label('supervisor', 'Supervisor', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('supervisor', null, ['class' => 'form-control']) !!}
        {!! $errors->first('supervisor', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('abstrack_en') ? 'has-error' : ''}}">
    {!! Form::label('abstrack_en', 'Abstrack En', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('abstrack_en', null, ['class' => 'form-control']) !!}
        {!! $errors->first('abstrack_en', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('abstrack_id') ? 'has-error' : ''}}">
    {!! Form::label('abstrack_id', 'Abstrack Id', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('abstrack_id', null, ['class' => 'form-control']) !!}
        {!! $errors->first('abstrack_id', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('keyword') ? 'has-error' : ''}}">
    {!! Form::label('keyword', 'Keyword', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('keyword', null, ['class' => 'form-control']) !!}
        {!! $errors->first('keyword', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('cover') ? 'has-error' : ''}}">
    {!! Form::label('cover', 'Cover', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::file('cover', null, ['class' => 'form-control']) !!}
        {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_1') ? 'has-error' : ''}}">
    {!! Form::label('bab_1', 'Bab 1', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::file('bab_1', null, ['class' => 'form-control']) !!}
        {!! $errors->first('bab_1', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_2') ? 'has-error' : ''}}">
    {!! Form::label('bab_2', 'Bab 2', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::file('bab_2', null, ['class' => 'form-control']) !!}
        {!! $errors->first('bab_2', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_3') ? 'has-error' : ''}}">
    {!! Form::label('bab_3', 'Bab 3', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::file('bab_3', null, ['class' => 'form-control']) !!}
        {!! $errors->first('bab_3', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_4') ? 'has-error' : ''}}">
    {!! Form::label('bab_4', 'Bab 4', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::file('bab_4', null, ['class' => 'form-control']) !!}
        {!! $errors->first('bab_4', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_5') ? 'has-error' : ''}}">
    {!! Form::label('bab_5', 'Bab 5', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::file('bab_5', null, ['class' => 'form-control']) !!}
        {!! $errors->first('bab_5', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_6') ? 'has-error' : ''}}">
    {!! Form::label('bab_6', 'Bab 6', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::file('bab_6', null, ['class' => 'form-control']) !!}
        {!! $errors->first('bab_6', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('lampiran') ? 'has-error' : ''}}">
    {!! Form::label('lampiran', 'Lampiran', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::file('lampiran', null, ['class' => 'form-control']) !!}
        {!! $errors->first('lampiran', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
