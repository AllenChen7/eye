# framework
###base framework

#### 包含
```
1、laravel7 基础框架

2、dingo/api 用于接口统一处理

3、tymon/jwt-auth jwt 接口验证

4、doctrine/dbal 用于 migrate 扩展

5、barryvdh/laravel-debugbar 用于 debug

7、barryvdh/laravel-ide-helper 代码提示及补全工具

    php artisan ide-helper:generate - 为 Facades 生成注释
    php artisan ide-helper:models - 为数据模型生成注释
    php artisan ide-helper:meta - 生成 PhpStorm Meta file

8、overtrue/wechat 微信开发相关

9、yansongda/pay 支付宝、微信支付

10、predis/predis 使用 redis

11、laravel/horizon 可视化队列监控面板

12、intervention/image 图片处理、如果将要开发的项目需要较专业的图片，请考虑 ImageMagic
```

> 通用 helpers

### 示例

```
重置所有数据库迁移
> php artisan migrate:refresh

重置所有数据库迁移并创建模拟数据：tips 正式环境还是不要执行这个了
> php artisan migrate:refresh --seed

创建模拟数据
> php artisan make:seeder UsersTableSeeder
