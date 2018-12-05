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
                    <!--<mu-card-actions>-->
                    <Button style="float: right;" type="ghost" shape="circle" icon="ios-search" @click="search_data()">搜索</Button>
                    <Input v-model="search" clearable placeholder="进行搜索..."
                           style="width: 150px;float: right;"></Input>
                    <mu-paper class="demo-paper" :zDepth="1">
                        <mu-table :selectable="false" :enableSelectAll="false" :fixedHeader="true"
                                  :multiSelectable="false" ref="table" :height="height"
                                  style="margin: 40px; text-align: center">
                            <mu-thead slot="header">
                                <mu-tr>
                                    <mu-th>失物名称</mu-th>
                                    <mu-th>领取时间</mu-th>
                                    <mu-th>领取人<br>学号</mu-th>
                                    <mu-th>学院</mu-th>
                                    <mu-th>手机号</mu-th>
                                    <mu-th>操作</mu-th>
                                </mu-tr>
                            </mu-thead>
                            <mu-tbody>
                                <mu-tr v-for="item in all">
                                    <mu-td>{{item.lostType}}</mu-td>
                                    <mu-td>{{new Date(Date.parse(item.takenTime)).toLocaleDateString()}}</mu-td>
                                    <mu-td>{{item.takenName}}<br>{{item.takenNumber}}</mu-td>
                                    <mu-td>{{item.takenAcademy}}</mu-td>
                                    <mu-td>{{item.takenPhone}}</mu-td>
                                    <mu-td>
                                        <mu-flat-button label="详细" class="demo-flat-button" primary
                                                        style="height: 26px;min-width: 60px" @click="detail(item)"/>
                                        <br>
                                        <mu-flat-button label="删除" class="demo-flat-button" secondary
                                                        style="height: 26px;min-width: 60px"
                                                        @click="showdialog2(item)"/>
                                    </mu-td>
                                </mu-tr>
                            </mu-tbody>
                        </mu-table>
                        <div style="float:right;">
                            <Page :total="this.total" :page="this.page" @on-change="changePage"></Page>
                        </div>
                    </mu-paper>
                    <mu-dialog :open="dialog" title="详细">
                        <mu-table :selectable="false" :enableSelectAll="false" :fixedHeader="true"
                                  :multiSelectable="false" ref="table" :height="height"
                                  style="margin: 40px; text-align: center">
                            <mu-tbody>
                                <mu-tr>
                                    <mu-td>失物名称</mu-td>
                                    <mu-td>{{choice.lostType}}</mu-td>
                                </mu-tr>
                                <mu-tr>
                                    <mu-td>领取时间</mu-td>
                                    <mu-td>{{new Date(Date.parse(choice.takenTime)).toLocaleDateString()}}</mu-td>
                                </mu-tr>
                                <mu-tr>
                                    <mu-td>领取人姓名</mu-td>
                                    <mu-td>{{choice.takenName}}</mu-td>
                                </mu-tr>
                                <mu-tr>
                                    <mu-td>领取人学号</mu-td>
                                    <mu-td>{{choice.takenNumber}}</mu-td>
                                </mu-tr>
                                <mu-tr>
                                    <mu-td>领取人的学院</mu-td>
                                    <mu-td>{{choice.takenAcademy}}</mu-td>
                                </mu-tr>
                                <mu-tr>
                                    <mu-td>领取人的电话号码</mu-td>
                                    <mu-td>{{choice.takenPhone}}</mu-td>
                                </mu-tr>
                                <mu-tr>
                                    <mu-td>被领取人姓名</mu-td>
                                    <mu-td>{{choice.stuName}}</mu-td>
                                </mu-tr>
                                <mu-tr>
                                    <mu-td>被领取人学号</mu-td>
                                    <mu-td>{{choice.stuNumber}}</mu-td>
                                </mu-tr>
                                <mu-tr>
                                    <mu-td>被领取人的学院</mu-td>
                                    <mu-td>{{choice.academy}}</mu-td>
                                </mu-tr>
                                <mu-tr>
                                    <mu-td>捡到的地点</mu-td>
                                    <mu-td>{{choice.pickLocation}}</mu-td>
                                </mu-tr>
                                <mu-tr>
                                    <mu-td>捡到的时间</mu-td>
                                    <mu-td>{{new Date(Date.parse(choice.pickTime)).toLocaleDateString()}}</mu-td>
                                </mu-tr>
                                <mu-tr v-if="choice.ImgPath">
                                    <mu-td>失物图片</mu-td>
                                    <mu-td><a :href="choice.ImgPath" target="_blank"><img v-bind:src="choice.ImgPath"
                                                                                          style="height: 100px;width: 100px"></a>
                                    </mu-td>
                                </mu-tr>
                            </mu-tbody>
                        </mu-table>
                        <mu-flat-button label="确定" slot="actions" primary @click="close"/>
                    </mu-dialog>
                    <mu-dialog :open="dialog2" title="删除">
                        {{msg}}
                        <mu-flat-button label="取消" slot="actions" primary @click="closeDialog2()"/>
                        <mu-flat-button label="确定" slot="actions" primary @click="deleteTaken()"/>
                    </mu-dialog>
                    <div class="footer">
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
    export default {
        data () {
            return {
                activeTab: 'tab1',
                activeList: 'list1',
                bottomNav: 'favorites',
                manageData: {},
                all: [],
                height: '600px',
                dialog: false,
                dialog2: false,
                msg: '确定要删除吗？',
                choice: '',
                takenDelete: '',
                total: 0,
                page: 1,
                search: '',
                schoolName: ['school_name'],
                userInfo: '',
                placesInfo: '',
                academysInfo: '',
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
                that.get_data();
            }).catch(function (err) {
                console.log(err)
                console.log( '发生了错误，请确认输入无误')
            })
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
                    window.location.href = "/admin";
                }
            },
            handleClick (newIndex) {
            },
            close () {
                this.dialog = false
            },
            detail (item) {
                if(item.ImgPath){
                    item.ImgPath = '/'+item.ImgPath;
                }
                this.choice = item
                this.dialog = true
            },
            showdialog2 (item) {
                this.dialog2 = true
                this.takenDelete = item
            },
            deleteTaken () {
                const Box = new FormData();
                Box.append('_method', 'PUT');

                Box.append('id', this.takenDelete.id);
                Box.append('delete', 1);
                var that = this;
                this.axios({
                    method: 'post',
                    url: '/admin/shipai/api/uploadLosts/' + this.takenDelete.id,
                    data: Box,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(function (res) {
                    console.log(res['data'])
                }).catch(function (err) {
                    console.log(err)
                })
                this.dialog2 = false;
                location.reload()
            },
            closeDialog2 () {
                this.dialog2 = false;
                this.takenDelete = ''
            },
            imgPath(img){
                return '/'+img;
            },
            get_data () {
                let that = this
                this.axios({
                    method: 'get',
                    url: '/api/getRecipientData/'+(this.page-1)*15+'/15/'+this.userInfo.name
                }).then(function (res) {
                    that.manageData = res.data[0]
                    that.total = res.data[1]
                    for (let x in that.manageData) {
                        if (that.manageData[x]['taken'] === 1 && that.manageData[x]['delete'] === 0) {
                            that.all.push(that.manageData[x])
                        }
                    }
                }).catch(function (err) {
                    console.log(err)
                })
            },
            changePage (page) {
                this.page = page
                this.loading=true
                var that = this;
                setTimeout(function () {
                    that.loading=false
                },100)
                this.get_data()
            },
            search_data () {
                if (this.search != ''){
                    this.all = [];
                    let that = this;
                    this.axios({
                        method: 'get',
                        url: '/api/searchRecipient/'+this.search+'/'+(this.page-1)*15+'/15/'+userInfo.name
                    }).then(function (res) {
                        that.manageData = res.data[0]
                        that.total = res.data[1]
                        console.log(that.manageData)
                        for (let x in that.manageData) {
                            if (that.manageData[x]['taken'] === 1 && that.manageData[x]['delete'] === 0) {
                                that.all.push(that.manageData[x])
                            }
                        }
                    }).catch(function (err) {
                        console.log(err)
                    })
                }else{
                    this.get_data();
                }
            }
        },
        beforeCreate: function () {
            document.querySelector('body').setAttribute('style','background-color: #3A569C;');
        }
    }
</script>
<style scoped>
    @import 'http://cdn.bootcss.com/material-design-icons/3.0.1/iconfont/material-icons.css';

    /*.layout {*/
        /*background-color: #3A569C;*/
        /*position: absolute;*/
    /*}*/

    .header {
        background-color: #7e57c2;
    }

    .logo {
        font-size: 24px;
        color: white;
        display: inline-block;
        padding: 10px 20px;
    }

    .nav {
        display: inline-block;
        width: calc(100% - 150px);
        margin: 0 auto;
    }

    .tab {
        margin: 0 auto;
        width: 400px;
        background-color: rgba(0, 0, 0, 0);
    }

    .content {
        overflow: hidden;
        width: 90%;
        margin: 0 auto;
    }

    .body {
        background-color: white;
        /*min-height: 500px;*/
        /*background-size: 100%;*/
    }

    .footer {
        padding: 20px 0;
        text-align: center;
    }

    .demo-raised-button {
        margin: 12px;
    }

    .demo-grid div[class*="col-"] {
        background: #fff;
        text-align: center;
        color: #000;
        border: 1px solid #ddd;
        padding: 8px;
        margin-bottom: 8px;
    }
    .demo-paper {
        padding-bottom: 40px;
    }
</style>
