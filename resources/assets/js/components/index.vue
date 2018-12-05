<template>
    <div class="layout">
        <div class="header">

            <div class="logo">
               {{this.schoolName}} - 失物招领
            </div>
            <div class="nav">
                <mu-tabs :value="activeTab" @change="handleTabChange" class="tab">
                    <mu-tab style="display:inline-block; padding-right: 7px;padding-left: 7px" class="my-tab" v-for="school in userAll" :value="school['name']" :title="school['name']"/>
                </mu-tabs>
                <mu-tab v-if="this.found == '1'" style="float:right;" value="link" title="寻物启事"/>
            </div>
        </div>

        <mu-row gutter>
            <mu-col width="100" tablet="23" desktop="12"></mu-col>
            <mu-col width="100" tablet="64" desktop="75">

                <div class="content">
                    <br/>
                    <div class="body" style="opacity: 0.88">
                        <show-table isClient="true" :select-school="this.activeTab"></show-table>
                    </div>
                </div>
            </mu-col>
            <mu-col width="100" tablet="23" desktop="13"></mu-col>
        </mu-row>
        <div class="footer">
            {{this.schoolName}} ©2017 Created by Ckend & lvj
        </div>
    </div>
</template>
<script>
    import showTable from './index_table.vue'
    export default {
        data () {
            return {
                schoolName: '',
                userAll: [],
                activeTab: '',
                found: 0
            }
        },
        components: {
            showTable: showTable
        },
        methods: {
            handleTabChange (val) {
                this.activeTab = val;
                console.log(this.activeTab);
                if (val === 'link') {
                    this.$Message.warning('抱歉，目前寻物启事模块尚未开启')
                    this.activeTab = this.userAll[0]['name']
                }
            },
        },
        created: function () {
            let that = this;
            this.axios({
                method: 'get',
                url: '/api/getBase',
            }).then(function (res) {
                that.schoolName = res['data'][0]['school_name'];
                that.found = res['data'][0]['found'].toString();
                that.userAll = res['data'][1];
                that.activeTab = that.userAll[0]['name'];
            }).catch(function (err) {
                console.log(err)
                console.log( '发生了错误，请确认输入无误')
            })
        },
        beforeCreate: function () {
            document.querySelector('body').setAttribute('style','background-image:url("/images/bg2.jpg");background-repeat:no-repeat;background-size:cover;background-attachment: fixed;');
        }
    }
</script>
<style scoped>
    .layout {
        /*background-image: url("/images/bg2.jpg");*/
        /*background-repeat:no-repeat ;*/
        /*background-size:cover;*/
        /*background-attachment: fixed;*/
    }

    .header {
        background: linear-gradient(left, #255e6b, #407c6b);
    }

    .logo {
        font-size: 24px;
        color: white;
        display: inline-block;
        padding: 10px 20px;
    }

    .nav {
        display: inline-block;
        text-align: center;
        width: calc(100% - 600px);
        margin: 0 auto;
    }

    .tab {
        display:inline-block;
        margin: 0 auto;
        width: 290px;
        background-color: rgba(0, 0, 0, 0);
    }

    .content {
        width: 90%;
        margin: 0 auto;
    }

    .breadcrumb {
        margin: 10px 0;
    }

    .body {
        background-color: white;
        border-radius: 5px;

    }

    .footer {
        padding: 20px 0;
        text-align: center;
        color: white;
    }

    .demo-table-settings {
        width: 200px;
        overflow: hidden;
        margin: 20px auto 0px;
    }

    .mu-tab-link {
        font-size: 17px;
    }
</style>
