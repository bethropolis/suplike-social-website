<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block nav-color sidebar collapse">
    <!---------------------------------side nav--------------------------------------------------->
    <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#" :class="stage == 0? 'active':''" @click.prevent="changeStage(0)">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="ml-2">Dashboard</span> <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" :class="stage == 1? 'active':''" @click.prevent="changeStage(1)">
                    <i class="fas fa-users"></i>
                    <span class="ml-2">Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" :class="stage == 2? 'active':''" @click.prevent="changeStage(2)">
                    <i class="fas fa-file-alt"></i>
                    <span class="ml-2">Posts</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" :class="stage == 3? 'active':''" @click.prevent="changeStage(3)">
                    <i class="fas fa-eye"></i>
                    <span class="ml-2">Visits</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" :class="stage == 4? 'active':''" @click.prevent="changeStage(4)">
                    <i class="fas fa-chart-bar"></i>
                    <span class="ml-2">Social Engagement</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" :class="stage == 5? 'active':''" @click.prevent="changeStage(5)">
                    <i class="fas fa-flag"></i>
                    <span class="ml-2">Moderation</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" :class="stage == 6? 'active':''" @click.prevent="changeStage(6)">
                    <i class="fas fa-layer-group"></i>
                    <span class="ml-2">Integrations</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="stage == 10? 'active':''" @click.prevent="changeStage(10)" href="#">
                    <i class="fas fa-cog"></i>
                    <span class="ml-2">Settings</span>
                </a>
            </li>
        </ul>
        <ul class="nav flex-column mb-2">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>ADMIN: {{user}}</span>
            </h6>
            <li class="nav-item">
                <a class="nav-link" :class="stage == 11? 'active':''" @click.prevent="changeStage(11)" href="#">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="ml-2">Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="stage == 12? 'active':''" @click.prevent="changeStage(12)" href="#">
                    <i class="fas fa-info-circle"></i>
                    <span class="ml-2">About</span>
                </a>
            </li>
        </ul>
    </div>
</nav>