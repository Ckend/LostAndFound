//index.js
//获取应用实例
const app = getApp()
var sliderWidth = 96; // 需要设置slider的宽度，用于计算中间位置
Page({
  data: {
    tabs: ["失物招领", "寻物启事"],
    LostData: {},
    activeIndex: 0,
    sliderOffset: 0,
    sliderLeft: 0,
    school: 0,
    inputShowed: false,
    inputVal: ""
  },
  onLoad: function () {
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          sliderLeft: (res.windowWidth / that.data.tabs.length - sliderWidth) / 2,
          sliderOffset: res.windowWidth / that.data.tabs.length * that.data.activeIndex
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
  },
  open: function () {
    var that = this;
    wx.showActionSheet({
      itemList: ['石牌校区', '大学城校区', '南海校区'],
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
  },
  clearInput: function () {
    this.setData({
      inputVal: ""
    });
  },
  inputTyping: function (e) {
    this.setData({
      inputVal: e.detail.value
    });
  }
})
