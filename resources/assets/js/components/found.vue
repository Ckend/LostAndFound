<template>
    <div class="layout">
        <div class="header">
            <div class="logo">
                华南师范大学 - 寻物启事
            </div>
            <div class="nav">
                <mu-tabs :value="activeTab" @change="handleTabChange" class="tab">
                    <mu-tab value="tab1" title="石牌校区"/>
                    <mu-tab value="tab2" title="大学城校区"/>
                    <mu-tab value="tab3" title="南海校区"/>
                    <mu-tab value="link" href="/" title="失物招领"/>
                </mu-tabs>
            </div>
        </div>

        <mu-row gutter>
            <mu-col width="100" tablet="23" desktop="12"></mu-col>
            <mu-col width="100" tablet="64" desktop="75">
                <div class="content">
                    <br/>
                    <div class="body" style="opacity: 0.88">
                        <show-table isClient="true"></show-table>
                    </div>
                </div>
            </mu-col>
            <mu-col width="100" tablet="23" desktop="13"></mu-col>
        </mu-row>
        <div class="footer">
            华南师范大学 网络协会&校学生会 ©2017 Created by Ckend & lvj
        </div>

    </div>
</template>
<script>
    import showTable from './adminFound_table.vue'
    export default {
        data () {
            return {
                activeTab: 'tab1'
            }
        },
        components: {
            showTable: showTable
        },
        methods: {
            handleTabChange (val) {
                this.activeTab = val
                if (val !== 'tab1' && val !== 'link') {
                    this.$Message.warning('抱歉，目前仅支持石牌校区')
                    this.activeTab = 'tab1'
                }
            }
        },
        beforeCreate: function () {
            document.querySelector('body').setAttribute('style', 'background-image:url("/images/bg.jpg");background-repeat:no-repeat;background-size:cover;background-attachment: fixed;');
        }
    }
</script>
<style scoped>
    .layout {
    }

    .header {
        background: linear-gradient(left, #536373, #5c4c44);
    }

    .logo {
        font-size: 24px;
        color: white;
        display: inline-block;
        padding: 10px 20px;
    }

    .nav {
        display: inline-block;
        width: calc(100% - 600px);
        margin: 0 auto;
    }

    .tab {
        margin: 0 auto;
        width: 400px;
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
        min-height: 500px;
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
