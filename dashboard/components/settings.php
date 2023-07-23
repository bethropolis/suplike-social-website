<h1 class="mt-2">Settings</h1>

<div class="success-banner" style="display: none;">
    <div class="alert alert-success" role="alert">
        Success! The form has been submitted.
    </div>
</div>
<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a class="nav-link c-ho" @click.prevent="settings = 1" :class="settings == 1 ? 'active':''" href="#">general</a>
    </li>
    <li class="nav-item">
        <a class="nav-link c-ho" @click.prevent="settings = 3" :class="settings == 3 ? 'active':''" href="#">application</a>
    </li>
    <li class="nav-item">
        <a class="nav-link c-ho" @click.prevent="settings = 2" :class="settings == 2 ? 'active':''" href="#">API</a>
    </li>
    <li class="nav-item">
        <a class="nav-link c-ho" @click.prevent="settings = 4" :class="settings == 4 ? 'active':''" href="#">config</a>
    </li>
    <li class="nav-item">
        <a class="nav-link c-ho" @click.prevent="settings = 5" :class="settings == 5 ? 'active': ''" href="#">updates</a>
    </li>
</ul>
<!-- dark mode toggle -->
<div v-if="settings == 5" class="container">
    <form>
        <div class="form-group">
        </div>
        <button type="button" class="btn btn-primary bg" @click="checkLatestRelease('<?= $setupData->version ?>')"> Check Latest Release
            <i class="fa fa-spinner fa-spin" id="updates-spinner" style="display: none"></i></button>

        <div id="latestReleaseInfo" class="mt-4" style="display: none;">
            <h2>Latest Version Information</h2>
            <p id="versionText"></p>
            <p>Installed Version: <?= $setupData->version ?> </p>

            <div class="update-field">

            </div>
        </div>
    </form>
</div>
<div v-if="settings == 4" class="container mb-4">
    <form @submit.prevent="saveConfig">
        <div class="form-group">
            <label for="dbDatabase">Database Name</label>
            <input type="text" class="form-control" id="dbDatabase" v-model="config.dbDatabase">
        </div>
        <div class="form-group">
            <label for="dbHost">Database Host</label>
            <input type="text" class="form-control" id="dbHost" v-model="config.dbHost">
        </div>
        <div class="form-group">
            <label for="dbUsername">Database Username</label>
            <input type="text" class="form-control" id="dbUsername" v-model="config.dbUsername">
        </div>
        <div class="form-group">
            <label for="dbPassword">Database Password</label>
            <input type="password" class="form-control" id="dbPassword" v-model="config.dbPassword">
        </div>
        <div class="form-group">
            <label for="dbPort">Database Port</label>
            <input type="number" class="form-control" id="dbPort" v-model="config.dbPort">
        </div>
        <button type="submit" class="btn btn-primary bg">Save Config</button>
    </form>
</div>

<div v-if="settings === 3" class="container">
    <form @submit.prevent="saveConfig">
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="emailVerification" v-model="config.emailVerification">
            <label class="form-check-label" for="emailVerification">Enable/Disable Email Verification</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="userSignup" v-model="config.userSignup">
            <label class="form-check-label" for="userSignup">Enable/Disable User Signup</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="userPost" v-model="config.userPost">
            <label class="form-check-label" for="userPost">Enable/Disable User Post</label>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="userComments" v-model="config.userComments">
            <label class="form-check-label" for="userComments">Enable/Disable User Comments</label>
        </div>
        <div class="form-group">
            <label for="appEmail">App Email <span class="small text-muted">(the email used to send verifications)</span></label>
            <input type="email" class="form-control" id="appEmail" v-model="config.appEmail" placeholder="eg. test@<?= $_SERVER['HTTP_HOST'] ?>">
        </div>
        <button type="submit" class="btn btn-primary bg">Save Settings</button>
    </form>
</div>

<div v-if="settings === 2" class="container">
    <form @submit.prevent="saveConfig">
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="apiAccess" v-model="config.apiAccess">
            <label class="form-check-label" for="apiAccess">Enable API Access</label>
        </div>
        <button type="submit" class="btn btn-primary bg">Save Settings</button>
    </form>
</div>

<div v-if="settings === 1" class="container">
    <form @submit.prevent="saveConfig">
        <div class="form-group">
            <label for="appName">Application Name</label>
            <input type="text" id="appName" class="form-control" v-model="config.appName">
        </div>
        <div class="form-group">
            <label for="fileSizeLimit">File Size Limit (<span id="mbCalc">{{ (config.fileSizeLimit/(1024*1024)).toFixed(3) }}</span> MB)</label>
            <input type="number" id="fileSizeLimit" class="form-control" v-model="config.fileSizeLimit">
        </div>
        <div class="form-group">
            <label for="defaultTheme">Default Theme</label>
            <select class="form-control" id="defaultTheme" v-model="config.defaultTheme">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
                <option value="install" disabled>Install New Themes</option>
            </select>
        </div>
        <div class="form-group">
            <label for="accentColor">Accent Color</label>
            <input type="color" id="accentColor" class="border-0 mx-3" v-model="config.accentColor">
            <button class="btn" @click="config.accentColor = ''">reset</button>
        </div>
        <button type="submit" class="btn btn-primary bg">Save Settings</button>
    </form>
</div>