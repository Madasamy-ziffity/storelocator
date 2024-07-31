const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;
import template from './store-inventory-edit.html.twig'; // template which will show data

Component.register('store-inventory-edit', {
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
        this.loadEntity();
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
        loadEntity() {
            const repository = this.repositoryFactory.create('store_inventory_stock');
            repository.get(this.$route.params.id, Shopware.Context.api).then((entity) => {
                this.selectedProduct = entity.product_number;
                this.selectedCity = entity.city;
                this.form.stock = entity.stock;
            });
        },
        async onUpdate() {
            this.clearErrors();
            if (this.validateForm()) {
                const repository = this.repositoryFactory.create('store_inventory_stock');
                repository.get(this.$route.params.id, Shopware.Context.api).then((entity) => {
                    entity.product_number = this.selectedProduct;
                    entity.city = this.selectedCity;
                    entity.stock = this.form.stock;

                    repository.save(entity, Shopware.Context.api).then(() => {
                        this.$router.push({name: 'store.inventory.list'});
                        this.createNotificationSuccess({
                            title: 'Success',
                            message: 'Store inventory details updated successfully.'
                        });
                    }).catch((error) => {
                        // Handle the error
                    });
                });
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
    },
});