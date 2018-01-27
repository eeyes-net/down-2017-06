var data = {
    isLogin: false,
    password: '',
    tabsActive: {
        downFiles: true,
        downList: false,
        downListSort: false,
        issue: false,
        stats: false,
        logout: false
    },
    notification: '',
    downList: [],
    downFiles: [],
    icons: [],
    issues: [],
    issueCurrentPage: 1,
    issueLastPage: 1,
    statsByDate: [],
    statsByFile: [],
    isModalIconsShow: false,
    currentModalIconsItem: null,
    fileUploadFile: null,
    iconUploaderPreviewSrc: ''
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
        refreshFilesData: function () {
            axios({
                method: 'get',
                url: '/admin/files',
                responseType: 'json'
            })
            .then(function (response) {
                data.downFiles = response.data;
            });
        },
        refreshData: function () {
            this.refreshFilesData();
            axios({
                method: 'get',
                url: '/admin/list',
                responseType: 'json'
            })
            .then(function (response) {
                data.downList = response.data;
            });
            axios({
                method: 'get',
                url: '/admin/icons',
                responseType: 'json'
            })
            .then(function (response) {
                data.icons = response.data;
            });
            this.getIssues(1);
            axios({
                method: 'get',
                url: '/admin/stats/date',
                responseType: 'json'
            })
            .then(function (response) {
                data.statsByDate = response.data;
            });
            axios({
                method: 'get',
                url: '/admin/stats/file',
                responseType: 'json'
            })
            .then(function (response) {
                data.statsByFile = response.data;
            });
        },
        refreshFiles: function () {
            axios({
                method: 'get',
                url: '/admin/files/refresh',
                responseType: 'json'
            })
            .then(function (response) {
                if (response.data) {
                    vm.refreshFilesData();
                }
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
        },
        showModalIcons: function (item) {
            this.isModalIconsShow = true;
            this.currentModalIconsItem = item;
        },
        hideModalIcons: function () {
            this.isModalIconsShow = false;
        },
        changeIcon: function (icon) {
            this.currentModalIconsItem.icon_path = icon;
            this.hideModalIcons();
        },
        onIconUploaderChange: function (e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.fileUploadFile = files[0];
            this.iconUploaderPreviewSrc = URL.createObjectURL(this.fileUploadFile);
        },
        uploadIcon: function () {
            var formData = new FormData();
            formData.append('file', this.fileUploadFile);
            axios({
                method: 'post',
                url: '/admin/icons/',
                data: formData
            })
            .then(function (response) {
                data.icons.push(response.data);
            });
        },
        getIssues: function (page) {
            axios({
                method: 'get',
                url: '/admin/issues',
                params: {
                    page: page
                },
                responseType: 'json'
            })
            .then(function (response) {
                data.issueCurrentPage = page;
                data.issueLastPage = response.data.last_page;
                data.issues = response.data.data;
            });
        },
        /**
         * 页码列表
         *
         * @link https://gist.github.com/kottenator/9d936eb3e4e3c3e02598
         * @return {Array}
         */
        issuePageList: function () {
            var current = this.issueCurrentPage;
            var last = this.issueLastPage;
            var delta = 2;
            var left = current - delta;
            var right = current + delta;
            var range = [];
            for (var i = 1; i <= last; ++i) {
                if (i === 1 || i === last || i >= left && i <= right) {
                    range.push(i);
                }
            }
            var result = [];
            var lastPage = 0;
            for (var i = 0; i < range.length; ++i) {
                var page = range[i];
                var deltaPage = page - lastPage;
                if (deltaPage > 2) {
                    result.push({
                        paginationEllipsis: true,
                        page: '\u8230',
                        isCurrent: false
                    });
                } else {
                    if (deltaPage === 2) {
                        result.push({
                            page: page - 1,
                            paginationEllipsis: false,
                            isCurrent: false
                        });
                    }
                }
                result.push({
                    page: page,
                    paginationEllipsis: false,
                    isCurrent: (page === current)
                });
                lastPage = page;
            }
            return result;
        },
        selectIssuePage: function (page) {
            if (page === '+1') {
                if (this.issueCurrentPage + 1 <= this.issueLastPage) {
                    this.getIssues(this.issueCurrentPage + 1)
                }
            } else if (page === '-1') {
                if (this.issueCurrentPage - 1 >= 1) {
                    this.getIssues(this.issueCurrentPage - 1)
                }
            } else {
                this.getIssues(parseInt(page))
            }
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
