<template>
    <mu-card style="text-align: center">
        <mu-card-text>
            <mu-text-field v-model="takenName" hintText="姓名" style="width: 90%;"/>
            <br/>
            <mu-select-field v-model="takenAcademy" style="width: 90%" hintText="学院">
                <mu-menu-item v-for="academy in academysInfo"  :value="academy['academy']" :title="academy['academy']"/>
            </mu-select-field>
            <mu-text-field v-model="takenNumber" hintText="学号" style="width: 90%"/>
            <br/>
            <mu-text-field v-model="takenPhone" hintText="手机" style="width: 90%"/>
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
        props: ['theitem','academysInfo'],
        data () {
            return {
                takenName: '',
                takenAcademy: '',
                takenNumber: '',
                takenPhone: '',
                msg: '',
                dialog: false
            }
        },
        created: function () {
            console.log(this.theitem.id);
        },
        methods: {
            submit () {
                let that = this;
                const Box = new FormData();
                Box.append('_method', 'PUT');

                Box.append('id', this.theitem.id);
                Box.append('takenName', this.takenName);
                Box.append('takenAcademy', this.takenAcademy);
                Box.append('takenNumber', this.takenNumber);
                Box.append('takenPhone', this.takenPhone);
                this.axios({
                    method: 'post',
                    url: '/admin/api/uploadLosts/' + this.theitem.id,
                    data: Box,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(function (res) {
                    that.dialog = true;
                    that.msg = res['data']
                }).catch(function (err) {
                    console.log(err);
                    that.msg = '发生了错误，请确认输入无误'
                })
            },
            close () {
                this.dialog = false;
                location.reload()
            }
        }
    }
</script>
