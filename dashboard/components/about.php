<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1 class="text-center">About</h1>
            <form>
                <div class="form-group">
                    <label for="app-name">System App Name</label>
                    <input type="text" class="form-control" id="app-name" value="<?= $setupData->name ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="app-version">Version</label>
                    <input type="text" class="form-control" id="app-version" value="<?= $setupData->version ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="app-date">Setup Date</label>
                    <input type="text" class="form-control" id="app-date" value="<?= $date ?>" disabled>
                </div>
            </form>
            <div class="row ml-1">
                <a href="https://github.com/bethropolis/suplike-social-website" target="_blank" class="mx-2" style="color:#8d55e8;">
                    <i class="fab fa-github fa-2x"></i></a>
                <a href="https://twitter.com/bethropolis" target="_blank" class="mx-2" style="color:#8d55e8;">
                    <i class="fab fa-twitter fa-2x"></i></a>

            </div>
            <div class="text-center">
                <a href="../LICENSE">
                    <p class="text-muted">License MIT</p>
                </a>
            </div>
        </div>
    </div>
</div>