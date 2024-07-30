const { Component, Mixin } = Shopware;

import template from './emizen-module-create.html.twig'; // template which will show data

Component.register('emizen-module-create', {
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
    methods: {
        validateForm() {
            this.formErrors.city = !this.form.city ? 'City is required' : null;
            this.formErrors.lat = !this.form.lat ? 'Latitude is required' : null;
            this.formErrors.long = !this.form.long ? 'Longitude is required' : null;

            return !this.formErrors.city && !this.formErrors.lat && !this.formErrors.long;
        },
        async checkForDuplicateRecord() {
            const criteria = new Shopware.Data.Criteria();
            criteria.addFilter(
                Shopware.Data.Criteria.equals('city', this.form.city),
                //Shopware.Data.Criteria.equals('lat', this.form.lat),
                //Shopware.Data.Criteria.equals('long', this.form.long)
            );

            const existingRecords = await this.storelocatorRepository.search(criteria, Shopware.Context.api);

            return existingRecords.total > 0;
        },
        async onSave() {

            if (this.validateForm()) {
                if (await this.checkForDuplicateRecord()) {
                    this.isLoading = false;
                    this.formErrors.city = 'Duplicate record found';
                    //this.formErrors.lat = 'Duplicate record found';
                    //this.formErrors.long = 'Duplicate record found';
                    return;
                }
                const storelocator = this.storelocatorRepository.create();
                storelocator.city = this.form.city;
                storelocator.lat = this.form.lat;
                storelocator.long = this.form.long;
                try {
                    await this.storelocatorRepository.save(storelocator, Shopware.Context.api);
                    this.createNotificationSuccess({
                        title: 'Success',
                        message: 'Store locator details saved successfully.'
                    });
                    this.$router.push({ name: 'emizen.module.list' });
                } catch (error) {
                    this.createNotificationError({
                        title: 'Error',
                        message: error
                    });
                }
            }
        },
    },
    /*onEditItem(item) {
        // Populate the form with the selected item's data
        this.form.id = item.id;
        this.form.city = item.city;
        this.form.lat = item.lat;
        this.form.long = item.long;
    },*/
});