<div class="row mt-4">
    <div class="col-lg-4 col-md-6 mb-2">
        <div class="card shadow border-radius-md">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total users</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{users.length }}
                                <span class="text-success text-sm font-weight-bolder"></span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-start">
                        <div class="icon icon-shape bg-gradient-primary text-center border-radius-md">
                            <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-2">
        <div class="card shadow border-radius-md">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Users online</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ averageOnlineUsers || 0}}/day
                                <span class="text-success text-sm font-weight-bolder"> {{averageOnlineUsersPercentage||0}}%</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-start">
                        <div class="icon icon-shape bg-gradient-primary text-center border-radius-md">
                            <i class="fas fa-user-check text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-2">
        <div class="card shadow border-radius-md">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">New users</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{newUser}}
                                <span class="text-success text-sm font-weight-bolder">+{{ newUserPercentage || 0}}%</span>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-start">
                        <div class="icon icon-shape bg-gradient-primary text-center border-radius-md">
                            <i class="fas fa-user-plus text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h4></h4>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
        </div>
        <button type="button" id="w" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
        </button>
    </div>
</div>
<canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>