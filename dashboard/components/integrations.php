<h1 class="mt-2 co">Plugins</h1>

<!-- Success Banner -->
<div class="success-banner alert alert-success" role="alert" v-if="successMessage">
    {{ successMessage }}
</div>

<!-- Navigation Tabs -->
<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a class="nav-link  c-ho" :class="{ active: plugins.activeTab === 'installed' }" @click="changeTab('installed')" href="#">Installed</a>
    </li>
    <li class="nav-item">
        <a class="nav-link  c-ho" :class="{ active: plugins.activeTab === 'marketplace' }" @click="changeTab('marketplace')" href="#">Marketplace</a>
    </li>
</ul>

<!-- Installed Plugins -->
<div v-if="plugins.activeTab === 'installed'">
    <div class="row">
        <div class="col-md-6 col-lg-4 mb-3" v-for="plugin in plugins.installedPlugins" :key="plugin.id">
            <div class="card">
                <div class="card-body">
                    <div class="top row align-items-center">
                        <h5 class="col-8 m-0">{{ plugin.name }}</h5>
                        <ul class="list-group list-group-flush text-right col-4 p-0 m-0">
                            <span class="text-muted m-2">V{{ plugin.version }}</span>
                        </ul>
                    </div>
                    <p class="card-text">{{ plugin.description }}</p>
                </div>
                <div class="card-footer text-right row justify-content-between align-items-center mx-0">
                    <!-- author -->
                    <div class="author">
                        <a :href="plugin.author.url" target="_blank">{{ plugin.author.name }}</a>
                    </div>
                    <div class="right">
                        <a :href="plugin.homepage" v-if="plugin.homepage" class="btn btn-link" target="_blank"><i class="fas fa-home-alt"></i></a>
                        <a :href="plugin.github" v-if="plugin.github" class="btn btn-link" target="_blank"><i class="fab fa-github"></i></a>
                        <a :href="plugin.gitlab" v-if="plugin.gitlab" class="btn btn-link" target="_blank"><i class="fab fa-gitlab"></i></a>
                        <button class="btn btn-danger btn-sm" @click="uninstallPlugin(plugin)">Uninstall</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div v-if="plugins.installedPlugins.length === 0" class="text-center  m-5 p-4">
        <p class="text-muted">No installed plugins</p>
    </div>
</div>

<!-- Marketplace Plugins -->
<div v-else>
    <div class="row">
        <div class="col-md-6 col-lg-4 mb-3" v-for="plugin in plugins.marketplacePlugins" :key="plugin.id">
            <div class="card">
                <div class="card-body">
                    <div class="top row align-items-center">
                        <h5 class="col-8 m-0">{{ plugin.name }}</h5>
                        <ul class="list-group list-group-flush text-right col-4 p-0 m-0">
                            <span class="text-muted m-2">V{{ plugin.version }}</span>
                        </ul>
                    </div>
                    <p class="card-text">{{ plugin.description }}</p>
                </div>
                <div class="card-footer text-right row justify-content-between align-items-center mx-0">
                    <!-- author -->
                    <div class="author">
                        <a :href="plugin.author.url" target="_blank">{{ plugin.author.name }}</a>
                    </div>
                    <div class="right">
                        <a :href="plugin.homepage" v-if="plugin.homepage" class="btn btn-link" target="_blank"><i class="fas fa-home-alt"></i></a>
                        <a :href="plugin.gitlab" v-if="plugin.gitlab" class="btn btn-link" target="_blank"><i class="fab fa-gitlab"></i></a>
                        <a :href="plugin.github" v-if="plugin.github" class="btn btn-link" target="_blank"><i class="fab fa-github"></i></a>
                        <button class="btn btn-success btn-sm" @click="installPlugin(plugin)">Install</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="plugins.marketplacePlugins.length == 0" class="text-center m-5 p-4">
        <p class="text-muted">No plugins found in the marketplace</p>
    </div>
</div>