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
</div><div class="form-group {{ $errors->has('abstrack_en') ? 'has-error' : ''}}">
    {!! Form::label('abstrack_en', 'Abstrack (English Language)', ['class' => 'col-md-12']) !!}
    <div class="col-md-12">
        {!! Form::textarea('abstrack_en', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('abstrack_en', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('abstrack_id') ? 'has-error' : ''}}">
    {!! Form::label('abstrack_id', 'Abstrack (Bahasa Indonesia)', ['class' => 'col-md-12']) !!}
    <div class="col-md-12">
        {!! Form::textarea('abstrack_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('abstrack_id', '<p class="help-block">:message</p>') !!}
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
        {!! Form::file('cover', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_1') ? 'has-error' : ''}}">
    {!! Form::label('bab_1', 'Bab 1 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::file('bab_1', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('bab_1', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_2') ? 'has-error' : ''}}">
    {!! Form::label('bab_2', 'Bab 2 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::file('bab_2', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('bab_2', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_3') ? 'has-error' : ''}}">
    {!! Form::label('bab_3', 'Bab 3 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::file('bab_3', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('bab_3', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_4') ? 'has-error' : ''}}">
    {!! Form::label('bab_4', 'Bab 4 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::file('bab_4', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('bab_4', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_5') ? 'has-error' : ''}}">
    {!! Form::label('bab_5', 'Bab 5 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::file('bab_5', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('bab_5', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('bab_6') ? 'has-error' : ''}}">
    {!! Form::label('bab_6', 'Bab 6 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::file('bab_6', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('bab_6', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('lampiran') ? 'has-error' : ''}}">
    {!! Form::label('lampiran', 'Lampiran [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        {!! Form::file('lampiran', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('lampiran', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-12">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary form-control']) !!}
    </div>
</div>
