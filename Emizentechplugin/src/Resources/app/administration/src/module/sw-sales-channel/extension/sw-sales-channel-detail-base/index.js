const { Criteria } = Shopware.Data;
import template from './sw-sales-channel-detail-base.html.twig';

Shopware.Component.override('sw-sales-channel-detail-base', {
    template,
    inject: ['repositoryFactory'],
    data() {
        return {
            cities: [],
            selectedCity: null,
            storelocatorRepository: null
        };
    },
   created() {
        this.storelocatorRepository = this.repositoryFactory.create('storelocator');
        this.fetchCities();
        this.loadSelectedCity();
    },
   methods: {
        fetchCities() {
            this.cities = [];
            this.storelocatorRepository.search(new Criteria(), Shopware.Context.api).then((result) => {
                this.cities = result;
            });
        },
        loadSelectedCity() {
            if (this.salesChannel.storelocatorId) {
                this.selectedCity = this.salesChannel.storelocatorId;
            }
        },
        onChangeCityDropdown(cityId) {
            this.selectedCity = cityId;
            this.salesChannel.storelocatorId = this.selectedCity;
        },
    }
});