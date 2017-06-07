var data = {
    isLogin: false,
    password: '',
    tabsActive: {
        downFiles: true,
        downList: false,
        downListSort: false,
        logout: false
    },
    notification: '',
    downList: [],
    downFiles: []
};

var vm = new Vue({
    el: '#root',
    data: data,
    watch: {
        isLogin: function (val) {
            if (val) {
                this.refreshData();
            }
        }
    },
    mounted: function () {
        this.getIsLogin();
    },
    methods: {
        getIsLogin: function () {
            axios({
                method: 'get',
                url: '/admin/auth'
            })
            .then(function (response) {
                if (response.data) {
                    data.isLogin = (true === response.data);
                }
            });
        },
        login: function () {
            axios({
                method: 'post',
                url: '/admin/auth/login',
                data: {
                    password: data.password
                }
            })
            .then(function (response) {
                if (response.data) {
                    data.isLogin = true;
                }
            });
        },
        logout: function () {
            axios({
                method: 'post',
                url: '/admin/auth/logout'
            })
            .then(function (response) {
                if (response.data) {
                    data.isLogin = false;
                }
            });
        },
        refreshData: function () {
            axios({
                method: 'get',
                url: '/admin/files',
                responseType: 'json'
            })
            .then(function (response) {
                data.downFiles = response.data;
            });
            axios({
                method: 'get',
                url: '/admin/list',
                responseType: 'json'
            })
            .then(function (response) {
                data.downList = response.data;
            });
        },
        enabledFiles: function () {
            var result = [];
            for (var i = 0; i < data.downFiles.length; ++i) {
                if (data.downFiles[i].enabled) {
                    result.push(data.downFiles[i]);
                }
            }
            return result;
        },
        changeTab: function (tab) {
            for (var i in data.tabsActive) {
                data.tabsActive[i] = i === tab;
            }
        },
        updateFile: function (downFile) {
            axios({
                method: 'put',
                url: '/admin/file/' + downFile.id,
                data: {
                    version: downFile.version
                }
            });
        },
        createItem: function () {
            axios({
                method: 'post',
                url: '/admin/item/',
                data: {
                    name: '',
                    description: '',
                    icon_path: '',
                    win_id: 0,
                    mac_id: 0,
                    enabled: false
                }
            })
            .then(function (response) {
                data.downList.push(response.data);
            });
        },
        updateItem: function (item) {
            axios({
                method: 'put',
                url: '/admin/item/' + item.id,
                data: {
                    name: item.name,
                    icon_path: item.icon_path,
                    win_id: item.win_id,
                    mac_id: item.mac_id,
                    description: item.description,
                    enabled: item.enabled
                }
            });
        },
        deleteItem: function (item) {
            if (confirm('确认删除' + item.name + '？')) {
                axios({
                    method: 'delete',
                    url: '/admin/item/' + item.id
                })
                .then(function (response) {
                    if (response.data === true) {
                        var index = data.downList.indexOf(item);
                        data.downList.splice(index, 1);
                    }
                });
            }
        },
        updateList: function () {
            postData = [];
            for (var i = 0; i < data.downList.length; ++i) {
                postData.push(data.downList[i].id);
            }
            axios({
                method: 'put',
                url: '/admin/list/',
                data: {
                    list: postData
                }
            });
        }
    },
    filters: {
        readableSize: function (bytes) {
            if (bytes < 1024) {
                return bytes + 'B';
            }
            if (bytes < 1048576) {
                return Math.round(10 * bytes / 1024) / 10 + 'K';
            }
            if (bytes < 1073741824) {
                return Math.round(10 * bytes / 1048576) / 10 + 'M';
            }
            return Math.round(10 * bytes / 1073741824) / 10 + 'G';
        }
    }
});
