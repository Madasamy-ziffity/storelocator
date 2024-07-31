const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

import template from './store-inventory-create.html.twig'; // template which will show data
Component.register('store-inventory-create', {
    template,
    inject: ['repositoryFactory'],
    mixins: [
        Mixin.getByName('notification')
    ],
    computed: {
        storeinventoryRepository() {
            return this.repositoryFactory.create('store_inventory_stock');
        },
    },
    data() {
        return {
            form: {
                selectedCity: '',
                selectedProduct: '',
                stock: ''
            },
            errors: {
                selectedProduct: '',
                selectedCity: '',
                stock: ''
            },
            cities: [],
            products:[],
        };
    },
    created() {
        this.storelocatorRepository = this.repositoryFactory.create('storelocator');
        this.loadCities();
        this.loadProducts();
    },
    methods: {
        loadCities() {
            this.cities = [];
            this.storelocatorRepository.search(new Criteria(), Shopware.Context.api).then((result) => {
                this.cities = result;
            });
        },
        loadProducts(){
            this.products = [];
            this.productdataRepository = this.repositoryFactory.create('product');
            this.productdataRepository.search(new Criteria(), Shopware.Context.api).then((result) => {
                this.products = result;
            });
        },
        async onSave() {
            this.clearErrors();
            if (this.validateForm()) {
                const storeinventory = this.storeinventoryRepository.create();
                storeinventory.product_number = this.selectedProduct;
                storeinventory.city = this.selectedCity;

                storeinventory.stock = this.form.stock;
                try {
                    await this.storeinventoryRepository.save(storeinventory, Shopware.Context.api);
                    this.createNotificationSuccess({
                        title: 'Success',
                        message: 'Store inventory details saved successfully.'
                    });
                    this.$router.push({name: 'store.inventory.list'});
                } catch (error) {
                    this.createNotificationError({
                        title: 'Error',
                        message: error
                    });
                }
            }
        },
        validateForm() {
            let isValid = true;
            if (!this.selectedProduct) {
                this.errors.selectedProduct = 'Product is required';
                isValid = false;
            }
            if (!this.selectedCity) {
                this.errors.selectedCity = 'City is required';
                isValid = false;
            }
            if (!this.form.stock) {
                this.errors.stock = 'Stock is required';
                isValid = false;
            }
            return isValid;
        },
        clearErrors() {
            this.errors.selectedProduct = '';
            this.errors.selectedCity = '';
            this.errors.stock = '';
        }
    }
});