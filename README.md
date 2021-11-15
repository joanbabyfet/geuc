## About
基于CMS搭建的禹臣实业eProduct产品系统，后台使用layui前端框架实现，提供店铺管理、商品管理、商品分类等模块  
技术要点：laravel 框架，无限级商品分类，lightbox 相册应用，redis，mongodb 存储日志

## Feature
* 界面足够简洁清爽的CMS
* 官网模块支持产品资讯、无限级分类、新闻讯息、展示中心相册、联络我们
* 完善的后台模块，包括会员、店铺、商品、分类、用户、权限、菜单、配置、日志、redis等
* 接口支持jwt与参数签名，强化安全性
* 日志支持MongoDB与mysql存储
* 基于layui编写的最简单、易用的后台框架模板

## Requires
PHP 7.2 or Higher  
Redis  
MongoDB 3.2 or Higher

## Install
```
composer install
cp .env.example .env
php artisan app:install
php artisan jwt:secret
```

## Usage
```
# Login Admin
username: admin
password: Bb123456
```

## Change Log
v1.0.0 - 2021-11-15
* 增加店铺管理、商品管理、商品分类、商品颜色功能
* 增加会员管理、会员等级、登入日志功能
* 增加新闻管理、新闻分类功能
* 增加H5管理功能
* 增加用户管理、用户组别功能
* 增加菜单管理功能
* 增加权限管理、权限组别功能
* 增加操作日志、登入日志、api访问日志功能
* 增加配置功能
* 增加redis键值管理、redis服务器信息功能
* 接口增加参数签名与jwt认证机制

## Maintainers
Alan

## LICENSE
[MIT License](https://github.com/joanbabyfet/geuc/blob/master/LICENSE)
