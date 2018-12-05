/* eslint-disable */
<template>
    <div style="text-align:center;padding-bottom: 50px">
        <div class="demo-table-settings">
            <h2 style="padding-top: 10px" >{{userInfo.name}} - 已有失物</h2>
            <br>
            <Row type="flex" justify="center" class="code-row-bg">
                <Col span="6">
                <mu-switch labelLeft v-model="switchTabCard" label="仅看证件类"/>
                </Col>
                <Col span="6">
                <mu-switch labelLeft v-model="switchTabNoCard" label="仅看非证件类"/>
                </Col>
                <Col span="6">
                <Input v-model="search" clearable placeholder="进行搜索..."
                       style="width: 150px;float: right;padding-right: 5px"></Input></Col>
                <Button type="ghost" shape="circle" icon="ios-search" @click="search_data()">搜索</Button>
            </Row>
        </div>
        <br>
        <!--@on-row-click="rowClick"-->
        <!--client-->
        <Table ref="table" v-if="switchTabCard === true && switchTabNoCard === false && isClient" :columns="client"
               :loading="this.loading" :data="Cards"></Table>
        <Table ref="table" v-else-if="switchTabNoCard === true && switchTabCard === false && isClient" :columns="client"
               :loading="this.loading" :data="NoCards"></Table>
        <Table ref="table" v-else-if="((switchTabNoCard === true && switchTabCard === true)||
                (switchTabNoCard === false && switchTabCard === false)) && isClient" :columns="client"
               :loading="this.loading" :data="all"></Table>

        <!--admin-->
        <Table ref="table" v-if="switchTabCard === true && switchTabNoCard === false && !isClient" :columns="admin"
               :loading="this.loading" :data="Cards"></Table>
        <Table ref="table" v-else-if="switchTabNoCard === true && switchTabCard === false && !isClient" :columns="admin"
               :loading="this.loading" :data="NoCards"></Table>
        <Table ref="table" v-else-if="((switchTabNoCard === true && switchTabCard === true)||
                (switchTabNoCard === false && switchTabCard === false)) && !isClient" :columns="admin"
               :loading="this.loading" :data="all"></Table>

        <mu-dialog :open="dialogDetail" title="详细">
            <mu-table :selectable="false" :enableSelectAll="false" :fixedHeader="true"
                      :multiSelectable="false" ref="table" :height="height"
                      style="margin: 40px; text-align: center">
                <mu-tbody>
                    <mu-tr>
                        <mu-td>失物名称</mu-td>
                        <mu-td>{{choice.lostType}}</mu-td>
                    </mu-tr>
                    <mu-tr v-if="choice.stuName !== null">
                        <mu-td>姓名</mu-td>
                        <mu-td>{{choice.stuName}}</mu-td>
                    </mu-tr>
                    <mu-tr v-if="choice.stuNumber !== null">
                        <mu-td>学号</mu-td>
                        <mu-td>{{choice.stuNumber}}</mu-td>
                    </mu-tr>
                    <mu-tr v-if="choice.academy !== null">
                        <mu-td>学院</mu-td>
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
                    <mu-tr>
                        <mu-td>领取地点</mu-td>
                        <mu-td>{{choice.getPlace}}</mu-td>
                    </mu-tr>
                    <mu-tr>
                        <mu-td>备注</mu-td>
                        <mu-td>{{choice.remarks}}</mu-td>
                    </mu-tr>
                    <mu-tr v-if="choice.ImgPath !== null">
                        <mu-td>失物图片</mu-td>
                        <mu-td><a :href="choice.ImgPath" target="_blank"><img v-bind:src="choice.ImgPath"
                        style="height: 100px;width: 100px"></a>
                        </mu-td>
                    </mu-tr>
                </mu-tbody>
            </mu-table>
            <mu-flat-button label="确定" slot="actions" primary @click="Close()"/>
        </mu-dialog>

        <mu-dialog :open="dialogEdit" title="修改信息">
            <myform v-bind:item="itemEdit" :academys-info="this.academysInfo" :places-info="this.placesInfo"></myform>
            <mu-flat-button label="关闭" slot="actions" primary @click="Close()"/>
        </mu-dialog>

        <mu-dialog :open="dialogTaken" title="添加领取人信息">
            <setRecipient :academys-info="this.academysInfo" v-bind:theitem="itemTaken"></setRecipient>
            <mu-flat-button label="关闭" slot="actions" primary @click="Close()"/>
        </mu-dialog>
        <br>

        <Button v-if="!isClient" type="primary" size="large" @click="exportData(1)"> 导出当前所显示的数据</Button>

        <div style="float: right;">
            <Page :total="this.total" :page="this.page" @on-change="changePage"></Page>
        </div>
        <Modal v-model="visible" title="查看图片">
            <img :src=visibleIMG style="width: 100%">
        </Modal>
    </div>
