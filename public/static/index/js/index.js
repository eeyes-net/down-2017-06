var data = {
    search: '',
    downList: [],
    errMsg: '',
    errMsgLink: '',
    content: '',
    name: '',
    contact: '',
    isModalShow: false,
    isCommentModalShow: false,
};
var vm = new Vue({
    el: '#root',
    data: data,
    mounted: function () {
        this.getList();
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
        saveIssue: function () {
            axios({
                method: 'post',
                url: '/issue/',
                data: {
                    content: data.content,
                    name: data.name,
                    contact: data.contact
                }
            })
            .then(function (response) {
                alert(response.data.msg);
                if (response.data.code === 200) {
                    vm.hideModal();
                }
            });
        },
        showModal: function () {
            data.isModalShow = true;
        },
        hideModal: function () {
            data.isModalShow = false;
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
