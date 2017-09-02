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
        @if(isset($article->cover))
            @if($article->cover != null)
            {!! Form::file('cover', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'cover.pdf' }}" target="_blank">{{ $article->email }}_cover.pdf</a>
            @endif
        @else
            {!! Form::file('cover', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div><div class="form-group {{ $errors->has('bab_1') ? 'has-error' : ''}}">
    {!! Form::label('bab_1', 'Bab 1 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        @if(isset($article->bab_1))
            @if($article->bab_1 != null)
            {!! Form::file('bab_1', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_1', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_1.pdf' }}" target="_blank">{{ $article->email }}_bab_1.pdf</a>
            @endif
        @else
            {!! Form::file('bab_1', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_1', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div><div class="form-group {{ $errors->has('bab_2') ? 'has-error' : ''}}">
    {!! Form::label('bab_2', 'Bab 2 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        @if(isset($article->bab_2))
            @if($article->bab_2 != null)
            {!! Form::file('bab_2', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_2', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_2.pdf' }}" target="_blank">{{ $article->email }}_bab_2.pdf</a>
            @endif
        @else
            {!! Form::file('bab_2', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_2', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div><div class="form-group {{ $errors->has('bab_3') ? 'has-error' : ''}}">
    {!! Form::label('bab_3', 'Bab 3 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        @if(isset($article->bab_3))
            @if($article->bab_3 != null)
            {!! Form::file('bab_3', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_3', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_3.pdf' }}" target="_blank">{{ $article->email }}_bab_3.pdf</a>
            @endif
        @else
            {!! Form::file('bab_3', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_3', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div><div class="form-group {{ $errors->has('bab_4') ? 'has-error' : ''}}">
    {!! Form::label('bab_4', 'Bab 4 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        @if(isset($article->bab_4))
            @if($article->bab_4 != null)
            {!! Form::file('bab_4', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_4', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_4.pdf' }}" target="_blank">{{ $article->email }}_bab_4.pdf</a>
            @endif
        @else
            {!! Form::file('bab_4', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_4', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div><div class="form-group {{ $errors->has('bab_5') ? 'has-error' : ''}}">
    {!! Form::label('bab_5', 'Bab 5 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        @if(isset($article->bab_5))
            @if($article->bab_5 != null)
            {!! Form::file('bab_5', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_5', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_5.pdf' }}" target="_blank">{{ $article->email }}_bab_5.pdf</a>
            @endif
        @else
            {!! Form::file('bab_5', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_5', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div><div class="form-group {{ $errors->has('bab_6') ? 'has-error' : ''}}">
    {!! Form::label('bab_6', 'Bab 6 [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        @if(isset($article->bab_6))
            @if($article->bab_6 != null)
            {!! Form::file('bab_6', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_6', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'bab_6.pdf' }}" target="_blank">{{ $article->email }}_bab_6.pdf</a>
            @endif
        @else
            {!! Form::file('bab_6', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('bab_6', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div><div class="form-group {{ $errors->has('lampiran') ? 'has-error' : ''}}">
    {!! Form::label('lampiran', 'Lampiran [pdf]', ['class' => 'col-md-12 ']) !!}
    <div class="col-md-12">
        @if(isset($article->cover))
            @if($article->cover != null)
            {!! Form::file('cover', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
            <a href="{{ url('/uploads') . '/' . $article->email . '_' . 'cover.pdf' }}" target="_blank">{{ $article->email }}_cover.pdf</a>
            @endif
        @else
            {!! Form::file('cover', null, ['class' => 'form-control', 'required' => 'required']) !!}
            {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
        @endif
    </div>
</div>

<div class="form-group">
    <div class="col-md-12">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary form-control']) !!}
    </div>
</div>
