<div class="row mb-4">
    <!-- first canvas  -->
    <div class="col-md-4 mt-3 mb-4" style="height:180px;">
        <canvas id="myPieChart"></canvas>
        <div class="mt-4 text-center small">
            <span class="mr-2">
                <i class="fa fa-circle text-primary"></i>
                Likes

            </span>
            <span class="mr-2">
                <i class="fa fa-circle text-success"></i>
                Comments

            </span>
            <span class="mr-2">
                <i class="fa fa-circle text-info"></i>
                Share

            </span>
        </div>
    </div>
    <!-- second canvas  -->
    <div class="col-md-4 mt-3 mb-4" style="height:180px;">
        <canvas id="myPieChart1"></canvas>
        <div class="mt-4 text-center small">
            <span class="mr-2">
                <i class="fa fa-circle" style="color: #00e000"></i>
                Chat

            </span>
            <span class="mr-2">
                <i class="fa fa-circle" style="color: #60c0ff"></i>
                Post

            </span>
        </div>
    </div>
    <!-- third canvas  -->
    <div class="col-md-4 mt-3 mb-4" style="height:180px;">
        <canvas id="myPieChart2"></canvas>
        <div class="mt-4 text-center small">
            <span class="mr-2">
                <i class="fa fa-circle" style="color:#8040ff"></i>
                Follows

            </span>
            <span class="mr-2">
                <i class="fa fa-circle" style="color: #e0ff40"></i>
                Messages

            </span>
        </div>
    </div>
</div>
<!-- the lower part -->
<div class="row mt-2">
    <div class="col-xl-8 col-lg-7 mt-2">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">this week's analysis</h4>
            </div>
            <div class="card-body">
                <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                    <div class="col-4">
                        <h6>likes</h6>
                    </div>
                    <div class="offset-4 col-4" style="text-align: right;">{{likes}}</div>
                </div>
                <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                    <div class="col-4">
                        <h6>posts</h6>
                    </div>
                    <div class="offset-4 col-4" style="text-align: right;">{{post}}</div>
                </div>
                <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                    <div class="col-4">
                        <h6>chat</h6>
                    </div>
                    <div class="offset-4 col-4" style="text-align: right;">{{chat}}</div>
                </div>
                <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                    <div class="col-4">
                        <h6>follows</h6>
                    </div>
                    <div class="offset-4 col-4" style="text-align: right;">{{follows}}</div>
                </div>
                <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                    <div class="col-4">
                        <h6>comments</h6>
                    </div>
                    <div class="offset-4 col-4" style="text-align: right;">{{comments}}</div>
                </div>
                <div class="col-md-12 row p-1 w-100 m-auto my-1 border flexcenter" style="height: 70px;">
                    <div class="col-4">
                        <h6>shares</h6>
                    </div>
                    <div class="offset-4 col-4" style="text-align: right;">{{share}}</div>
                </div>
            </div>
        </div>
    </div>
    <!-- line Chart -->
    <div class="col-xl-4 col-lg-5 mt-2">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h4 class="m-0 font-weight-bold text-primary">social Rates</h4>
            </div>
            <div class="card-body">
                <canvas id="totalChart" width="280" height="240" class="chartjs-render-monitor" style="display: block; width: 280px; height: 240px;"></canvas>
            </div>
        </div>
    </div>
</div>