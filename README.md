# 可视化运维

## 介绍
本系统是采用 php 开发的, 可以界面化操作的简单运维系统

## 功能
- 连接任意服务器
- 执行任意命令

## 说明
本系统比对 `aliyun` 的 `ECS` 服务器, 可以无缝操作

## 如何使用
- 点击进入导航栏的`可视化运维`, 进入需要登录, 方便个人命令的持久化
- `执行操作`, `命令列表`, `服务器列表`, `预设命令` .一次对应
    - `服务器列表` : 要操作的服务器
    - `命令列表` : 自定义的一些命令
    - `预设命令` : 系统预设的一些使用频率比较高的命令,不定期增加
    - `执行操作` : 通过设定服务器, 和命令, 根据顺序, 依次构建不同的任务

### 使用场景
如, 单服务器操作(项目更新):
> 连接服务器 -> 切换到项目根目录 -> 执行git拉取 -> 执行composer更新

或者, 多服务器操作(项目的全量上线):
> 进入测试服务器 -> 打包项目 -> 进入正式服务器 -> 拉取测试服务器项目 -> 解压项目

## 展望功能
- 完善一些功用性比较高的命令, 作为系统预置命令
- 完善 `LNMP` 一键部署到阿里云服务器, 只是把手动 `yum` 安装给自动化, 相当于手动安装的, 不影响后续单个使用和维护
- 完善相关常用组建的一键式安装