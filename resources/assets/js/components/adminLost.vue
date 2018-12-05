<template>
    <div class="layout">
        <mu-row gutter>
            <mu-col width="15" tablet="15" desktop="15"></mu-col>
            <mu-col width="70" tablet="70" desktop="70" style="background-color: white;margin: 5px">
                <div class="content">
                    <mu-bottom-nav :value="bottomNav" @change="handleChange">
                        <mu-bottom-nav-item value="recents" title="提交失物" icon="restore"/>
                        <mu-bottom-nav-item value="favorites" title="领取人信息" icon="favorite"/>
                    </mu-bottom-nav>
                    <myform :user-info="this.userInfo" :academys-info="this.academysInfo" :places-info="this.placesInfo"></myform>
                    <br>
                    <showTable :user-info="this.userInfo" :academys-info="this.academysInfo" :places-info="this.placesInfo"></showTable>
                    <div class="footer" v-cloak>
                         {{schoolName[0].school_name}} ©2017 Created by Ckend & lvj
                        <span style="padding-left: 50px">
                            <Button type="primary" onclick="window.location.href='/admin/logout'">退出登录</Button>
                        </span>
                    </div>
                </div>
            </mu-col>
            <mu-col width="15" tablet="15" desktop="15"></mu-col>
        </mu-row>
    </div>
</template>

<script>
    import myform from './adminLost_set_table.vue'
    import showTable from './adminLost_table.vue'
    export default {
        data () {
            return {
                activeTab: 'tab1',
                activeList: 'list1',
                bottomNav: 'recents',
                schoolName: ['school_name'],
                userInfo: '',
                placesInfo: '',
                academysInfo: '',
                total: 500,
                current: 1,
                value: 1
            }
        },
        components: {
            myform: myform,
            showTable: showTable
        },
        methods: {
            handleTabChange (val) {
                this.activeTab = val
            },
            handleListChange (val) {
                this.activeList = val
            },
            handleChange (val) {
                if (val === this.bottomNav) {
                } else {
                    window.location.href="admin/taken";
                }
            },
            dropChange (value) {
                this.value = value
            }
        },
        created: function () {
            let that = this;
            this.axios({
                method: 'get',
                url: '/admin/getBranchInfo',
            }).then(function (res) {
                that.userInfo = res['data'][0];
                that.academysInfo = res['data'][1];
                that.placesInfo = res['data'][2];
                that.schoolName = res['data'][3];
            }).catch(function (err) {
                console.log(err)
                console.log( '发生了错误，请确认输入无误')
            })
        },
        beforeCreate: function () {
            document.querySelector('body').setAttribute('style','background-color: #5D4B7B;');
        }
    }
</script>
<style>
    @import 'https://cdn.bootcss.com/material-design-icons/3.0.1/iconfont/material-icons.css';

    .footer{
        padding: 20px 0;
        text-align: center;
    }

    .content{
        overflow: hidden;
        width: 90%;
        margin: 0 auto;
    }

    [v-cloak] {
        display: none;
    }
</style>