</template>
<script>
    import myform from './adminLost_set_table.vue'
    import setRecipient from './adminLost_set_recipient.vue'
    export default {
        data () {
            return {
                dialogEdit: false,
                dialogTaken: false,
                dialogDetail: false,

                manageData: {},
                all: [],
                Cards: [],
                NoCards: [],
                visible: false,
                visibleIMG: '',
                search: '',

                switchTabCard: false,
                switchTabNoCard: false,
                msg: '',
                height: '600px',
                itemEdit: '',
                itemTaken: {},
                choice: {},
                page: 1,
                total: 0,
                loading: false,

                client: [
                    {
                        title: '失物名称',
                        key: 'lostType',
                        align: 'center'
                    },
                    {
                        title: '上传时间',
                        key: 'created_at',
                        width: 99,
                        align: 'center'
                    },
                    {
                        title: '姓名',
                        key: 'stuName',
                        align: 'center'
                    },
                    {
                        title: '学号',
                        key: 'stuNumber',
                        align: 'center'
                    },
                    {
                        title: '图片',
                        key: 'ImgPath',
                        width: 100,
                        render: (h, params) => {
                            if (params.row.ImgPath !== null) {
                                return h('img', {
                                    attrs: {
                                        'src': params.row.ImgPath
                                    },
                                    style: {
                                        height: '100px',
                                        width: '100px'
                                    },
                                    on: {
                                        click: () => {
                                            this.showIMG('http://127.0.0.1:1024/' + params.row.ImgPath)
                                        }
                                    }
                                })
                            }
                        }
                    },
                    {
                        title: '领取地点',
                        key: 'getPlace'
                    },
                    {
                        title: '备注',
                        width: 250,
                        key: 'remarks'
                    },
                    {
                        title: '更多',
                        key: 'action',
                        width: 70,
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'success',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
//                                            this.editLost(params.row)
                                            this.detail(params.row)
                                        }
                                    }
                                }, '查看')
                            ])
                        }
                    }
                ],
                admin: [
                    {
                        title: '失物名称',
                        key: 'lostType',
                        align: 'center'
                    },
                    {
                        title: '上传时间',
                        key: 'created_at',
                        align: 'center'
                    },
                    {
                        title: '姓名',
                        key: 'stuName',
                        align: 'center'
                    },
                    {
                        title: '学号',
                        key: 'stuNumber',
                        align: 'center'
                    },
                    {
                        title: '图片',
                        key: 'ImgPath',
                        render: (h, params) => {
                            if (params.row.ImgPath !== null) {
                                return h('a', {
                                    attrs: {
                                        'href': '/'+params.row.ImgPath,
                                        'target': '_blank'
                                    }
                                }, [h('img', {
                                    attrs: {
                                        'src': '/'+params.row.ImgPath
                                    },
                                    style: {
                                        height: '100px',
                                        width: '100px'
                                    }
                                })
                                ])
                            }
                        }
                    },
                    {
                        title: '备注',
                        key: 'remarks'
                    },
                    {
                        title: '公示日期',
                        key: 'showTime'
                    },
                    {
                        title: '操作',
                        key: 'action',
                        width: 150,
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
//                                            this.showIMG(params.row)
                                            this.editLost(params.row)
                                        }
                                    }
                                }, '修改'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.changeTaken(params.row)
                                        }
                                    }
                                }, '设为领取')
                            ])
                        }
                    }
                ]
            }
        },
        props: ['isClient','userInfo','selectSchool', "academysInfo", "placesInfo"],
        components: {
            myform: myform,
            setRecipient: setRecipient
        },
        created: function () {
            this.get_data()
        },
        methods: {
            handleClick (newIndex) {
            },
            editLost (item) {
                this.itemEdit = item
                this.dialogEdit = true
            },
            changeTaken (item) {
                this.itemTaken = item
                this.dialogTaken = true
            },
            Close () {
                this.dialogEdit = false
                this.dialogTaken = false
                this.dialogDetail = false
            },
            exportData (type) {
                if (type === 1) {
                    this.$refs.table.exportCsv({
                        filename: 'The original data'
                    })
                }
            },
            showIMG (imgPath) {
                this.visible = true
                this.visibleIMG = imgPath
            },
            detail (item) {
                if(item.ImgPath){
                    item.ImgPath = '/'+item.ImgPath;
                }
                this.choice = item
                this.dialogDetail = true
            },
            search_data () {
                if (this.search !== ''){
                    var that = this
                    this.axios({
                        method: 'get',
                        url: '/api/search/'+this.search+'/'+(this.page-1)*10+'/10/'+this.userInfo.name,
                    }).then(function (res) {
                        console.log(res['data'])
                        that.all = [];
                        that.Cards = [];
                        that.NoCards = [];
                        that.total = res.data[1]
                        that.manageData = res.data[0]
                        for (let x in that.manageData) {
                            if (that.manageData[x]['taken'] === 0) {
                                that.all.push(that.manageData[x])
                            }
                        }
                        for (let x in that.all) {
                            that.all[x]['created_at'] = new Date(Date.parse(that.all[x]['created_at'])).toLocaleDateString()
                            if (that.all[x]['isCard'] === 1) {
                                that.Cards.push(that.all[x])
                            }
                            if (that.all[x]['isCard'] === 0) {
                                that.NoCards.push(that.all[x])
                            }
                        }
                    }).catch(function (err) {
                        console.log(err);
                    })
                }else {
                    this.get_data();
                }
            },
            get_data () {
                var that = this;
                this.loading=true
                setTimeout(function () {
                    that.loading=false
                },100)
                this.all = [];
                this.Cards = [];
                this.NoCards = [];
                this.axios({
                    method: 'get',
                    url: '/api/getLostData/'+(this.page-1)*10+'/10/'+this.userInfo.name
                }).then(function (res) {
                    that.total = res.data[1]
                    that.manageData = res.data[0]
                    for (let x in that.manageData) {
                        if (that.manageData[x]['taken'] === 0) {
                            that.all.push(that.manageData[x])
                        }
                    }
                    for (let x in that.all) {
                        that.all[x]['created_at'] = new Date(Date.parse(that.all[x]['created_at'])).toLocaleDateString()
                        if (that.all[x]['isCard'] === 1) {
                            that.Cards.push(that.all[x])
                        }
                        if (that.all[x]['isCard'] === 0) {
                            that.NoCards.push(that.all[x])
                        }
                    }
                }).catch(function (err) {
                    console.log(err)
                })
                this.$store.dispatch("setLost",this.all,this.Cards,this.NoCards);
            },
            changePage (page) {
                this.page = page
                this.loading=true
                var that = this;
                setTimeout(function () {
                    that.loading=false
                },100)
                this.get_data()
            }
        },
        watch: {
            userInfo: function () {
                this.get_data()
            },
            placesInfo: function () {
                console.log(this.placesInfo)
            }
        },
    }
</script>
<style>
    .ivu-page-item-active {
        background-color:#19be6b;
        border-color:#19be6b;
    }
</style>
