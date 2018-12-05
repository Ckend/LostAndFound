/* eslint-disable */
<template>
    <div style="text-align:center;min-height: 760px;">
        <div class="demo-table-settings">
            <h2 style="padding-top: 10px">石牌校区 - 寻物启事</h2>
            <br>
            <Row type="flex" justify="center" class="code-row-bg">
                <Col span="6">
                <mu-switch labelLeft v-model="switchTabCard" label="仅看证件类"/>
                </Col>
                <Col span="6">
                <mu-switch labelLeft v-model="switchTabNoCard" label="仅看非证件类"/>
                </Col>
                <Col span="4">
                <Input v-model="search" clearable placeholder="进行搜索..."
                       style="width: 150px;float: right;padding-right: 5px"></Input></Col>
                <Button type="ghost" shape="circle" icon="ios-search" @click="search_data()">搜索</Button>

                <Col span="4">
                <Button type="info" @click="uploadFound()">上传寻物启事</Button>
                </Col>
            </Row>
        </div>
        <br>

        <Table ref="table" v-if="switchTabCard === true && switchTabNoCard === false " :columns="admin"
               :data="Cards"></Table>
        <Table ref="table" v-else-if="switchTabNoCard === true && switchTabCard === false " :columns="admin"
               :data="NoCards"></Table>
        <Table ref="table" v-else-if="((switchTabNoCard === true && switchTabCard === true)||
                (switchTabNoCard === false && switchTabCard === false)) " :columns="admin"
               :data="all"></Table>

        <mu-dialog :open="dialogDetail" title="详细">
            <mu-table :selectable="false" :enableSelectAll="false" :fixedHeader="true"
                      :multiSelectable="false" ref="table" :height="height"
                      style="margin: 40px; text-align: center">
                <mu-tbody>
                    <mu-tr>
                        <mu-td>寻物标题</mu-td>
                        <mu-td>{{choice.lostType}}</mu-td>
                    </mu-tr>
                    <mu-tr>
                        <mu-td>丢失地点</mu-td>
                        <mu-td>{{choice.stuName}}</mu-td>
                    </mu-tr>
                    <mu-tr>
                        <mu-td>丢失时间</mu-td>
                        <mu-td>{{new Date(Date.parse(choice.pickTime)).toLocaleDateString()}}</mu-td>
                    </mu-tr>
                    <mu-tr>
                        <mu-td>失物详情</mu-td>
                        <mu-td>{{choice.remarks}}</mu-td>
                    </mu-tr>
                    <mu-tr>
                        <mu-td>联系方式</mu-td>
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
            <myform v-bind:item="itemEdit"></myform>
            <mu-flat-button label="关闭" slot="actions" primary @click="Close()"/>
        </mu-dialog>

        <mu-dialog :open="dialogTaken" title="添加领取人信息">
            <setRecipient v-bind:theitem="itemTaken"></setRecipient>
            <mu-flat-button label="关闭" slot="actions" primary @click="Close()"/>
        </mu-dialog>

        <mu-dialog :open="dialogUploadFound" title="添加寻物启事">
            <uploadFound></uploadFound>
            <mu-flat-button label="关闭" slot="actions" primary @click="Close()"/>
        </mu-dialog>
        <br>


        <Button v-if="!isClient" type="primary" size="large" @click="exportData(1)"> 导出当前所显示的数据</Button>

        <Modal v-model="visible" title="查看图片">
            <img :src=visibleIMG style="width: 100%">
        </Modal>
    </div>
</template>
<script>
    import myform from './adminLost_set_table.vue'
    import setRecipient from './adminLost_set_recipient.vue'
    import uploadFound from './adminFound.vue'
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
                dialogUploadFound: false,
                admin: [
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
                        render: (h, params) => {
                            if (params.row.ImgPath !== null) {
                                return h('a', {
                                    attrs: {
                                        'href': '/' + params.row.ImgPath,
                                        'target': '_blank'
                                    }
                                }, [h('img', {
                                    attrs: {
                                        'src': '/' + params.row.ImgPath
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
        props: ['userInfo'],
        components: {
            myform: myform,
            setRecipient: setRecipient,
            uploadFound: uploadFound
        },
        created: function () {
            let that = this
            this.manageData.data = {}
            this.axios({
                method: 'get',
                url: '/api/getData'
            }).then(function (res) {
                that.manageData = res
                for (let x in that.manageData.data) {
                    if (that.manageData.data[x]['taken'] === 0) {
                        that.all.push(that.manageData.data[x])
                    }
                }
                for (let x in that.all) {
                    that.all[x]['created_at'] = new Date(Date.parse(that.all[x]['created_at'])).toLocaleDateString()
                }
            }).catch(function (err) {
                console.log(err)
            })
            this.$store.dispatch("setLost", this.all, this.Cards, this.NoCards);
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
                this.dialogUploadFound = false
            },
            showIMG (imgPath) {
                this.visible = true
                this.visibleIMG = imgPath
            },
            detail (item) {
                if (item.ImgPath) {
                    item.ImgPath = '/' + item.ImgPath;
                }
                this.choice = item
                this.dialogDetail = true
            },
            search_data () {
                if (this.search !== '') {
                    const Box = new FormData();
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    Box.append('search_words', this.search);
                    this.axios({
                        method: 'post',
                        url: '/api/searchData',
                        data: Box,
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    }).then(function (res) {
                        console.log(res['data'])
                    }).catch(function (err) {
                        console.log(err);
                    })
                }
            },
            uploadFound () {
                this.dialogUploadFound = true
            }
        }
    }
</script>
<style>
</style>
