<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<html>
    <body>
        @if (!empty($errors))
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
        @endif
        @section('navbar')
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">Home</a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
             <li class="nav-item active">
                <a class="nav-link" href="/domains">Domains<span class="sr-only">(current)</span></a>
             </li>
            </ul>
          </div>
        </nav>
        @show

        @section('primary_content')
        <div class="jumbotron">
            <form action="/domains" method="post">
            <div class="form-group">
                <label for="urlinput">Url</label>
                <input type="text" name="url" class="form-control" id="urlinput" aria-describedby="inputHelp" placeholder="Enter url">
                <small id="inputHelp" class="form-text text-muted">Enter any url</small>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </div>
        @show
    </body>
</html>