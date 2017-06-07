var data = {
    title: '',
    content: '',
    name: '',
    contact: '',
    isModalShow: false
};
var vm = new Vue({
    el: '#root',
    data: data,
    methods: {
        saveIssue: function () {
            axios({
                method: 'post',
                url: '/issue/',
                data: {
                    title: data.title,
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
        }
    }
});
