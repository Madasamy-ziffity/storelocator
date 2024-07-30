const { Component, Mixin } = Shopware;

import template from './emizen-module-edit.html.twig'; // template which will show data

Component.register('emizen-module-edit', {
    template,
    inject: ['repositoryFactory'],
    mixins: [
        Mixin.getByName('notification')
    ],
    computed: {
        storelocatorRepository() {
            return this.repositoryFactory.create('storelocator');
        },
    },
    data() {
        return {
            form: {
                city: '',
                lat: '',
                long: ''
            },
            formErrors: {
                city: null,
                lat: null,
                long: null
            }
        };
    },
    created() {
        this.loadEntity();
    },
    methods: {
        loadEntity() {
            const repository = this.repositoryFactory.create('storelocator');
            repository.get(this.$route.params.id, Shopware.Context.api).then((entity) => {
                this.form.city = entity.city;
                this.form.lat = entity.lat;
                this.form.long = entity.long;
            });
        },
        validateForm() {
            this.formErrors.city = !this.form.city ? 'City is required' : null;
            this.formErrors.lat = !this.form.lat ? 'Latitude is required' : null;
            this.formErrors.long = !this.form.long ? 'Longitude is required' : null;

            return !this.formErrors.city && !this.formErrors.lat && !this.formErrors.long;
        },
        /*async checkupdateForDuplicateRecord() {
            const criteria = new Shopware.Data.Criteria();
            criteria.addFilter(Shopware.Data.Criteria.equals('city', this.form.city));
            criteria.addFilter(Shopware.Data.Criteria.not(Shopware.Data.Criteria.equals('id', this.$route.params.id)));
            const existingRecords = await this.storelocatorRepository.search(criteria, Shopware.Context.api);

            return existingRecords.total > 0;
        },*/
        async onUpdate() {

            if (this.validateForm()) {
                /*if (await this.checkupdateForDuplicateRecord()) {
                    this.isLoading = false;
                    this.formErrors.city = 'Duplicate record found';
                    return;
                }*/
                const repository = this.repositoryFactory.create('storelocator');
                repository.get(this.$route.params.id, Shopware.Context.api).then((entity) => {
                    entity.city = this.form.city;
                    entity.lat = this.form.lat;
                    entity.long = this.form.long;

                    repository.save(entity, Shopware.Context.api).then(() => {
                        this.$router.push({ name: 'emizen.module.list' });
                        this.createNotificationSuccess({
                            title: 'Success',
                            message: 'Store locator details updated successfully.'
                        });
                    }).catch((error) => {
                        // Handle the error
                    });
                });
            }
        },
    }
});