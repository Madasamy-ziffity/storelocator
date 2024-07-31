const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

import template from './store-inventory-list.html.twig'; // template which will show data

Component.register('store-inventory-list', {
    template,
    inject: [
        'repositoryFactory',
    ],
    data() {
        return {
            repository: null,
            items: [],
            isLoading: true,
            showDeleteModal: false,
            itemToDelete: null,
            columns: [
                {property: 'product_number', label: 'Product', align: 'left'},
                {property: 'city', label: 'Store', align: 'left'},
                {property: 'stock', label: 'Stock', align: 'left'},
                {property: 'actions', label: 'Actions', align: 'center', useCustomRender: true}
            ],
        };
    },
    created() {
        this.repository = this.repositoryFactory.create('store_inventory_stock');
        this.loadItems();
    },
    methods: {
        loadItems() {
            this.repository.search(new Criteria(), Shopware.Context.api).then((result) => {
                this.items = result;
                this.total = result.total;
                this.isLoading = false;
            });
        },
        onDelete(item) {
            this.repository.delete(item.id, Shopware.Context.api).then(() => {
                this.loadItems();
            });
        },
        onEdit(item) {
            this.$router.push({ name: 'store.inventory.edit', params: { id: item.id } });
        },
    },
});