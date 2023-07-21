<div class="row p-0">
    <div class="card col-md-7 shadow my-3">
        <div class="card-body">
            <canvas id="visitsChart" style="width: 250px"></canvas>
        </div>
    </div>
    <div class="col-md-5 my-3 flex-column ">
        <div class="col-12 my-1">
            <div class="card my-1 border-left shadow">
                <div class="card-header my-1 border-0 ">
                    <h6 class="m-0 font-weight-bold text-primary">analysis</h6>
                </div>
                <div class="row p-2 m-1">
                    <div class="col-7">
                        <i class="fa fa-user fa-2x"></i>
                        <p>new users</p>
                    </div>
                    <div class="col-5 text-right">
                        <h4 class="text-muted co">{{newUser}}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 my-1">
            <div class="card border-left shadow">
                <div class="card-header border-0">
                    <h6 class="m-0 font-weight-bold text-primary">online today</h6>
                </div>
                <div class="row p-2 m-1">
                    <div class="col-8">
                        <i class="fa fa-users fa-2x"></i>
                        <p>users online</p>
                    </div>
                    <div class="col-4 text-right">
                        <h4 class="text-muted co">{{userOnline}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>