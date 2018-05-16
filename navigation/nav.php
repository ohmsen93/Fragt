<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="?">Fragt</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php if($page == "ordre"){echo "active";}else{echo "";}; ?>">
                <a class="nav-link" href="?page=ordre">Ordre Bestilling</a>
            </li>
            <li class="nav-item <?php if($page == "page2"){echo "active";}else{echo "";}; ?>" >
                <a class="nav-link" href="?page=page2">Page 2</a>
            </li>
            <li class="nav-item <?php if($page == "page3"){echo "active";}else{echo "";}; ?>" >
                <a class="nav-link" href="?page=page3">Page 3</a>
            </li>

        </ul>


    </div>
    <div class="btn-group">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
            Login
        </button>
        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="triggerId">
            <div class="container col-sm-12">
            <div class="form-group">
                <label for="">Brugernavn</label>
                <input type="text" class="form-control" name="" id=""  placeholder="">
                <label for="">Kodeord</label>
                <input type="password" class="form-control" name="" id=""  placeholder="">
                <label for=""></label>

                <button type="button" class="btn btn-primary col-sm-12">Login</button>
            </div>
            <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="?page=nyBruger">Opret ny Bruger</a>
                <a class="dropdown-item" href="?page=nyMedarbejder">Medarbejder Login</a>
            </div>
        </div>
    </div>
</nav>