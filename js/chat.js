const app = new Vue({
    el: '#app',
    data: {
        status: '',
        chatwith: _id_user,
        user: _token,
        chatwith_detail: null,
        online: [],
        messages: [],
        player: {},
        playing: null,
        progress: 0,
        audio_meta: {},
        file_type: null,
        timerId: null
    },
    methods: {
        getMessage: async function () {
            const vm = this;
            let start = 0;

            async function sendRequest() {
                // Clear the previous timer if any
                if (vm.timerId) {
                    clearTimeout(vm.timerId);
                }
                await $.get('./inc/message.inc.php?start=' + start + "&from=" + vm.user + "&to=" + vm.chatwith, function (data) {
                    if (data.data) {
                        data.data.forEach(item => {
                            start = item.id;
                            const t = new Date(item.time);
                            const time = `${t.getHours()}:${t.getMinutes() < 10 ? '0' : ''}${t.getMinutes()}`;
                            let audio_id = null;
                            if (item.type == 'mus') {
                                audio_id = Math.random().toString(36).substr(2, 5);
                                vm.player[audio_id] = {
                                    src: item.message,
                                    playing: false,
                                };
                            }
                            vm.messages.push({
                                message: item.message,
                                id: item.who_to,
                                type: item.type,
                                to: false,
                                time: time,
                                audio_id: audio_id,
                            })
                        })
                        $('.messages').animate({
                            scrollTop: $('.messages')[0].scrollHeight
                        });
                    }
                });

                if (vm.chatwith != null) {
                    // Store the new timer id
                    vm.timerId = setTimeout(sendRequest, 2000);
                }
            }

            await sendRequest();
        },
        WhoIsOnline: async function () {
            //meant to get users who are online but the plan changed 
            await $.get('./inc/social.inc.php?user=' + this.user, function (areOnline) {
                app.online = [];
                let _users = areOnline.users;
                let users = _users.sort(function (a, b) {
                    if (a.last_msg == '') return 1;
                    if (b.last_msg == '') return -1;
                    return 0;
                })

                users.forEach(user => {
                    app.online.push({
                        id: user.chat_auth,
                        full_name: user.full_name,
                        profile_picture: user.profile_picture,
                        last_msg: user.last_msg,
                        time: user.time,
                        type: user.type,
                        online: user.online
                    })
                })
            })

        },
        switchMsgdata: function () {
            let vm = this;
            this.messages.forEach(message => {
                if (vm.chatwith == message.id) {
                    message.to = true
                }
            })
        },
        startChat: function (index) {
            this.messages = [];
            this.chatwith = this.online[index].id;
            this.chatwith_detail = this.online[index];
            history.pushState({}, '', '?id=' + this.chatwith);
            document.title = this.online[index].full_name;
        },
        sendUpload: async function (data) {
            // make code for upload to server inc/file.inc.php 
            await $.ajax({
                url: `inc/file.inc.php?from=${app.user}&to=${app.chatwith}&type=${app.file_type}`,
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.success) {
                        // upload_status
                        $('#upload-status').html('');
                         app.messages.push({
                            message: data.file,
                            id: app.user,
                            type: app.file_type,
                            to: true,
                            time: data.time,
                            audio_id: null,
                        })
                        $('.messages').animate({
                            scrollTop: $('.messages')[0].scrollHeight
                        });
                    }
                    app.statusReset();
                    return;
                }
            });


        },
        playerUpdate: function (data) {
            this.player.push(data);
        },
        statusReset: function () {
            setTimeout(()=> {
            this.status = '';
              }, 2000);
        },
        checkrequest: function () {
            // $.get('./inc/checkrequest.inc.php?user=' + this.user, function(data) {
            //   notification.setNotification(data.msg, data.type, data.id);
            // });
        },
        sendMessage: function (type = 'txt') {

            // send message to database
            const vm = this;
            const msg = $('#msg-form').val();
            const time = new Date();
            const timeString = `${time.getHours()}:${time.getMinutes() < 10 ? '0' : ''}${time.getMinutes()}`;
            const data = {
                message: msg,
                from: this.user,
                to: this.chatwith
            }
            $.post('./inc/message.inc.php', data, function (data) {
                $('#msg-form').val('');
                $('.messages').animate({
                    scrollTop: $('.messages')[0].scrollHeight
                });
            });
        },
        goBack: function () {
            this.chatwith = null
        },
        play: function (id) {
            vm = this;
            const player = document.getElementById('audioPlayer');

            if (this.playing != id) {
                player.src = 'inc/' + this.player[id].src;
                this.playing = id;
            }
            if (this.player[id].playing) {
                player.pause();
                this.player[id].playing = false;
            } else {
                player.play();
                this.player[id].playing = true;
                $('#audioPlayer').on('timeupdate', function () {
                    vm.progress = (this.currentTime / this.duration) * 100;
                    $(('#p-' + vm.playing)).css('width', vm.progress + '%');
                    if (vm.progress == 100) {
                        vm.player[id].playing = false;
                        vm.playing = null;
                        player.pause();
                    }
                });
            }
        },
        openModal: function (id) {
            // this is a popup which displays
        },
    },
    watch: {
        chatwith: async function () {
            if (this.chatwith == null) {
                document.title = "messages";
                history.pushState(null, null, "./message.php")
            }
            this.messages = [];
            await this.getMessage();
        },
        messages: function () {
            this.switchMsgdata();
        }
    },
    mounted: async function () {
        if (_id_user) {
            this.getMessage();
        }
        await this.WhoIsOnline();

        const to = await this.online.filter((user) => {
            return user.id == app.chatwith;
        });

        if (to.length > 0) {
            this.chatwith_detail = to[0];
        }
        this.checkrequest();
        // add eventlistener to #songUpload to upload song by calling handleImageUpload

    }
})

// make for me a code
const handleUpload = async (type, event) => {
    app.file_type = type;
    const files = event.target.files;
    const formData = new FormData();
    formData.append("uploadedFile", files[0]);
    // upload_status.innerHTML = "Uploading...";
    app.status = 'uploading...'
    await app.sendUpload(formData);
};
// document ready
$(document).ready(function () {
    $('#songUpload').on('change', function (event) {
        handleUpload('mus', event);
    });
    $('#imgUpload').on('change', function (event) {
        handleUpload('img', event);
    });
});