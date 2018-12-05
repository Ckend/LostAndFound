<template>
    <mu-card style="text-align: center">
        <mu-card-text>
            <mu-text-field v-model="foundTitle" hintText="寻物标题" style="width: 90%;"/>
            <br/>
            <mu-text-field v-model="lostPlace" hintText="丢失地点" style="width: 90%;"/>
            <br/>
            <mu-row gutter style="margin: 10px">
                <mu-col width="40" tablet="40" desktop="40">
                    <mu-date-picker autoOk v-model="uploadPickTimeDate" hintText="丢失的日期"/>
                </mu-col>
                <mu-col width="40" tablet="40" desktop="40">
                    <mu-time-picker v-model="uploadPickTimeHour" hintText="具体时间" format="24hr"/>
                    <br/>
                </mu-col>
            </mu-row>
            <mu-text-field v-model="detail" hintText="寻物详情" multiLine :rows="3" :rowsMax="6"/>

            <br/>
            <mu-raised-button class="demo-raised-button" label="上传失物图片(<2M)">
                <input type="file" id="theImg" class="file-button" @change="uploadImg">
            </mu-raised-button>
            <h4 v-show="uploadSuccess" style="">已上传: {{imgReturn}} - 等待提交。</h4>
            <br/>

            <br/>
            <mu-text-field v-model="stuNumber" hintText="学号(用于撤回寻物启事)" style="width: 90%"/>
            <br/>
            <mu-text-field v-model="QQ" hintText="QQ/微信" style="width: 90%"/>
            <br/>
            <mu-text-field v-model="Phone" hintText="手机" style="width: 90%"/>
            <br/>
        </mu-card-text>
        <mu-raised-button label="提交" class="demo-raised-button" primary @click="submit"/>
        <mu-dialog :open="dialog" title="提示">
            {{msg}}
            <mu-flat-button label="确定" slot="actions" primary @click="close"/>
        </mu-dialog>
        <!--<mu-card-actions>-->
    </mu-card>
</template>
<script>
    export default {
        data () {
            return {
                foundTitle: '',
                lostPlace: '',
                uploadPickTimeDate: '',
                uploadPickTimeHour: '',
                imgReturn: '',
                img: '',
                detail: '',
                QQ: '',
                Phone: '',
                stuNumber: '',
                msg: '',
                dialog: false,
                uploadSuccess: false,
            }
        },
        created: function () {
        },
        methods: {
            submit () {
                this.dialog = true
                this.uploadSchool = '石牌校区'
                if (this.isCard) {
                    if (this.foundTitle === '') {
                        this.msg = '寻物标题不能为空！'
                        return
                    } else if (this.stuNumber === '') {
                        this.msg = '学号不能为空！'
                        return
                    } else if (this.QQ === ''|| this.Phone === '') {
                        this.msg = 'QQ或手机必须填写一项！'
                        return
                    }
                }

                let that = this
                const Box = new FormData()
                Box.append('file', this.img)
                Box.append('foundTitle', this.foundTitle)
                Box.append('lostPlace', this.lostPlace)
                Box.append('uploadPickTimeDate', this.uploadPickTimeDate)
                Box.append('uploadPickTimeHour', this.uploadPickTimeHour)
                Box.append('detail', this.detail)
                Box.append('QQ', this.QQ)
                Box.append('Phone', this.Phone)
                Box.append('stuNumber', this.stuNumber)
                // 发送创建请求
                this.axios({
                    method: 'post',
                    url: '/api/uploadFounds',
                    data: Box
                }).then(function (res) {
                    that.msg = '保存成功！'
                }).catch(function (err) {
                    console.log(err)
                    that.msg = '发生了错误，请确认输入无误'
                })
            },
            close () {
                this.dialog = false
                location.reload()
            },
            uploadImg () {
                let _file = document.getElementById('theImg').files[0]
                this.img = _file
                this.uploadSuccess = true
                this.imgReturn = _file.name
            }
        }
    }
</script>
<style>
    .mu-text-field-input {
        text-align: center;
    }
</style>