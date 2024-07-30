const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

import template from './emizen-module-list.html.twig'; // template which will show data

Component.register('emizen-module-list', {
    template,

    inject: [
        'repositoryFactory',
    ],

    mixins: [
        Mixin.getByName('notification')
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            repository: null,
            items: [],
            isLoading: true,
            showDeleteModal: false,
            itemToDelete: null,
            columns: [
                { property: 'city', label: 'City', align: 'left' },
                { property: 'lat', label: 'Latitude', align: 'left' },
                { property: 'long', label: 'Longitude', align: 'left' },
                { property: 'actions', label: 'Actions', align: 'center', useCustomRender: true }
            ],
        };
        /*return {
            repository: null,
            items: null,
            isLoading: true,
        };*/
        /*return {
            columns: [
                { property: 'city', label: 'City' },
                { property: 'lat', label: 'Latitude' },
                { property: 'long', label: 'Longitude' },
                { property: 'actions', label: 'Actions' } // Add this column for actions
            ],
            total: 0,
            emizenModuleCollection: [],
            repository: this.repositoryFactory.create('storelocator'),
            isLoading:false,
            processSuccess:false,
            form: {
                city: '',
                lat: '',
                long: '',
                id: null // Add id for editing
            },
            formErrors: {}
        };*/
    },

    created() {
        //this.getEmizentechModule();
        this.repository = this.repositoryFactory.create('storelocator');
        this.loadItems();
    },

    // computed: {
    //     columns() {
    //         return [{
    //             property: 'city',  // column property
    //             dataIndex: 'city',
    //             //label: this.$t('emizen-module.list.titleColumn'), // column label (snippets used for labels)
    //             label: 'City', // column label (snippets used for labels)
    //             allowResize: true,
    //             sortable: false,
    //         },
    //             {
    //                 property: 'lat',
    //                 label: 'Lat',
    //                 //label: this.$t('emizen-module.list.descColumn'),
    //                 allowResize: true,
    //                 sortable: false,
    //             },
    //             {
    //                 property: 'long',
    //                 label: 'Long',
    //                 //label: this.$t('emizen-module.list.descColumn'),
    //                 allowResize: true,
    //                 sortable: false,
    //             },
    //             {
    //                 property: 'actions',
    //                 label: 'actions',
    //                 //label: this.$t('emizen-module.list.descColumn'),
    //                 allowResize: true,
    //                 sortable: false,
    //             },
    //         ];
    //     }
    // },

    methods: {
        loadItems() {
            this.repository.search(new Criteria(), Shopware.Context.api).then((result) => {
                this.items = result;
                this.total = result.total;
                this.isLoading = false;
                //console.log(result);
            });
        },
        onDelete(item) {
            this.repository.delete(item.id, Shopware.Context.api).then(() => {
                this.loadItems();
            });
           /* this.itemToDelete = item;
            this.showDeleteModal = true;*/
        },
        /*confirmDelete() {
            if (this.itemToDelete) {
                this.repository.delete(this.itemToDelete.id, Shopware.Context.api).then(() => {
                    this.loadItems();
                    this.showDeleteModal = false;
                    this.itemToDelete = null;
                });
            }
        },*/
        onEdit(item) {
            this.$router.push({ name: 'emizen.module.edit', params: { id: item.id } });
        },
        /*getEmizentechModule: function () {
            const criteria = new Criteria();
            this.repository = this.repositoryFactory.create('storelocator'); // product repository data
            this.repository.search(criteria, Shopware.Context.api).then((result) =>{
                this.emizenModuleCollection = result;
                this.total = result.total;
            })
        }*/
    }
});