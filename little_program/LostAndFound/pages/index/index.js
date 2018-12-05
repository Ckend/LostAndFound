//index.js
//获取应用实例
const app = getApp()
var sliderWidth = 96; // 需要设置slider的宽度，用于计算中间位置
Page({
  data: {
    LostData: [],
    activeIndex: 0,
    sliderOffset: 0,
    sliderLeft: 0,
    school: 0,
    inputShowed: false,
    inputVal: "",

    schoolName: '',
    userAll: [],

    LoadingComplete: false,
    PageNum: 0
  },
  onLoad: function () {
    var that = this;
    this.loadData();
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          sliderLeft: (res.windowWidth / that.data.userAll.length - sliderWidth) / 2,
          sliderOffset: res.windowWidth / that.data.userAll.length * that.data.activeIndex
        });
      }
    })
  },
  tabClick: function (e) {
    var that = this;
    this.setData({
      sliderOffset: e.currentTarget.offsetLeft,
      activeIndex: e.currentTarget.id
    });
    this.data.PageNum = 0;
    this.loadData();
  },
  open: function () {
    var that = this;
    wx.showActionSheet({
      success: function (res) {
        if (!res.cancel) {
          console.log(res.tapIndex);
          that.setData({
            school : res.tapIndex
          })
        }
      }
    })
  },
  showInput: function () {
    this.setData({
      inputShowed: true
    });
  },
  hideInput: function () {
    this.setData({
      inputVal: "",
      inputShowed: false
    });
    this.loadData();
  },
  clearInput: function () {
    this.setData({
      inputVal: ""
    });
    this.loadData();
  },
  inputTyping: function (e) {
    this.setData({
      inputVal: e.detail.value
    });
    console.log(this.inputVal);
    this.searchData(this.data.inputVal);
  },
  loadData: function (record) {
    var that = this;
    that.data.LostData = [];
    wx.request({
      url: 'https://laf.alltoshare.com/api/getBase',
      method: 'get',
      header: { 'content-type': 'application/json' },
      success: function (res) {
        that.setData({
          schoolName: res.data[0].school_name,
          userAll: res.data[1]
        });
        var that2 = that;
        that.getData();
      }
    })
  },
  getData: function (record) {
    var that = this;
    wx.request({
      url: 'https://laf.alltoshare.com/api/getLostData/' + that.data.PageNum*10 + '/10/' + that.data.userAll[that.data.activeIndex].name,
      method: 'get',
      header: { 'content-type': 'application/json' },
      success: function (res) {
        let searchList = [];
        searchList = that.data.LostData.concat(res.data);
        that.setData({
          LostData: searchList,
        });  
      }
    })
  },
  searchData: function (word) {
    var that = this;
    wx.request({
      url: 'https://laf.alltoshare.com/api/getBase',
      method: 'get',
      header: { 'content-type': 'application/json' },
      success: function (res) {
        that.setData({
          schoolName: res.data[0].school_name,
          userAll: res.data[1]
        });
        var that2 = that;
        wx.request({
          url: 'https://laf.alltoshare.com/api/search/' + word + '/0/10/' + that.data.userAll[that.data.activeIndex].name,
          method: 'get',
          header: { 'content-type': 'application/json' },
          success: function (res) {
            console.log(res.data);
            that2.setData({
              LostData: res.data
            });
          }
        })
      }
    })
  },
  onReachBottom() {
    var that = this;
    setTimeout(() => {
      // 模拟请求数据，并渲染
      if (that.data.PageNum == 0){
        that.data.PageNum = 1;
      }
      that.getData();
      that.setData({
        PageNum: that.data.PageNum + 1
      });

      // 数据成功后，停止下拉刷新
      wx.stopPullDownRefresh();
    }, 1000);
  }
})
