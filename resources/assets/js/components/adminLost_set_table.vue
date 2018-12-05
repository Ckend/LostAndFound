<template>
    <div>
        <mu-row gutter style="margin: 10px">
            <mu-col width="50" tablet="50" desktop="50">
                <mu-raised-button label="证件类" v-on:click="changeToCards" :secondary="Boolean(isCard)" fullWidth/>
            </mu-col>
            <mu-col width="50" tablet="50" desktop="50">
                <mu-raised-button label="非证件类" v-on:click="changeToNoCard" :secondary="Boolean(!isCard)" fullWidth/>
            </mu-col>
        </mu-row>
        <mu-card style="text-align: center">
            <div v-if="isCard">
                <mu-card-text>
                    <mu-text-field v-model="uploadName" hintText="姓名" style="width: 90%;"/>
                    <br/>
                    <mu-select-field v-model="uploadAcademy" style="width: 90%" hintText="学院">
                        <mu-menu-item v-for="academy in academysInfo"  :value="academy['academy']" :title="academy['academy']"/>
                    </mu-select-field>
                    <mu-text-field v-model="uploadStuNumber" hintText="学号" style="width: 90%"/>
                    <br/>
                    <mu-select-field v-model="uploadType" style="width: 90%" hintText="卡的类型">
                        <mu-menu-item value="学生卡" title="学生卡"/>
                        <mu-menu-item value="临时卡" title="临时卡"/>
                        <mu-menu-item value="教工卡" title="教工卡"/>
                        <mu-menu-item value="家属卡" title="家属卡"/>
                        <mu-menu-item value="银行卡" title="银行卡"/>
                        <mu-menu-item value="身份证" title="身份证"/>
                        <mu-menu-item value="其他" title="其他"/>
                    </mu-select-field>
                    <mu-text-field v-model="uploadPickLocation" hintText="捡到的地点" style="width: 90%"/>
                    <br/>
                    <mu-row gutter style="margin: 10px">
                        <mu-col width="40" tablet="40" desktop="40">
                            <mu-date-picker autoOk v-model="uploadPickTimeDate" hintText="捡到的日期"/>
                        </mu-col>
                        <mu-col width="40" tablet="40" desktop="40">
                            <mu-time-picker v-model="uploadPickTimeHour" hintText="具体时间" format="24hr"/>
                            <br/>
                        </mu-col>
                    </mu-row>
                    <mu-date-picker autoOk v-model="showTime" hintText="展示日期"/>

                    <mu-select-field v-model="uploadGetPlace" style="width: 90%" hintText="领取地点">
                        <mu-menu-item v-for="place in placesInfo"  :value="place['place']" :title="place['place']"/>
                    </mu-select-field>
                    <mu-text-field v-model="remarks" hintText="备注" multiLine :rows="3" :rowsMax="6"/>
                    <br/>
                </mu-card-text>
            </div>
            <div v-else>
                <mu-card-text>
                    <mu-text-field v-model="uploadType" hintText="失物名称" style="width: 90%"/>
                    <br/>
                    <mu-text-field v-model="uploadPickLocation" hintText="捡到的地点" style="width: 90%"/>
                    <br/>
                    <mu-raised-button class="demo-raised-button" label="上传失物图片(<2M)">
                        <input type="file" id="theImg" class="file-button" @change="uploadImg">
                    </mu-raised-button>
                    <h4 v-show="uploadSuccess" style="">已上传: {{imgReturn}} - 等待提交。</h4>
                    <mu-row gutter style="margin: 10px">
                        <mu-col width="40" tablet="40" desktop="40">
                            <mu-date-picker autoOk v-model="uploadPickTimeDate" hintText="捡到的日期"/>
                        </mu-col>
                        <mu-col width="40" tablet="40" desktop="40">
                            <mu-time-picker v-model="uploadPickTimeHour" hintText="具体时间" format="24hr"/>
                            <br/>
                        </mu-col>
                    </mu-row>
                    <mu-select-field v-model="uploadGetPlace" style="width: 90%" hintText="领取地点">
                        <mu-menu-item v-for="place in placesInfo"  :value="place['place']" :title="place['place']"/>
                    </mu-select-field>
                    <mu-text-field v-model="remarks" hintText="备注" multiLine :rows="3" :rowsMax="6"/>
                    <br/>
                </mu-card-text>
            </div>
            <mu-raised-button label="提交" class="demo-raised-button" primary @click="submit"/>
            <mu-dialog :open="dialog" title="提示">
                {{msg}}
                <mu-flat-button label="确定" slot="actions" primary @click="close"/>
            </mu-dialog>
            <!--<mu-card-actions>-->
        </mu-card>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                isCard: 1,
                uploadName: '',
                uploadSchool: '',
                uploadAcademy: '',
                uploadStuNumber: '',
                uploadType: '',
                showTime: '',
                uploadPickLocation: '',
                uploadPickTimeDate: '',
                uploadPickTimeHour: '',
                uploadGetPlace: '',
                id: '',
                remarks: '',

                imgReturn: '',
                dialog: false,
                msg: '',
                uploadSuccess: false
                // 这个Success是图片的
            }
        },
        props: ['item', "userInfo", "academysInfo", "placesInfo"],
        created: function () {
            // 下面是修改表单的
            if (this.item) {
                this.isCard = this.item.isCard
                this.uploadName = this.item.stuName
                this.uploadSchool = this.item.school
                this.uploadAcademy = this.item.academy
                this.uploadStuNumber = this.item.stuNumber
                this.uploadType = this.item.lostType
                this.uploadPickLocation = this.item.pickLocation
                this.uploadGetPlace = this.item.getPlace
                this.showTime = this.item.showTime
                this.imgReturn = this.item.ImgPath
                this.remarks = this.item.remarks
                this.id = this.item.id

                var theTime = new Date(this.item.pickTime)
                this.uploadPickTimeDate = theTime.toLocaleDateString().replace(/\//g, '-')
                this.uploadPickTimeHour = theTime.toLocaleTimeString().substr(2, 5)
                if (this.isCard === false) {
                    this.uploadSuccess = true
                }
            }
        },
        methods: {
            changeToCards (val) {
                this.isCard = 1
            },
            changeToNoCard (val) {
                this.isCard = 0
            },
            uploadImg () {
                let _file = document.getElementById('theImg').files[0]
                this.img = _file
                this.uploadSuccess = true
                this.imgReturn = _file.name
            },
            submit () {
                this.dialog = true
                this.uploadSchool = this.userInfo.name
                if (this.uploadGetPlace === '') {
                    this.msg = '喂喂喂，领取地点别忘了！'
                    return
                }
                let that = this
                const Box = new FormData()
                Box.append('file', this.img)
                Box.append('uploadSchool', this.uploadSchool)
                Box.append('uploadAcademy', this.uploadAcademy)
                Box.append('uploadStuNumber', this.uploadStuNumber)
                Box.append('uploadType', this.uploadType)
                Box.append('uploadPickLocation', this.uploadPickLocation)
                Box.append('uploadPickTimeDate', this.uploadPickTimeDate)
                Box.append('uploadPickTimeHour', this.uploadPickTimeHour)
                Box.append('uploadGetPlace', this.uploadGetPlace)
                Box.append('uploadIsCard', this.isCard)
                Box.append('showTime', this.showTime)
                Box.append('remarks', this.remarks)
                Box.append('uploadName', this.uploadName);
                if (this.id) {
                    // 发送修改请求
                    Box.append('id', this.id);
                    Box.append('_method', 'PUT');
                    this.axios({
                        method: 'post',
                        url: '/admin/api/uploadLosts/' + this.id,
                        data: Box,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(function (res) {
                        that.msg = res['data']
                    }).catch(function (err) {
                        console.log(err)
                        that.msg = '发生了错误，请确认输入无误'
                    })
                } else {
                    // 发送创建请求
                    this.axios({
                        method: 'post',
                        url: '/admin/api/uploadLosts',
                        data: Box
                    }).then(function (res) {
                        that.msg = '保存并发送成功！'
                    }).catch(function (err) {
                        console.log(err)
                        that.msg = '发生了错误，请确认输入无误'
                    })
                }
            },
            close () {
                this.dialog = false
                location.reload()
            }
        }
    }
</script>
<style>
    .demo-raised-button {
        margin: 12px;
    }

    .file-button {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        opacity: 0;
    }

    .mu-text-field-input {
        text-align: center;
    }

    .mu-menu-item-title {
        margin-left: auto;
        margin-right: auto;
    }

    .mu-dropDown-menu-text-overflow {
        text-align: center;
    }
</style>
