const { Module } = Shopware;
import './page/store-inventory-list';
import './page/store-inventory-create';
import './page/store-inventory-edit';
import './module.scss';

Module.register('store-inventory', {
    type: 'plugin',
    title: 'Store Inventory',
    description: 'Store Inventory',
    routes: {
        'list': {
            component: 'store-inventory-list',
            path: 'list',
            meta: {
                parentPath: 'sw-catalogue.index'
            }
        },
        'create': {
            component: 'store-inventory-create',
            path: 'create',
            meta: {
                parentPath: 'store.inventory.list'
            }
        },
        'edit': {
            component: 'store-inventory-edit',
            path: 'edit/:id',
            meta: {
                parentPath: 'store.inventory.list',
            }
        }
    },
    navigation: [{
        label: 'Store Inventory',
        color: '#ff3d58',
        path: 'store.inventory.list',
        icon: 'default-action-settings',
        parent: 'sw-catalogue',
        position: 1110
    }]
});