## 失物招领系统使用说明

本章主要包括本系统的使用说明。

项目演示地址： https://laf.alltoshare.com

使用本项目建议： 由于是大二时的作品，设计有点稚嫩，数据库中表的构建略为冗杂，你可以进行改进；前端打包生成的app.js约1.3M, 可以进一步优化。

欢迎访问我的博客：https://huanxiangke.com

### 后端及web端

下面主要讲解后端和web端的部署方法和使用说明。

#### 部署方法

需要在系统上安装好了基本的PHP运行环境、Mysql、composer、Node.js. 由于这些不是本章主题，这里不赘述，请自行查阅文档解决。

**基础安装(web及后端)**
1.	复制代码到服务器上。
2.	SSH连接服务器，cd到文件夹目录下。
3.	安装扩展包依赖、运行命令: composer install
4.	生成配置文件: cp .env.example .env 
5.	然后在 .env配置文件中修改DB_DATABASE, DB_USERNAME, DB_PASSWORD 的值，分别为数据库名、数据库用户、数据库密码。另外记得赋予storage 777 权限。
6.	执行数据库迁移: php artisan migrate.
7.	生成key: php artisan key:generate
8.	设置域名解析，并且Nginx指向到该文件夹的public中。
9.	部署完成，下面开始使用。

**基础安装(小程序)**
1.	将little_program中的LostAndFound文件夹作为你的小程序主目录。
2.	配置project.config.js中的appid和projectname.
3.	修改pages文件夹下所有的js文件，将wx.request中的https://jizhianshi.com/ 修改成你自己的域名或本地地址。
4.	部署完成。

**系统配置**

1.	注册一个超级账号，用于管理所有校区和校区学院，以及是否开启企业号自动通知、微博同步等功能。打开网页：http://你的域名/register 在校区名中写入: admin ，其他账号密码设定后需要牢记。
2.	页面会自动跳转到超级后台管理，请自行配置学校名称、微博账号密码及token、企业号配置（如果不需要可以关闭）和校区及学院。
3.	增加校区后要记得回到超级后台管理页面增加校区的学院。
4.	修改title.(app.blade.php/ phone.blade.php)

注意：
1.	所有校区的登录页面都是http://你的域名/admin 包括超级后台。登录时候输入账号密码就会自动跳转到那个校区的后台。
2.	DEMO：http://laf.alltoshare.com/

## 系统介绍

本系统的整体架构如下图所示。

![laf_1](https://huanxiangke.com/storage/app/media/Now/laf_1.png)

失物招领的整体流程如下：

![laf_2](https://huanxiangke.com/storage/app/media/Now/laf_2.png)

### 失物招领web端

这一部分包括系统展示页和后台管理页、企业号自动通知和微博同步功能。

#### 系统展示页

系统展示页包括学校名称、各学院名称、失物简介、证件类与非证件类的查看、搜索框、失物详情。如下图所示

![laf_3](https://huanxiangke.com/storage/app/media/Now/laf_3.png)

#### 后台管理页

包括超级系统管理页面、分校区管理页面。其中超级系统管理页面需要设定学校名称，及各校区基本情况。以及配置微博信息和企业号信息。如下图所示。

![laf_4](https://huanxiangke.com/storage/app/media/Now/laf_4.png)

分校区后台管理页面包括失物基本信息上传和修改，设置已领取、已领取人的信息等功能，如下图所示（建议放大查看）

![laf_5](https://huanxiangke.com/storage/app/media/Now/laf_5.png)
![laf_6](https://huanxiangke.com/storage/app/media/Now/laf_6.png)

#### 企业号自动通知与微博同步功能

为确保同学们能及时收到失物信息，我们设计了卡类失物微信自动通知功能和非卡类失物微博同步功能，如下图所示。

![laf_7](https://huanxiangke.com/storage/app/media/Now/laf_7.png)

![laf_8](https://huanxiangke.com/storage/app/media/Now/laf_8.png)

### 失物招领小程序端

从同学们的调查问卷结果中我们可以知道，90%的同学通过微信来获取信息，因此抓住手机端的优势非常重要，所以我们开发了小程序端来相应同学们的需求。如下图所示。

其所有数据和web端是同步的。

![laf_9](https://huanxiangke.com/storage/app/media/Now/laf_9.png)

## 软件架构和开发环境

本系统采用前后端分离的形式进行设计、前端使用的框架是Vue、后端使用的
框架是Laravel. 数据库采用MySQL. 开发环境是macOS, 使用的软件是PHPstorm和DataGrip.

### 前端

采用Vue组件化设计、使用了两个组件库：Muse-ui和iview配合设计。

### 后端

采用Laravel典型的MVC设计搭配数据库MySQL。其中本系统开发并使用了6个模型和11个控制器。


技术架构如图所示。其中包括上传在内共有14个数据接口。

![laf_10](https://huanxiangke.com/storage/app/media/Now/laf_10.png)
