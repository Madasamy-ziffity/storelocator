import template from './sw-sales-channel-detail.html.twig';
const { Criteria } = Shopware.Data;

Shopware.Component.override('sw-sales-channel-detail', {
    template,
    inject: ['repositoryFactory'],
   data() {
        return {
            selectedCity: null,
            cities: []
        };
    },
   methods: {
        onSave() {
            var store_locator_id = this.salesChannel.storelocatorId;
            const repository = this.repositoryFactory.create('sales_channel');
            repository.get(this.$route.params.id, Shopware.Context.api).then((entity) => {
                entity.storelocator_id = store_locator_id;
                repository.save(entity, Shopware.Context.api).then(() => {
                }).catch((error) => {
                });
            });
            this.$super('onSave');
        }
    }
});