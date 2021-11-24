## yii-module-iit-partners

[Докуметация](docs/api/index.html)

Модуль позволяет управлять [сущностями](docs/api/namespaces/devskyfly.yiiModuleIitUc.models.html):

* Регионы
* Населенные пункты
* Агенты

Модуль имеет свое rest [api](docs/api/namespaces/devskyfly.yiiModuleIitUc.controllers.rest.html).

### Консольные команды:

* iit-partners/agents                              
* iit-partners/agents/clear - delete agents items.
* iit-partners/agents/reset-need-to-custom-flag
* iit-partners/agents/update - update agents and add settlements if it needs.

* iit-partners/lk                                  
* iit-partners/lk/send-request-for-agents - send request to Lk and print result to stdout.
* iit-partners/lk/send-request-for-orgs

* iit-partners/regions                             
* iit-partners/regions/clear - clear regions.
* iit-partners/regions/init - init region table from external file.

* iit-partners/settlements                         
* iit-partners/settlements/clear - delete Settlements items.

### Подключение модуля

Модуль надо подключить как к web так и console.

```php
'iit-partners'=>[
    'class'=>'devskyfly\yiiModuleIitPartners\Module',
    'lk_login'=>'*',
    'lk_pass'=>'*',
    'lk_url'=>'',
]
```

### Применение миграций

./yii migrate --migrationPath="@app/vendor/devskyfly/yii-module-iit-partners/migrations"
