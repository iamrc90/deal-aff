<!DOCTYPE html>
<html lang="en">
<head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-110993517-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-110993517-1');
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <title>{{$title}}</title>
</head>
<body>

<!-- Main Header -->
<header id="main-header" class="py-2 hidden-md-down">
    <div class="container">
        <div class="row py-3">
            <div class="col-md-3">
                <a href="/">
                    <img class="logo img-fluid" src="/img/1.png" alt="Deals" id="logo">
                </a>
            </div>
            <div class="col-lg-4 offset-1">
                <form class="form-inline my-4" action="{{route('search')}}">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search for...">
                        <span class="input-group-btn">
                  <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                </span>
                    </div>
                </form>
            </div>
            <div class="col-lg-3">

                <ul class="list-inline my-2">
                    <div class="float-right social-icons">
                        <p class="text-muted d-inline-block">Follow us: </p>
                        <li class="list-inline-item"><a href="https://www.facebook.com/pg/DealKhojo-1969393653302311"><i class="fa fa-facebook"></i></a></li>
                        <li class="list-inline-item"><a href="https://twitter.com/dealkhojo53"><i class="fa fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="https://www.instagram.com/dealkhojo/"><i class="fa fa-instagram"></i></a></li>
                    </div>

                </ul>
            </div>
        </div>
    </div>
</header>
<!-- Navbar -->
<nav class="navbar navbar-toggleable-md navbar-inverse bgcolor p-0" id="mainNav">
    <div class="container">
        <button class="navbar-toggler navbar-toggler-right bgcolor text-white" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a href="#" class="brand hidden-lg-up"><img class="logo img-fluid" src="/img/1.png" alt="Deals" id="logo" width="300" height="200"></a>
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('home')}}">Latest<span class="sr-only">(current)</span></a>
                </li>
                @foreach($categories as $category)
                <li class="nav-item">
                    <a class="nav-link" href="{{route('categorywise_posts',['category_slug' => $category->slug])}}">{{$category->name}}<span class="sr-only">(current)</span></a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile search box -->
<section class="container hidden-lg-up mt-1">
    <div class="row">
        <form class="my-4 col">
            <input class="form-control mr-sm-2 d-inline-block" type="text" placeholder="Search">
            <button class="btn btn-success my-2 my-sm-1 btn-block" type="submit">Search</button>
        </form>
    </div>
</section>

@yield('content')

<!-- Footer -->

<footer id="footer">
    <div class="row mx-2">
        <div class="col-md-4 mt-2 pt-1"><a href="/contact-us">Contact Us</a></div>
        <div class="col-md-4 mt-2 pt-1"><p class="text-center">Copyright&copy; DealKhojo 2017</p></div>
        <div class="col-md-4 mt-2 pt-1"><div class="float-right"><a href="/terms-of-service">Terms of service</a>
                {{--| <a href="#">Privacy Policy</a></div></div>--}}
    </div>
</footer>

<script src="/js/jquery.min.js"></script>
<script src="/js/tether.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="/js/pace.min.js"></script>
<script>var baseUrl = '/'; </script>
<script src="/js/application.js"></script>
@yield('ext_js')

</body>
</html>
