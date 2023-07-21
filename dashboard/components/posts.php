<div class="row justify-content-center p-0">
    <div class="card col-md-7 shadow my-3">
        <div class="card-body">
            <canvas id="postsChart"></canvas>
        </div>
    </div>
    <div class="card mx-4 col-md-4 p-0">
        <h4 class="text-primary text-center">posts</h4>
        <div class="card-body mx-auto w-100">
            <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                <div class="col-8">
                    <h6>Monday</h6>
                </div>
                <div class="col-4 text-left">{{ postDayM }}</div>
            </div>
            <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                <div class="col-8">
                    <h6>Tuesday</h6>
                </div>
                <div class="col-4 text-left">{{postDayT}}</div>
            </div>
            <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                <div class="col-8">
                    <h6>Wednesday</h6>
                </div>
                <div class="col-4 text-left">{{postDayW}}</div>
            </div>
            <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                <div class="col-8">
                    <h6>Thursday</h6>
                </div>
                <div class="col-4 text-left">{{postDayTh}}</div>
            </div>
            <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                <div class="col-8">
                    <h6>Friday</h6>
                </div>
                <div class="col-4 text-left">{{postDayF}}</div>
            </div>
            <div class="col-12 row p-2 w-100 m-auto flexcenter page-link">
                <div class="col-8">
                    <h6>weekend</h6>
                </div>
                <div class="col-4 text-left">{{postDayWeek}}</div>
            </div>
        </div>
    </div>
</div>