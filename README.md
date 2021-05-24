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

13、dcat/laravel-admin 后台管理
```

> 通用 helpers

### 示例

```
重置所有数据库迁移
> php artisan migrate:refresh

重置所有数据库迁移并创建模拟数据：tips 正式环境还是不要执行这个了
> php artisan migrate:refresh --seed

重置数据迁移后可能会没有 admin 的初始化数据，可以再次执行
> php artisan admin:install

创建模拟数据
1. 创建模拟数据工厂
> php artisan make:factory PostFactory
2. 批量数据生成
> php artisan make:seeder PostsTableSeeder

migrate 时外键约束 合理谨慎使用
> $table->foreignId('admin_user_id')->index()->comment('作者ID')->constrained();

生成 model 并创建迁移文件
> php artisan make:model Models/Post -m

dcat 配置资源访问地址
> $router->resource('posts', 'PostController');
