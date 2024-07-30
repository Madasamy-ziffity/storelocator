const { Module } = Shopware;
import './page/emizen-module-list';
import './page/emizen-module-create';
import './page/emizen-module-edit';
import enGB from './snippet/en-GB';
import deDE from './snippet/de-DE';

Module.register('emizen-module', {
    type: 'plugin',
    title: 'emizen-module.general.mainMenuItemList',
    description: 'emizen-module .general.descriptionTextModule',
    snippets: {
        'en-GB': enGB,
        'de-De': deDE
    },

    routes: {
        'list': {
            component: 'emizen-module-list',
            path: 'list',
            meta: {
                parentPath: 'sw-catalogue.index'
            }
        },
        'create': {
            component: 'emizen-module-create',
            path: 'create',
            meta: {
                parentPath: 'emizen.module.list'
            }
        },
        'edit': {
            component: 'emizen-module-edit',
            path: 'edit/:id',
            meta: {
                parentPath: 'emizen.module.list',
            }
        }
    },
    navigation: [{
        label: 'emizen-module.general.mainMenuItemList',
        color: '#ff3d58',
        path: 'emizen.module.list',
        icon: 'default-action-settings',
        parent: 'sw-catalogue',
        position: 1100
    }]
});