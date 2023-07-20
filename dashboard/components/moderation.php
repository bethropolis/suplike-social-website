<h1 class="h2 co">reports</h1>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group mr-2">
        <button type="button" @click="getReports(false)" class="btn btn-sm btn-outline-secondary co">unsolved</button>
        <button type="button" @click="getReports(true)" class="btn btn-sm btn-outline-secondary co">solved</button>
    </div>
</div>
<div class="row co m-0">
    <div v-for="(report, index) in reports" class="col-12 row border-bottom p-2">
        <h4 class="col-2">{{parseInt(report.post_id)||parseInt(report.comment_id)}}</h4>
        <div class="col-3 text-muted">
            <a class="text-muted" :href="parseInt(report.is_comment) ? '../comment?id=' + report.slug + '&comment=' + report.comment_id + '#comment-'+ report.comment_id : '../post?id=' + report.slug">
                <i class="fas fa-eye fa-2x"></i>
            </a>
        </div>
        <span class="col-4">type: {{parseInt(report.is_comment) ? 'comment' : 'post'}}</span>
        <div class="col-3 text-right">
            <button class="btn btn-danger" @click="sendReport(index)">
                <i class="fa fa-trash text-light fa-2x"></i>
            </button>
        </div>
    </div>
</div>