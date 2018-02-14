var data = {
    isLogin: false,
    greeting: '',
    search: '',
    downList: [],
    comments: [],
    reply: '',
    errMsg: '',
    errMsgLink: '',
    content: '',
    name: '',
    contact: '',
    isCommentModalShow: false,
};
var vm = new Vue({
    el: '#root',
    data: data,
    mounted: function () {
        this.getList();
        this.getUserInfo();
        this.getComments();
    },
    computed: {
        searchDownList: function () {
            var searchRegExp = new RegExp(data.search, 'i');
            var result = [];
            for (var i = 0; i < data.downList.length; ++i) {
                var item = data.downList[i];
                if (item.name.match(searchRegExp) || item.description.match(searchRegExp)) {
                    result.push(item);
                }
            }
            return result;
        }
    },
    methods: {
        getUserInfo: function () {
            axios({
                method: 'get',
                url: '/oauth/user',
            })
            .then(function (response) {
                data.isLogin = response.data.code === 200;
                data.greeting = '你好，' + response.data.username;
            });
        },
        getList: function () {
            axios({
                method: 'get',
                url: '/list/',
                headers: {'x-Requested-With': 'XMLHttpRequest'}
            })
            .then(function (response) {
                if (response.data.code === 200) {
                    data.downList = response.data.data;
                } else {
                    data.errMsg = response.data.msg;
                    data.errMsgLink = response.data.url;
                }
            });
        },
        getComments: function () {
            axios({
                method: 'get',
                url: '/comment',
            })
            .then(function (response) {
                if (response.data.code === 200) {
                    data.comments = response.data.data.comment;
                } else {
                    data.comments = [];
                }
            });
        },
        parseComment: function (comment) {
            if (comment.is_admin) {
                return '管理员: ' + comment.content;
            } else {
                return '我: ' + comment.content;
            }
        },
        saveComment: function () {
            axios({
                method: 'post',
                url: '/comment',
                data: {
                    content: data.reply
                }
            })
            .then(function (response) {
                if (response.data.code === 200) {
                    alert('提交评论成功！');
                    vm.getComments();
                    data.reply = '';
                } else {
                    alert('失败：' + response.data.err_msg);
                }
            });
        },
        showCommentModal: function() {
            data.isCommentModalShow = true;
        },
        hideCommentModal: function () {
            data.isCommentModalShow = false;
        },
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
