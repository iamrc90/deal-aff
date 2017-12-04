@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3" style="background: #fff">
            <h2>{{$post->title}}</h2>
            <p class="bg-primary" style="display: inline-block; padding: 4px;"> @if($post->status == 1)Published @else Unpublished @endif</p>
            {!! $post->body !!}
            <div>posted on {{$post->formattedDateString}}</div>
        </div>
    </div>
</div>
@endsection