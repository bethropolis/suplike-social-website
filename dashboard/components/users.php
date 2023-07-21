<div class="container-fluid mt-2">
    <!-- DataTales Example -->
    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive co">
                <table class="table " id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">id</th>
                            <th class="text-center align-middle">username</th>
                            <th class="text-center align-middle">last online</th>
                            <th class="text-center align-middle">data joined</th>
                            <th class="text-center align-middle">status</th>
                            <th class="text-center align-middle">action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center align-middle">id</th>
                            <th class="text-center align-middle">username</th>
                            <th class="text-center align-middle">last online</th>
                            <th class="text-center align-middle">data joined</th>
                            <th class="text-center align-middle">status</th>
                            <th class="text-center align-middle">action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr v-for="user in users">
                            <td class="text-center align-middle">{{user.id}}</td>
                            <td class="text-center align-middle justify-content-around"> <span class="mx-1">{{user.username}}</span> <i v-if="user.admin" class="fa fa-user-shield c-ho"></i> <i v-if="user.bot" class="fa fa-robot c-ho"></i></td>
                            <td class="text-center align-middle">{{user.online}}</td>
                            <td class="text-center align-middle">{{user.joined}}</td>
                            <td class="text-center align-middle">
                                <span v-if="user.status == 'active'" class="badge px-3 py-1  bg-gradient-success">ACTIVE</span>
                                <span v-if="user.status == 'blocked'" class="badge px-3 py-1  badge-dark">BLOCKED</span>
                            <td class="text-center align-middle">
                                <a class="dropdown-toggle co" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu cob dropdown-menu-right shadow animated--fade-in bga" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" :href="'../profile.php?id='+user.token">visit profile</a>
                                    <a v-if="user.id != 1" class="dropdown-item" href="#" @click="toggleAdmin(user)">{{user.admin == 1 ? "revoke admin" : "make admin"}}</a>
                                    <a v-if="user.id != 1" class="dropdown-item" href="#" @click="blockUser(user)">{{user.status == "blocked" ? "unblock user" : " block user"}}</a>
                                    <a v-if="user.id != 1" class="dropdown-item text-danger" href="#" @click="deleteUser(user)">delete user</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>