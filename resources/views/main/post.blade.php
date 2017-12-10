@extends('layouts.app-main')

@section('content')

    <!-- post section -->

    <section class="m-3" id="post-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="card col pt-2">
                            <div class="heading">
                                {{--<h1 class="heading-text h6">{{$main_heading}}</h1>--}}
                                {{ Breadcrumbs::render('category',$main_heading) }}
                            </div>
                        </div>
                    </div>
                    @if(count($posts)>0)
                        @foreach($posts as $post)
                        <!-- post card -->
                        <div class="row">
                            <div class="col post-item">
                                <!-- insert content  -->
                                <a href="{{route('viewDeal',['post_slug'=>$post->slug])}}"><h3 class="h4 mt-1" >{{$post->title}}</h3></a>
                                <div class="mt-3">
                                    {!! $post->body !!}
                                </div>
                                <div class="posted-date">
                                    <abbr title="{{$post->updated_at}}">{{$post->formattedDateString}}</abbr>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    {{--end here--}}
                    {{--start pagination--}}

                            <div class="row hidden-md-down">
                                <div class="col pagination-style">
                                {{ $posts->links() }}
                                </div>
                            </div>

                            <div class="row hidden-sm-up">
                                <div class="col-sm-6">
                                    <a  class="btn btn-primary" href="{{$posts->previousPageUrl()}}">Prev</a>
                                </div>
                                <div class="col-sm-6">
                                    <a class="btn btn-primary"  href="{{$posts->nextPageUrl()}}">Next</a>
                                </div>
                            </div>


                    {{--end pagination--}}
                        @else
                    <div class="row">
                        <div class="col post-item text-center"><h5>Did not match any deals in this category</h5></div>
                    </div>
                        @endif
                </div>
                <div class="col-md-4" id="sidebar">
                    <div class="row">
                        <div class="col subscribe-box ml-md-1">
                            <h6>Get best deals in your email box</h6>
                            <p>Complete the form below, and we'll send you the best deals.</p>
                            <form class="form-inline" id="subsciption-form">
                                <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                                    <div class="input-group-addon bg-success"><i class="fa fa-envelope"></i></div>
                                    <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                                </div>

                                <button type="submit" class="btn btn-success">subscribe</button>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col ml-md-2 recent-deals-sidebar">
                            <h6>Recent Deals</h6>
                            <ul>
                                @foreach($recentDeals as $deal)
                                    <li><a href="{{route('viewDeal',['post_slug'=>$deal->slug])}}">{{$deal->title}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </section>
    @endsection