<template>
    <div class="layout">
        <mu-row gutter>
            <mu-col width="15" tablet="15" desktop="15"></mu-col>
            <mu-col width="70" tablet="70" desktop="70" style="background-color: white;margin: 5px">
                <h3 class="super-header">超级后台管理 <span style="float: right">
                    <Button type="primary" onclick="window.location.href='/admin/logout'">退出登录</Button>
                </span></h3>

                <div class="content">

                    <Form :model="formItem" :label-width="80">
                        <FormItem label="学校名称">
                            <Input v-model="formItem.schoolName" placeholder="请输入学校名称..."></Input>
                        </FormItem>
                        <FormItem label="开启微博同步">
                            <RadioGroup v-model="formItem.weibo_sync">
                                <Radio label="1"><span>是</span></Radio>
                                <Radio label="0"><span>否</span></Radio>
                            </RadioGroup>
                        </FormItem>
                        <div v-if="formItem.weibo_sync=='1'">
                            <FormItem label="微博账号">
                                <Input v-model="formItem.weibo_user" placeholder="请输入微博账号..."></Input>
                            </FormItem>
                            <FormItem label="微博密码">
                                <Input type="password" v-model="formItem.weibo_password" placeholder="请输入微博密码..."></Input>
                            </FormItem>
                            <FormItem label="微博开发者token">
                                <Input v-model="formItem.weibo_token" placeholder="请输入微博开发者token..."></Input>
                            </FormItem>
                        </div>
                        <FormItem label="开启企业号自动通知">
                            <RadioGroup v-model="formItem.autoInform_qiyehao">
                                <Radio label="1"><span>是</span></Radio>
                                <Radio label="0"><span>否</span></Radio>
                            </RadioGroup>
                        </FormItem>
                        <div v-if="formItem.autoInform_qiyehao == '1'">
                            <FormItem label="服务地址">
                                <Input v-model="formItem.SERVER_ADDR" placeholder="请输入服务地址..."></Input>
                            </FormItem>
                            <FormItem label="CLIENT_ID">
                                <Input v-model="formItem.CLIENT_ID" placeholder="请输入CLIENT_ID..."></Input>
                            </FormItem>
                            <FormItem label="CLIENT_SECRET">
                                <Input type="password" v-model="formItem.CLIENT_SECRET" placeholder="请输入CLIENT_SECRET..."></Input>
                            </FormItem>
                        </div>
                        <FormItem>
                            <Button type="primary" @click="base_save">提交</Button>
                            <Button type="ghost" style="margin-left: 8px">取消</Button>
                        </FormItem>
                    </Form>
                    <span>现有校区：</span>
                    <Table border :columns="columns" :data="formItem.branch_school"></Table>
                    <div class="add_branch">
                        <Button type="primary" @click="add_branch">增加校区</Button>
                    </div>
                    <!--修改学院-->
                    <mu-dialog :open="dialog" :scrollable="true" title="修改学院">
                        <Form ref="formDynamic" :model="formDynamic" :label-width="80" style="width: 300px">
                            <FormItem
                                    v-for="(item, index) in formDynamic.items"
                                    v-if="item.status"
                                    :key="index"
                                    :label="'学院 '"
                                    :prop="'items.' + index + '.value'"
                                    :rules="{required: true, message: '学院 ' + item.index +' 不能为空', trigger: 'blur'}">
                                <Row>
                                    <Col span="18">
                                    <Input type="text" v-model="item.value" placeholder="学院名称..."></Input>
                                    </Col>
                                    <Col span="4" offset="1">
                                    <Button type="ghost" @click="handleRemove(index)">删除</Button>
                                    </Col>
                                </Row>
                            </FormItem>
                            <FormItem>
                                <Row>
                                    <Col span="12">
                                    <Button type="dashed" long @click="handleAdd" icon="plus-round">增加学院</Button>
                                    </Col>
                                </Row>
                            </FormItem>
                            <FormItem>
                                <Button type="primary" @click="handleAcademySubmit('formDynamic')">提交</Button>
                                <Button type="ghost" @click="handleReset('formDynamic')" style="margin-left: 8px">重置</Button>
                                <Button type="ghost" @click="close" style="margin-left: 8px;float: right;">关闭</Button>
                            </FormItem>
                        </Form>
                    </mu-dialog>

                    <!--修改领取地点-->
                    <mu-dialog :open="dialog2" :scrollable="true" title="修改领取地点">
                        <Form ref="formDynamic2" :model="formDynamic2" :label-width="80" style="width: 300px">
                            <FormItem
                                    v-for="(item, index) in formDynamic2.items"
                                    v-if="item.status"
                                    :key="index"
                                    :label="'领取地点 '"
                                    :prop="'items.' + index + '.value'"
                                    :rules="{required: true, message: '领取地点 ' + item.index +' 不能为空', trigger: 'blur'}">
                                <Row>
                                    <Col span="18">
                                    <Input type="text" v-model="item.value" placeholder="领取地点..."></Input>
                                    </Col>
                                    <Col span="4" offset="1">
                                    <Button type="ghost" @click="handleRemove2(index)">删除</Button>
                                    </Col>
                                </Row>
                            </FormItem>
                            <FormItem>
                                <Row>
                                    <Col span="12">
                                    <Button type="dashed" long @click="handleAdd2" icon="plus-round">增加领取地点</Button>
                                    </Col>
                                </Row>
                            </FormItem>
                            <FormItem>
                                <Button type="primary" @click="handlePlaceSubmit('formDynamic2')">提交</Button>
                                <Button type="ghost" @click="handleReset('formDynamic2')" style="margin-left: 8px">重置</Button>
                                <Button type="ghost" @click="close" style="margin-left: 8px;float: right;">关闭</Button>
                            </FormItem>
                        </Form>
                    </mu-dialog>
                    <div class="footer">
                        ©2017 Created by <a href="https://huanxiangke.com"> Ckend</a>
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
                // 修改校区
                dialog: false,
                // 修改领取地点
                dialog2: false,
                index: 1,
                formDynamic: {
                    items: []
                },
                formDynamic2: {
                    items: []
                },
                academys: [],
                places: [],
                now_branch_school: '',
                // 主信息表单
                formItem: {
                    schoolName: '',
                    found: '0',
                    lost: '0',

                    weibo_sync: '0',
                    weibo_user: '',
                    weibo_password: '',
                    weibo_token: '',

                    autoInform_qiyehao: '0',
                    SERVER_ADDR: '',
                    CLIENT_ID: '',
                    CLIENT_SECRET: '',

                    branch_school: this.userInfo,
                },

                //校区表
                columns: [
                    {
                        title: '校区名称',
                        key: 'name',
                        render: (h, params) => {
                            if (params.row.name != 'admin'){
                                return h('div', [
                                    h('strong',  params.row.name)
                                ]);
                            }
                        }
                    },
                    {
                        title: '查看学院',
                        render: (h,params) => {
                            if (params.row.name != 'admin') {
                                return [h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.academy_edit(params.row.name)
                                        }
                                    }
                                }, '查看与修改')]
                            }
                        }
                    },
                    {
                        title: '查看领取地点',
                        render: (h,params) => {
                            if (params.row.name != 'admin') {
                                return [h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.place_edit(params.row.name)
                                        }
                                    }
                                }, '查看与修改')]
                            }
                        }
                    },
                    {
                        title: 'Action',
                        key: 'action',
                        width: 150,
                        align: 'center',
                        render: (h, params) => {
                            if (params.row.name != 'admin') {
                                return h('div', [
                                    h('Button', {
                                        props: {
                                            type: 'error',
                                            size: 'small'
                                        },
                                        on: {
                                            click: () => {
                                                this.remove(params.row.name)
                                            }
                                        }
                                    }, '删除')
                                ]);
                            }
                        }
                    }
                ]
            }
        },
        props:['userInfo'],
        methods: {
            academy_edit (branch_school) {
                this.now_branch_school = branch_school;
                let that = this;
                this.axios({
                    method: 'get',
                    url: '/admin/api/showAcademy/'+this.now_branch_school
                }).then(function (res) {
                    for (var i in res['data']){
                        var data = {
                            value: res['data'][i]['academy'],
                            status: 1
                        };
                        that.formDynamic.items.push(data);
                    }
                }).catch(function (err) {
                    console.log(err);
                });
                this.dialog = true;
            },
            place_edit (branch_school) {
                this.now_branch_school = branch_school;
                let that = this;
                this.axios({
                    method: 'get',
                    url: '/admin/api/showPlace/'+this.now_branch_school
                }).then(function (res) {
                    for (var i in res['data']){
                        var data = {
                            value: res['data'][i]['place'],
                            status: 1
                        };
                        that.formDynamic2.items.push(data);
                    }
                }).catch(function (err) {
                    console.log(err);
                });
                this.dialog2 = true;
            },
            close() {
                this.formDynamic.items = [];
                this.formDynamic2.items = [];
                this.dialog = false;
                this.dialog2 = false;
            },
            remove (branch_school) {
                this.now_branch_school = branch_school;
                const Box = new FormData();
                Box.append('branch_school', this.now_branch_school)
                let that = this;
                this.axios({
                    method: 'post',
                    url: '/admin/api/superDeleteBranch',
                    data: Box
                }).then(function (res) {
                    that.$Message.success('删除成功!');
                    location.reload()
                }).catch(function (err) {
                    console.log(err)
                    that.$Message.error('删除失败!');
                })
            },
            // 以下是修改学院所需要的函数
            handleAcademySubmit (name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        const Box = new FormData();
                        for (var i in this.formDynamic.items){
                            if (this.formDynamic.items[i]['status'] === 1){
                                this.academys.push(this.formDynamic.items[i]['value'])
                            }
                        }
                        console.log(this.academys);
                        Box.append('academys', this.academys);
                        Box.append('branch_school', this.now_branch_school)
                        let that = this;
                        this.axios({
                            method: 'post',
                            url: '/admin/api/superAcademy',
                            data: Box
                        }).then(function (res) {
                            console.log( '发送成功！')
                            that.$Message.success('成功!');
                        }).catch(function (err) {
                            console.log(err)
                            console.log( '发生了错误，请确认输入无误')
                            that.$Message.error('失败!');
                        })
                        this.academys = [];
                        this.close();
                    } else {
                    }
                })
            },
            // 以下是修改领取地点所需要的函数
            handlePlaceSubmit (name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        const Box = new FormData();
                        for (var i in this.formDynamic2.items){
                            if (this.formDynamic2.items[i]['status'] === 1){
                                this.places.push(this.formDynamic2.items[i]['value'])
                            }
                        }
                        console.log(this.places);
                        Box.append('places', this.places);
                        Box.append('branch_school', this.now_branch_school)
                        let that = this;
                        this.axios({
                            method: 'post',
                            url: '/admin/api/superPlace',
                            data: Box
                        }).then(function (res) {
                            console.log( '发送成功！')
                            that.$Message.success('成功!');
                        }).catch(function (err) {
                            console.log(err)
                            console.log( '发生了错误，请确认输入无误')
                            that.$Message.error('发生了错误，请确认输入无误');
                        })
                        this.places = [];
                        this.close();
                    } else {
                    }
                })
            },
            handleReset (name) {
                this.$refs[name].resetFields();
            },
            handleAdd () {
                this.index++;
                this.formDynamic.items.push({
                    value: '',
                    index: this.index,
                    status: 1
                });
            },
            handleRemove (index) {
                this.formDynamic.items[index].status = 0;
            },

            handleAdd2 () {
                this.index++;
                this.formDynamic2.items.push({
                    value: '',
                    index: this.index,
                    status: 1
                });
            },
            handleRemove2 (index) {
                this.formDynamic2.items[index].status = 0;
            },
            add_branch() {
                location.href = '/admin/logoutAndRegister';
            },
            base_save() {
                let that = this;
                this.axios({
                    method: 'post',
                    url: '/admin/api/superBaseSave',
                    data: this.formItem
                }).then(function (res) {
                    console.log( '发送成功！')
                    that.$Message.success('成功!');
                }).catch(function (err) {
                    console.log(err)
                    console.log( '发生了错误，请确认输入无误')
                    that.$Message.error('发生了错误，请确认输入无误');
                })
            }

        },
        created: function () {
            let that = this;
            this.axios({
                method: 'get',
                url: '/admin/api/getBase',
            }).then(function (res) {
                that.formItem.schoolName = res['data'][0]['school_name'];
                that.formItem.weibo_sync = res['data'][0]['sync_weibo'].toString();
                that.formItem.lost = res['data'][0]['lost'].toString();
                that.formItem.found = res['data'][0]['found'].toString();
                that.formItem.weibo_user = res['data'][0]['weibo_user'];
                that.formItem.weibo_password = res['data'][0]['weibo_password'];
                that.formItem.weibo_token = res['data'][0]['weibo_token'];

                that.formItem.autoInform_qiyehao = res['data'][0]['autoInform_qiyehao'].toString();
                that.formItem.SERVER_ADDR = res['data'][0]['SERVER_ADDR'];
                that.formItem.CLIENT_ID = res['data'][0]['CLIENT_ID'];
                that.formItem.CLIENT_SECRET = res['data'][0]['CLIENT_SECRET'];

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
    .add_branch{
        padding: 10px;
        text-align: center;
    }

    .footer{
        padding: 20px 0;
        text-align: center;
    }

    .content{
        overflow: hidden;
        width: 90%;
        margin: 0 auto;
    }

    .super-header{
        padding: 15px;
    }
</style>
